<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MateriPembelajaran extends Model
{
    use HasFactory;

    protected $table = 'materi_pembelajaran';
    protected $fillable = [
        'code',
        'description',
        'kurikulum_id',
    ];

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }
}
