<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CplKkni extends Model
{
    use HasFactory;

    protected $table = 'cpl_kkni';
    protected $fillable = [
        'code',
        'description',
        'kurikulum_id',
    ];

    /**
     * Relasi ke BenchKurikulum
     * 
     * BkCpl milik satu BenchKurikulum
     */
    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }
}
