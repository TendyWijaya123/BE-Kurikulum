<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MateriPembelajaran extends Model
{
    use HasFactory;

    protected $table = 'materi_pembelajaran';
    protected $fillable = [
        'code',
        'description',
        'cognitif_proses',
        'kurikulum_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!$model->code) {
                // Get the highest kode for the given kurikulum_id
                $lastMp = self::where('kurikulum_id', $model->kurikulum_id)
                    ->orderBy('id', 'desc')
                    ->first();

                // Determine the next number
                $nextNumber = $lastMp ? ((int) str_replace('CPL-', '', $lastMp->code) + 1) : 1;

                // Set the kode
                $model->code = 'MP-' . $nextNumber;
            }
        });

        static::deleted(function ($model) {


            self::reindexKode($model->kurikulum_id);
        });
    }

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }

    public function pengetahuan()
    {
        return $this->belongsToMany(Pengetahuan::class, 'p_mp', 'p_id', 'mp_id');
    }

    public function knowledgeDimension()
    {
        return $this->belongsToMany(KnowledgeDimension::class, 'knowledge_mp', 'mp_id', 'code_knowledge_dimension', 'id', 'code');
    }

    public static function reindexKode(int $kurikulumId) 
    {
        DB::beginTransaction();

        try {
            $mps = self::where('kurikulum_id', $kurikulumId)
                ->orderBy('id', 'asc')
                ->get();

            // Iterasi setiap CPL dan perbarui kode-nya
            foreach ($mps as $index => $mp) {
                $mp->code = 'MP-' . ($index + 1);
                $mp->save(); // Gunakan save() untuk menyimpan perubahan
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
