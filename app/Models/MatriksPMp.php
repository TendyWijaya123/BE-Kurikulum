<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MatriksPMp extends Model
{
    use HasFactory;

    protected $table = 'p_mp';
    protected $fillable = [
        'p_id',
        'mp_id',
    ];

    public function mataKuliahs(){
        return $this->belongsToMany(MataKuliah::class, 'mp_p_mk','mp_p_id', 'mk_id');
    }
}
