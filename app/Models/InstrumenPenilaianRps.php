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

            // Ambil RPS beserta mata_kuliah_id-nya
            $rps = $model->rps ?? RpsMataKuliah::find($model->rps_id);
            if (!$rps) return;

            $mataKuliahId = $rps->mata_kuliah_id;

            // Ambil semua sub_kategori yang sudah digunakan di mata kuliah dan kategori tersebut
            $existingSubKategoris = self::whereHas('rps', function ($query) use ($mataKuliahId) {
                    $query->where('mata_kuliah_id', $mataKuliahId);
                })
                ->where('kategori', $model->kategori)
                ->pluck('sub_kategori')
                ->map(function ($subKategori) use ($kategori) {
                    if (preg_match('/' . preg_quote($kategori, '/') . '-(\d+)/', $subKategori, $matches)) {
                        return (int) $matches[1];
                    }
                    return 0;
                })
                ->toArray();

            // Cari angka yang belum digunakan
            $nextNumber = 1;
            while (in_array($nextNumber, $existingSubKategoris)) {
                $nextNumber++;
            }

            // Set nilai sub_kategori
            $model->sub_kategori = $kategori . '-' . $nextNumber;
        });
    }

    public function rps()
    {
        return $this->belongsTo(RpsMataKuliah::class, 'rps_id');
    }

    public function tujuanBelajar()
    {
        return $this->belongsTo(TujuanBelajarRps::class, 'tujuan_belajar_id');
    }

    public function cpl()
    {
        return $this->belongsTo(Cpl::class, 'cpl_id');
    }

    public static function reindexByMataKuliah(int $mataKuliahId)
    {
        DB::beginTransaction();

        try {
            // Ambil semua RPS berdasarkan mata_kuliah_id, urutkan berdasarkan minggu ASC
            $rpsList = RpsMataKuliah::where('mata_kuliah_id', $mataKuliahId)
                ->orderBy('minggu', 'asc')
                ->get();

            $kategoriCounters = [];

            foreach ($rpsList as $rps) {
                // Ambil instrumen dari RPS ini, urutkan berdasarkan kategori ASC dan ID ASC
                $instrumens = InstrumenPenilaianRps::where('rps_id', $rps->id)
                    ->orderBy('kategori', 'asc')
                    ->orderBy('id', 'asc')
                    ->get();

                foreach ($instrumens as $instrumen) {
                    $kategori = strtolower($instrumen->kategori);

                    // Jika belum ada kategori ini dalam counter, inisialisasi ke 1
                    if (!isset($kategoriCounters[$kategori])) {
                        $kategoriCounters[$kategori] = 1;
                    } else {
                        $kategoriCounters[$kategori]++;
                    }

                    $instrumen->sub_kategori = $kategori . '-' . $kategoriCounters[$kategori];
                    $instrumen->save();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal reindex sub_kategori: ' . $e->getMessage());
            throw $e;
        }
    }
}
