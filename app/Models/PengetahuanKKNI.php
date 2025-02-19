<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengetahuanKKNI extends Model
{
    use HasFactory;

    protected $table = 'pengetahuan_kkni';

    protected $fillable = [
        'level',
        'Pengatahuan_kkni',
        'jenjang',
    ];

    public function cplKKNI(){
        return $this->belongsTo(CplKkni::class);
    }
}
