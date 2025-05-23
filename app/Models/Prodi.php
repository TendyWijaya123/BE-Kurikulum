<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'jenjang', 'kode', 'jurusan_id', 'is_active'];

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

    public function dosen()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_has_prodi');
    }

    public function kaprodi()
    {
        return $this->belongsTo(Dosen::class, 'dosens');
    }

    public function  activeKurikulum()
    {
        return $this->kurikulums()->where('is_active', true)->first();
    }

    public function getStatusProgressAttribute()
    {
        $activeKurikulum = $this->activeKurikulum();

        if (!$activeKurikulum) {
            return [];
        }

        // Ambil semua atribut yang diawali dengan 'is_'
        return collect($activeKurikulum->getAttributes())
            ->filter(function ($value, $key) {
                return str_starts_with($key, 'is_');
            })
            ->toArray();
    }
}
