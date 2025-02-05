<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KompetensiKerja extends Model
{
    use HasFactory;

    protected $table = 'kompetensi_kerjas';
    protected $fillable = [
        'kompetensi_kerja',
        'sksu_id',
    ];

    // Relasi dengan model Sksu
    public function sksu()
    {
        return $this->belongsTo(Sksu::class);
    }
}
