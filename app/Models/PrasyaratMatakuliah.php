<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrasyaratMatakuliah extends Model
{
    use HasFactory;

    protected $table = 'prasyarat_matakuliah';

    protected $fillable = [
        'from_id',
        'to_id',
    ];

    public function mataKuliahFrom()
    {
        return $this->belongsTo(MataKuliah::class, 'from_id');
    }

    public function mataKuliahTo()
    {
        return $this->belongsTo(MataKuliah::class, 'to_id');
    }
}
