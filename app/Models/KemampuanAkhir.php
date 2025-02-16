<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KemampuanAkhir extends Model
{
    use HasFactory;

    protected $fillable = [
        'deskripsi',
        'estimasi_beban_belajar',
        'mata_kuliah_id',
    ];

    /**
     * Relasi ke MataKuliah
     */
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id', 'id');
    }


    public function rpss()
    {
        return $this->hasMany(RpsMatakuliah::class, 'kemampuan_akhir_id');

    }

    

    /**
     * Relasi ke BentukPembelajaran
     */
    public function bentukPembelajarans()
    {
        return $this->belongsToMany(BentukPembelajaran::class, 'ka_bp', 'kemampuan_akhir_id', 'bentuk_pembelajaran_id');
    }

    /**
     * Relasi ke MetodePembelajaran
     */
    public function metodePembelajarans()
    {
        return $this->belongsToMany(MetodePembelajaran::class, 'ka_mp', 'kemampuan_akhir_id', 'metode_pembelajaran_id');
    }
}
