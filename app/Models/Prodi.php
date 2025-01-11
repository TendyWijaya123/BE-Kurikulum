<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi
    protected $fillable = ['name', 'jenjang', 'kode', 'jurusan_id'];

    /**
     * Relasi Many to One dengan Jurusan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function kurikulums()
    {
        return $this->hasMany(Kurikulum::class);
    }


}
