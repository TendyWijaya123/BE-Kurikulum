<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeDimension extends Model
{
    protected $table = 'knowledge_dimensions';

    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    public function materiPembelajaran()
    {
        return $this->belongsToMany(MateriPembelajaran::class,'knowledge_mp','code_knowledge_dimension', 'mp_id');
    }
}
