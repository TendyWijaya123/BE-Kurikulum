<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KemampuanKerjaKKNI extends Model
{
    use HasFactory;

    protected $table = 'kemampuan_kerja_kkni';

    protected $fillable = [
        'level',
        'kemampuan_kerja_kkni',
        'jenjang',
    ];

    public function cplKKNI(){
        return $this->belongsTo(CplKkni::class);
    }
}
