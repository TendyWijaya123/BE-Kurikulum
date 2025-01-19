<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembelajaran extends Model
{
    //

    use HasFactory;

    protected $fillable = [
        'nama'
    ];

    public function  kemampuanAkhirs()
    {
        return $this->belongsToMany(KemampuanAkhir::class, 'ka_mp', 'metode_pembelajaran_id', 'kemampuan_akhir_id');
    }
}
