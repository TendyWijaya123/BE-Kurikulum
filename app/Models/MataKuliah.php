<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'tujuan',
        'kurikulum_id'
    ];

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class, 'kurikulum_id');
    }

    public function formulasiCpas()
    {
        return $this->belongsToMany(
            FormulasiCpa::class,
            'mk_formulasi',
            'mata_kuliah_id',
            'formulasi_cpa_id'
        )->withTimestamps();
    }

    public  function kemampuanAkhirs()
    {
        return  $this->hasMany(KemampuanAkhir::class);
    }

    public function matriksPMp(){
        return $this->belongsToMany(MatriksPMp::class, 'mp_p_mk', 'mp_p_id', 'mk_id');
    }

    public function cpls()
    {
        return $this->belongsToMany(Cpl::class, 'mk_cpl', 'mk_id', 'cpl_id')
            ->withPivot('kategori')
            ->withTimestamps();
    }

    public function dosens(){
        return $this->belongsToMany(Dosen::class, 'dosen_has_matkul',  'mk_id', 'dosen_id');
    }
}
