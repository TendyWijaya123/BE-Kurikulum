<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeranIndustriDeskripsi extends Model
{
    use HasFactory;

    /**
     * Nama tabel terkait model ini.
     *
     * @var string
     */
    protected $table = 'peran_industri_deskripsis';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'peran_industri_id',
        'deskripsi_point',
    ];

    /**
     * Relasi ke model PeranIndustri.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function peranIndustri()
    {
        return $this->belongsTo(PeranIndustri::class);
    }
}
