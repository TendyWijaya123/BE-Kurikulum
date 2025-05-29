<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DetailMataKuliahRPS extends Model
{
    use HasFactory;

    protected $table = 'detail_mk_rps';

    protected $fillable = [
        'deskripsi_singkat',
        'materi_pembelajaran',
        'mata_kuliah_id',
    ];

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

}
