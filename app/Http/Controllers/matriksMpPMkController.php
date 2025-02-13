<?php

namespace App\Http\Controllers;

use App\Models\MateriPembelajaran;
use App\Models\MatriksPMp;
use App\Models\Pengetahuan;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class matriksMpPMkController extends Controller
{
    public function index(Request $request){
        $prodiId = $request->query('prodiId');

        $mps = MateriPembelajaran::with('knowledgeDimension') // Eager load relasi knowledgeDimension
            ->whereHas('kurikulum', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId)->where('is_active', true);
            })
            ->get();

        $pengetahuans = Pengetahuan::whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId)->where('is_active', true);
        })->get();

        $mataKuliahData = MataKuliah::whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId)->where('is_active', true);
        })->get();

        $matrix = [];
        foreach ($mps as $mp) {
            $row = [];
            foreach ($pengetahuans as $pengetahuan) {
                // Cek apakah ada relasi MP dan Pengetahuan di MatriksMPp
                $relation = MatriksPMp::where('mp_id', $mp->id)
                ->where('p_id', $pengetahuan->id)
                ->first();
            
                if ($relation) {
                    $hasRelation = true;
                    $relationId = $relation->id; // Mendapatkan id dari relasi
                } else {
                    $hasRelation = false;
                    $relationId = null; // Tidak ada relasi
                }

                // Ambil daftar mata kuliah jika ada relasi
                $mataKuliahs = $hasRelation
                    ? MatriksPMp::with('mataKuliahs')
                        ->where('mp_id', $mp->id)
                        ->where('p_id', $pengetahuan->id)
                        ->get()
                        ->flatMap(function ($item) {
                            return $item->mataKuliahs; // Ambil nama mata kuliah
                        })
                    : [];

                $row[] = [
                    'enabled' => $hasRelation, // True jika relasi MP-P tersedia
                    'mata_kuliahs' => $mataKuliahs, // Daftar mata kuliah (jika ada)
                    'relationId' => $relationId
                ];
            }
            $matrix[] = $row; // Tambahkan baris ke matriks
        }

        // Format data untuk respons JSON
        $result = [
            'mps' => $mps->map(function ($mp) {
                return $mp;
            }),
            'pengetahuans' => $pengetahuans->map(function ($pengetahuan) {
                return $pengetahuan;
            }),
            'mataKuliah' => $mataKuliahData,
            'matrix' => $matrix,
        ];

        return response()->json($result);
    }

    public function update(Request $request)
    {
        $updates = $request->input('updates'); // Data updates berupa array

        foreach ($updates as $update) {
            $relationId = $update['relationMpPId']; // ID Relasi MatriksMPp
            $selectedMatkul = $update['selected_matkul']; // Daftar mata kuliah yang dipilih

            // Cek apakah relasi MatriksMPp ada
            $relation = MatriksPMp::find($relationId);

            if ($relation) {
                // Jika relasi ada, perbarui relasi dengan mata kuliah yang dipilih
                $relation->mataKuliahs()->sync($selectedMatkul);
            } else {
                return response()->json([
                    'error' => 'relasi mp p tidak tersedia n tolong buat dulu',
                    'message' => 'Data gagal diperbarui!',
                ]);
            }
        }

        return response()->json([
            'message' => 'Data berhasil diperbarui!',
        ]);
    }

}
