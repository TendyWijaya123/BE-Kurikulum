<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VmtPolban extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vmt_polbans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'visi_polban',
        'kurikulum_id',
    ];

    /**
     * Get the Kurikulum associated with the VmtPolban.
     */
    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }

    public function tujuanPolbans()
    {
        return $this->hasMany(TujuanPolban::class);
    }

    public function misiPolbans()
    {
        return $this->hasMany(MisiPolban::class);
    }
}
