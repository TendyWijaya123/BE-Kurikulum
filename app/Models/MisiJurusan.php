<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MisiJurusan extends Model
{
    use HasFactory;

    // Tentukan tabel yang digunakan (jika nama tabel tidak mengikuti konvensi Laravel)
    protected $table = 'misi_jurusans';

    // Tentukan kolom yang dapat diisi massal
    protected $fillable = [
        'misi_jurusan',
        'vmt_jurusan_id',
    ];

    /**
     * Definisikan relasi dengan model VmtJurusan
     */
    public function vmtJurusan()
    {
        return $this->belongsTo(VmtJurusan::class, 'vmt_jurusan_id');
    }
}
