<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliahs';
    protected $fillable = [
        'kode',
        'nama',
        'tujuan',
        'semester',
        'teori_bt',
        'teori_pt',
        'teori_m',
        'praktek_bt',
        'praktek_pt',
        'praktek_m',
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


    public function prasyarat()
    {
        return $this->belongsToMany(
            MataKuliah::class,
            'prasyarat_mata_kuliah',
            'mata_kuliah_id',
            'prasyarat_id'
        );
    }

    public function digunakanSebagaiPrasyarat()
    {
        return $this->belongsToMany(
            MataKuliah::class,
            'prasyarat_mata_kuliah',
            'prasyarat_id',
            'mata_kuliah_id'
        );
    }

    public function prasyaratGraphById()
    {
        $nodes = [];
        $edges = [];

        $addNode = function ($id, $nama) use (&$nodes) {
            // Cek apakah ID sudah ada dalam nodes
            if (!isset($nodes[$id])) {
                $nodes[$id] = $nama;
            }
        };

        $addEdge = function ($toId, $fromId) use (&$edges) {
            $edges[] = ['from' => $fromId, 'to' => $toId];
        };

        $addNode($this->id, $this->nama);

        $prasyarat = $this->prasyarat()->get();

        foreach ($prasyarat as $item) {
            $addNode($item->id, $item->nama);
            $addEdge($this->id, $item->id);

            $subGraph = $item->prasyaratGraphById();

            foreach ($subGraph['nodes'] as $id => $nama) {
                $addNode($id, $nama);
            }
            foreach ($subGraph['edges'] as $edge) {
                $edges[] = $edge;
            }
        }

        return [
            'nodes' => $nodes,
            'edges' => $edges,
        ];
    }
}
