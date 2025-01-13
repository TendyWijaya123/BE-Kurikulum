<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iea extends Model
{
    use HasFactory;

    protected $table = 'iea';
    protected $fillable = [
        'jenjang',
        'code',
        'description'
    ];

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }
}
