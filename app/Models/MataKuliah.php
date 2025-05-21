<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliahs';
    protected $fillable = [
        'kode',
        'nama',
        'nama_inggris',
        'kategori',
        'deskripsi_singkat',
        'deskripsi_singkat_inggris',
        'materi_pembelajaran_inggris',
        'tujuan',
        'semester',
        'teori_bt',
        'teori_pt',
        'teori_m',
        'total_teori',
        'praktek_bt',
        'praktek_pt',
        'praktek_m',
        'total_praktek',
        'sks',
        'total_beban_belajar',
        'kurikulum_id'
    ];


    public static function boot()
    {
        parent::boot();


        static::created(function ($model) {
            self::updateTotalBebanBelajar($model);
            $model->generateRpsMinggu();
        });

        static::updated(function ($model) {
            self::updateTotalBebanBelajar($model);
        });
    }

    /**
     * Generate RPS (Rencana Pembelajaran Semester) dengan 14 minggu
     */
    public function generateRpsMinggu()
    {
        $rpsData = [];
        for ($i = 1; $i <= 14; $i++) {
            $rpsData[] = ['minggu' => $i];
        }
        $this->rpss()->createMany($rpsData);
    }

    /**
     * Update total teori, total praktek, dan total beban belajar
     */
    private static function updateTotalBebanBelajar($model)
    {
        $model->total_teori = round((($model->teori_bt ?? 0) + ($model->teori_pt ?? 0) + ($model->teori_m ?? 0)) / 170);
        $model->total_praktek = round((($model->praktek_bt ?? 0) + ($model->praktek_pt ?? 0) + ($model->praktek_m ?? 0)) / 170);
        $model->saveQuietly();
    }



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

    public function matriksPMp()
    {
        return $this->belongsToMany(MatriksPMp::class, 'mp_p_mk', 'mk_id', 'mp_p_id');
    }

    public function materiPembelajarans()
    {
        return $this->belongsToMany(
            MateriPembelajaran::class,
            'p_mp',
            'mp_id',
            'p_id'
        )->whereIn('mp_id', $this->matriksPMp()->pluck('mp_p_id'));
    }

    public function prasyaratTo()
    {
        return $this->belongsToMany(MataKuliah::class, "prasyarat_matakuliah", "from_id", "to_id");
    }

    public function prasyaratFrom()
    {
        return $this->belongsToMany(MataKuliah::class, "prasyarat_matakuliah", "to_id", "from_id");
    }


    public function cpls()
    {
        return $this->belongsToMany(Cpl::class, 'mk_cpl', 'mk_id', 'cpl_id')
            ->withPivot('kategori')
            ->withTimestamps();
    }

    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_has_matkul',  'mk_id', 'dosen_id');
    }

    public function tujuanBelajars()
    {
        return $this->hasMany(TujuanBelajar::class, 'mata_kuliah_id');
    }

    public function bukuReferensis()
    {
        return $this->belongsToMany(BukuReferensi::class, 'mata_kuliah_has_buku_referensi');
    }

    public function rpss()
    {
        return $this->hasMany(RpsMatakuliah::class, 'mata_kuliah_id');
    }
}
