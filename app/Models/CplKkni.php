<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CplKkni extends Model
{
    use HasFactory;

    protected $table = 'cpl_kkni';
    protected $fillable = [
        'code',
        'description',
        'pengetahuan_kkni_id',
        'kemampuan_kerja_id',
        'kurikulum_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!$model->code) {
                // Get the highest kode for the given kurikulum_id
                $lastCpl = self::where('kurikulum_id', $model->kurikulum_id)
                    ->orderBy('id', 'desc')
                    ->first();

                // Determine the next number
                $nextNumber = $lastCpl ? ((int) str_replace('CPL-', '', $lastCpl->code) + 1) : 1;

                // Set the kode
                $model->code = 'CPL-' . $nextNumber;
            }
        });

        static::deleted(function ($model) {


            self::reindexKode($model->kurikulum_id);
        });
    }

    /**
     * Relasi ke BenchKurikulum
     * 
     * BkCpl milik satu BenchKurikulum
     */
    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }

    public function pengetahuanKKNI(){
        return $this->hasMany('pengetahuan_kkni');
    }

    public function kemampuanKerjaKKNI(){
        return $this->hasMany('kemampuan_kerja_kkni');
    }

    public static function reindexKode(int $kurikulumId)
    {
        DB::beginTransaction();
        try {
            $cpls = self::where('kurikulum_id', $kurikulumId)
                ->orderBy('id', 'asc')
                ->get();

            // Iterasi setiap CPL dan perbarui kode-nya
            foreach ($cpls as $index => $cpl) {
                $cpl->code = 'CPL-' . ($index + 1);
                $cpl->save(); // Gunakan save() untuk menyimpan perubahan
            }

            // Commit transaksi jika tidak ada error
            DB::commit();
        } catch (\Exception $e) {
            // Rollback jika terjadi error
            DB::rollback();

            // Menangani error atau melempar kembali error untuk ditangani lebih lanjut
            Log::error('Error saat reindex kode: ' . $e->getMessage());
            throw $e; // Lempar ulang agar dapat ditangani lebih lanjut
        }
    }
}
