<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetaKompetensi extends Model
{
    use HasFactory;

    protected $table = 'peta_kompetensis';

    protected $fillable = [
        'prodi_id',
        'gambar_url'
    ];
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }
}
;