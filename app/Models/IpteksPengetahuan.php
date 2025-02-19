<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpteksPengetahuan extends Model
{
    use HasFactory;
    protected $table = 'ilmu_pengetahuans';
    protected $fillable = [
        'deskripsi',
        'link_sumber',
        'kurikulum_id',
    ];

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }
}
