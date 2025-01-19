<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Cpl extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'keterangan',
        'kurikulum_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!$model->kode) {
                // Get the highest kode for the given kurikulum_id
                $lastCpl = self::where('kurikulum_id', $model->kurikulum_id)
                    ->orderBy('id', 'desc')
                    ->first();

                // Determine the next number
                $nextNumber = $lastCpl ? ((int) str_replace('CPL-', '', $lastCpl->kode) + 1) : 1;

                // Set the kode
                $model->kode = 'CPL-' . $nextNumber;
            }
        });

        static::deleted(function ($model) {


            self::reindexKode($model->kurikulum_id);
        });
    }

    /**
     * Get the kurikulum associated with the CPL.
     */
    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class, 'kurikulum_id');
    }

    /**
     * The ppms associated with the CPL.
     */
    public function ppms()
    {
        return $this->belongsToMany(Ppm::class, 'cpl_ppm', 'cpl_id', 'ppm_id')
            ->withTimestamps();
    }

    public function iea()
    {
        return $this->belongsToMany(Iea::class, 'cpl_iea', 'cpl_id', 'iea_id')
            ->withTimestamps();
    }

    public function pengetahuans(): BelongsToMany
    {
        return $this->belongsToMany(Pengetahuan::class, 'cpl_p', 'cpl_id', 'p_id')
            ->withTimestamps();
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
                $cpl->kode = 'CPL-' . ($index + 1);
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
