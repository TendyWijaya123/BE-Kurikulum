<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpteksTeknologi extends Model
{
    use HasFactory;

    protected $table = 'ipteks_teknologi';

    protected $fillable = [
        'teknologi',
        'kurikulum_id',
    ];

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }
}
