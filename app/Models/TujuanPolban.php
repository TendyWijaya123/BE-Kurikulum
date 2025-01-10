<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TujuanPolban extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tujuan_polbans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tujuan_polban',
        'vmt_polban_id',
    ];

    /**
     * Get the VmtPolban associated with the TujuanPolban.
     */
    public function vmtPolban()
    {
        return $this->belongsTo(VmtPolban::class);
    }
}
