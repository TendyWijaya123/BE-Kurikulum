<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VmtJurusan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     */
    protected $table = 'vmt_jurusans';

    /**
     * Atribut yang dapat diisi (mass assignable).
     */
    protected $fillable = [
        'visi_jurusan',
        'visi_keilmuan_prodi',
        'kurikulum_id',
    ];

    /**
     * Relasi ke tabel Kurikulum.
     * Setiap VmtJurusan berhubungan dengan satu Kurikulum.
     */
    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class, 'kurikulum_id');
    }


    public function misiJurusans()
    {
        return $this->hasMany(MisiJurusan::class, 'vmt_jurusan_id');
    }

    /**
     * Aturan validasi untuk model.
     */
    public static function rules(): array
    {
        return [
            'visi_jurusan' => 'required|string',
            'visi_keilmuan_prodi' => 'required|string',
            'kurikulum_id' => 'required|exists:kurikulums,id|unique:vmt_jurusans,kurikulum_id',
        ];
    }
}
