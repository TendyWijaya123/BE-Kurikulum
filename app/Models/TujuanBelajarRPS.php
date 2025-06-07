<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TujuanBelajarRPS extends Model
{
    use HasFactory;

    protected $table = 'tujuan_belajar_rps';

    protected $fillable = [
        'kode',
        'deskripsi',
        'mata_kuliah_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!$model->kode) {
                $lastTujuan = self::where('mata_kuliah_id', $model->mata_kuliah_id)
                    ->orderBy('id', 'desc')
                    ->first();

                $nextNumber = $lastTujuan ? ((int) str_replace('TB-', '', $lastTujuan->kode) + 1) : 1;

                // Set kode baru
                $model->kode = 'TB-' . $nextNumber;
            }
        });

        static::deleted(function ($model) {
            self::reindexKode($model->mata_kuliah_id);
        });
    }

    public function MataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function rpss()
    {
        return $this->hasMany(RpsMatakuliah::class, 'tujuan_belajar_id');
    }

    public function instrumenPenilaian()
    {
        return $this->hasMany(InstrumenPenilaianRps::class, 'tujuan_belajar_id');
    }

    /**
     * Reindex kode untuk TujuanBelajar dalam satu mata_kuliah_id.
     *
     * @param int $Id
     */
    public static function reindexKode(int $Id)
    {
        DB::beginTransaction();
        try {
            // Ambil semua tujuan belajar untuk mata_kuliah_id, urutkan berdasarkan ID
            $tujuanBelajars = self::where('mata_kuliah_id', $Id)
                ->orderBy('id', 'asc')
                ->get();

            // Update kode sesuai urutan baru
            foreach ($tujuanBelajars as $index => $tujuan) {
                $tujuan->kode = 'TB-' . ($index + 1);
                $tujuan->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saat reindex kode TujuanBelajar: ' . $e->getMessage());
            throw $e;
        }
    }
}
