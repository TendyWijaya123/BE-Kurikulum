<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormulasiCpa extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'kode',
        'kategori',
        'deskripsi',
    ];

    public function mataKuliahs()
    {
        return $this->belongsToMany(
            MataKuliah::class,
            'mk_formulasi',
            'formulasi_cpa_id',
            'mata_kuliah_id'
        )->withTimestamps();
    }
}
