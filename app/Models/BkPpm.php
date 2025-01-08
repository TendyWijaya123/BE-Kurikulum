<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BkPpm extends Model
{
    use HasFactory;

    protected $table = 'bk_ppms';
    protected $fillable = [
        'ppm',
        'bk_id',
    ];

    /**
     * Relasi ke BenchKurikulum
     * 
     * BkPpm milik satu BenchKurikulum
     */
    public function benchKurikulum()
    {
        return $this->belongsTo(BenchKurikulum::class, 'bk_id');
    }
}
