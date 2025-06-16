<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JejaringMKDiagram extends Model
{
    use HasFactory;

    protected $table = 'jejaring_mk_diagram';

    protected $fillable = [
        'kurikulum_id',
        'gambar_url',
    ];

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }
}
