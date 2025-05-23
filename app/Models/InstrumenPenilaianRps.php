<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstrumenPenilaianRps extends Model
{
    use HasFactory;

    protected $table = 'instrumen_penilaian_rps';

    protected $fillable = [
        'rps_id',
        'jenis_evaluasi',
        'deskripsi',
        'bobot_penilaian',
    ];


    public function rps()
    {
        return $this->belongsTo(RpsMatakuliah::class, 'rps_id');
    }
}
