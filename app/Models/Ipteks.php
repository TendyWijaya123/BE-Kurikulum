<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ipteks extends Model
{
    use HasFactory;

    protected $table = 'ipteks';

    protected $fillable = [
        'kategori',
        'deskripsi',
        'link_sumber',
        'kurikulum_id',
    ];

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }
}