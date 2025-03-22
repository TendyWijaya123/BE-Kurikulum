<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetaKompetensi extends Model
{
    use HasFactory;

    protected $table = 'peta_kompetensis';

    protected $fillable = [
        'kurikulum_id',
        'gambar_url'
    ];
    public function kurikulum()
    {
        return $this->belongsTo(Prodi::class, 'kurikulum_id');
    }
};
