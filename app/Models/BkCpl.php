<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BkCpl extends Model
{
    use HasFactory;

    protected $table = 'bk_cpls';
    protected $fillable = [
        'cpl',
        'bk_id',
    ];

    /**
     * Relasi ke BenchKurikulum
     * 
     * BkCpl milik satu BenchKurikulum
     */
    public function benchKurikulum()
    {
        return $this->belongsTo(BenchKurikulum::class);
    }
}
