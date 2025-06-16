<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RpsMataKuliah extends Model
{
    use HasFactory;

    protected $table = 'rps_matakuliah';

    protected $fillable = [
        'mata_kuliah_id',
        'kemampuan_akhir',
        'minggu',
        'kategori',
        'pokok_bahasan',
        'modalitas_pembelajaran',
        'bentuk_pembelajaran',
        'strategi_pembelajaran',
        'metode_pembelajaran',
        'media_pembelajaran',
        'sumber_belajar',
        'hasil_belajar',
    ];

    /**
     * Relasi ke tabel MataKuliah
     */
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    /**
     * Relasi ke tabel KemampuanAkhir
     */
    // public function kemampuanAkhir()
    // {
    //     return $this->belongsTo(KemampuanAkhir::class, 'kemampuan_akhir_id');
    // }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->validateTotalBobot();
    //     });

    //     static::updating(function ($model) {
    //         $model->validateTotalBobot();
    //     });
    // }

    /**
     * Validasi total bobot_penilaian agar tidak melebihi 100 per mata_kuliah_id
     */
    // public function validateTotalBobot()
    // {
    //     $totalBobot = DB::table('rps_matakuliah')
    //         ->where('mata_kuliah_id', $this->mata_kuliah_id)
    //         ->sum('bobot_penilaian');

    //     if (($totalBobot + $this->bobot_penilaian) > 100) {
    //         throw ValidationException::withMessages([
    //             'bobot_penilaian' => 'Total bobot penilaian untuk mata kuliah ini tidak boleh melebihi 100.',
    //         ]);
    //     }
    // }

    /**
     * Relasi ke tabel TujuanBelajar
     */
    public function tujuanBelajar()
    {
        return $this->belongsTo(TujuanBelajar::class, 'tujuan_belajar_id');
    }

    public function tujuanBelajarRps()
    {
        return $this->belongsTo(TujuanBelajarRPS::class, 'tujuan_belajar_id');
    }


    public function instrumenPenilaians()
    {
        return $this->hasMany(InstrumenPenilaianRps::class, 'rps_id');
    }

    /**
     * Relasi ke tabel CPL
     */
    public function cpl()
    {
        return $this->belongsTo(Cpl::class, 'cpl_id');
    }
}
