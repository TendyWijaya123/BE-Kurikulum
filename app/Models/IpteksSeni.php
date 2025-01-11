<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpteksSeni extends Model
{
    use HasFactory;

    protected $table = 'ipteks_seni';

    protected $fillable = [
        'seni',
        'kurikulum_id',
    ];

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }
}
