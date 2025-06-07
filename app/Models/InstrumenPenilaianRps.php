<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InstrumenPenilaianRps extends Model
{
    use HasFactory;

    // Pastikan nama tabel sesuai dengan migration
    protected $table = 'isntrumen_penilaian';

    protected $fillable = [
        'kategori',
        'tujuan_belajar_id',
        'cpl_id',
        'bobot_penilaian',
        'rps_id',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $kategori = strtolower($model->kategori);
            $count = self::where('rps_id', $model->rps_id)
                        ->where('kategori', $model->kategori)
                        ->count();

            $model->kode = $kategori . '-' . ($count + 1);
        });
    }

    public function rps()
    {
        return $this->belongsTo(RpsMatakuliah::class, 'rps_id');
    }

    public function tujuanBelajar()
    {
        return $this->belongsTo(TujuanBelajarRps::class, 'tujuan_belajar_id');
    }

    public function cpl()
    {
        return $this->belongsTo(Cpl::class, 'cpl_id');
    }

    public static function reindexKode(int $Id)
    {
        DB::beginTransaction();
        try {
            // Ambil semua instrumen penilaian untuk rps_id, urutkan berdasarkan kategori dan id
            $isntrumentPenilaians = self::where('rps_id', $Id)
                ->select('id', 'kode', 'kategori')
                ->orderBy('kategori', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $kategoriCounters = [];

            foreach ($isntrumentPenilaians as $instrumen) {
                $kategori = strtolower($instrumen->kategori);
                if (!isset($kategoriCounters[$kategori])) {
                    $kategoriCounters[$kategori] = 1;
                } else {
                    $kategoriCounters[$kategori]++;
                }
                $instrumen->kode = $kategori . '-' . $kategoriCounters[$kategori];
                $instrumen->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saat reindex kode InstrumenPenilaian: ' . $e->getMessage());
            throw $e;
        }
    }
}
