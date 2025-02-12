<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BenchKurikulum extends Model
{
    use HasFactory;

    protected $table = 'bench_kurikulums'; // Nama tabel
    protected $fillable = [
        'program_studi',
        'kategori',
        'cpl',
        'ppm',
        'kurikulum_id',
    ];

    /**
     * Relasi ke Kurikulum
     * 
     * BenchKurikulum milik satu Kurikulum
     */
    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }
}
