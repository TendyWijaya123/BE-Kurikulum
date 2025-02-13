<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $fillable = ['nama', 'kategori'];

    public function prodis()
    {
        return $this->hasMany(Prodi::class);
    }

    public function bukuReferensis()
    {
        return $this->hasMany(BukuReferensi::class);
    }
}
