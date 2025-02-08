<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeranIndustri extends Model
{
    use HasFactory;

    /**
     * Nama tabel terkait model ini.
     *
     * @var string
     */
    protected $table = 'peran_industris';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'jabatan',
        'deskripsi',
        'kurikulum_id',
    ];

    /**
     * Relasi ke model Kurikulum.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }
}
