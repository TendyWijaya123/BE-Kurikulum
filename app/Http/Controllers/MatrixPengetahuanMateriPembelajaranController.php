<?php

namespace App\Http\Controllers;

use App\Models\Pengetahuan as ModelPengetahuan;
use App\Models\MateriPembelajaran as ModelMp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatrixPengetahuanMateriPembelajaranController extends Controller
{
    public function index(Request $request) {
        $prodiId = $request->query('prodiId');

        $mps = ModelMp::whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId)->where('is_active', true);
        })->get();

        $pengetahuans = ModelPengetahuan::whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId)->where('is_active', true);
        })->get();

        $matrix = [];
        foreach ($mps as $mp) {
            $row = [];
            foreach ($pengetahuans as $p) {
                // Cek apakah ada relasi antara Pengetahuan dan materi pembelajaran di tabel pivot
                $hasRelation = $p->mp->contains($mp);
                $row[] = $hasRelation; // true jika ada, false jika tidak
            }
            $matrix[] = $row;
        }

        return response()->json([
            'mps' => $mps,
            'pengetahuans' => $pengetahuans,
            'matrix' => $matrix
        ]);
    }

    public function update(Request $request){
        try{

            DB::beginTransaction();

            $validated = $request->validate([
                'prodiId' => 'required|exists:prodis,id',
                'updates' => 'required|array',
                'updates.*.p_id' => 'required|exists:pengetahuans,id',
                'updates.*.mp_id' => 'required|exists:materi_pembelajaran,id',
                'updates.*.has_relation' => 'required|boolean',
            ]);
        
            $prodiId = $validated['prodiId'];
            $updates = $validated['updates'];
        
            foreach ($updates as $update) {
                $pId = $update['p_id'];
                $mpId = $update['mp_id'];
                $hasRelation = $update['has_relation'];
        
                // Ambil CPL berdasarkan ID dan pastikan prodi sesuai
                $pengetahuan = ModelPengetahuan::where('id', $pId)
                    ->whereHas('kurikulum', function ($query) use ($prodiId) {
                        $query->where('prodi_id', $prodiId);
                    })
                    ->first();
        
                if (!$pengetahuan) {
                    return response()->json(['error' => 'Pengetahuan not found or not associated with the given prodi'], 404);
                }
        
                // Tambahkan atau hapus hubungan di tabel pivot
                if ($hasRelation) {
                    // Tambahkan relasi jika belum ada
                    $pengetahuan->mp()->syncWithoutDetaching([$mpId]);
                } else {
                    // Hapus relasi jika ada
                    $pengetahuan->mp()->detach($mpId);
                }
            }

            DB::commit();
        
            return response()->json(['message' => 'Matrix updated successfully']);
        } catch (\Exception $e){
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to update matrix.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
