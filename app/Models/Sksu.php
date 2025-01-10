<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sksu extends Model
{
    use HasFactory;

    protected $table = 'sksus';
    protected $fillable = [
        'profil_lulusan',
        'kualifikasi',
        'kategori',
        'kurikulum_id',
    ];

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }

    public function kompetensiKerja()
    {
        return $this->hasMany(KompetensiKerja::class);
    }
}
