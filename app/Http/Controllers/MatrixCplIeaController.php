<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cpl as ModelCpl;
use App\Models\Iea as ModelIea;
use App\Models\Prodi;
use Illuminate\Support\Facades\DB;

class MatrixCplIeaController extends Controller
{
    public function index(Request $request)
    {
        $prodiId = $request->query('prodiId');

        // Mendapatkan jenjang berdasarkan prodiId
        $prodi = Prodi::find($prodiId);
        if (!$prodi) {
            return response()->json(['error' => 'Prodi not found'], 404);
        }

        // Kondisi untuk memfilter data berdasarkan jenjang
        $jenjangFilter = $prodi->jenjang === 'D3' ? 'Diploma III' : 'Sarjana Terapan';

        // Ambil semua data CPL dan IEA
        $ieas = ModelIea::where('jenjang', $jenjangFilter)->get();
        $cpls = ModelCpl::
        whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId)->where('is_active', true);
        })
        ->get();

        // Bangun matriks CPL-IEA
        $matrix = [];
        foreach ($cpls as $cpl) {
            $row = [];
            foreach ($ieas as $iea) {
                // Cek apakah ada relasi antara CPL dan IEA di tabel pivot
                $hasRelation = $cpl->iea->contains($iea);
                $row[] = $hasRelation; // true jika ada, false jika tidak
            }
            $matrix[] = $row;
        }

        // Return data matriks, CPL, dan IEA ke view atau response JSON
        return response()->json([
            'cpls' => $cpls,
            'ieas' => $ieas,
            'matrix' => $matrix,
        ]);
    }

    public function update(Request $request){
        try{

            DB::beginTransaction();

            $validated = $request->validate([
                'prodiId' => 'required|exists:prodis,id',
                'updates' => 'required|array',
                'updates.*.cpl_id' => 'required|exists:cpls,id',
                'updates.*.iea_id' => 'required|exists:iea,id',
                'updates.*.has_relation' => 'required|boolean',
            ]);
        
            $prodiId = $validated['prodiId'];
            $updates = $validated['updates'];
        
            foreach ($updates as $update) {
                $cplId = $update['cpl_id'];
                $ieaId = $update['iea_id'];
                $hasRelation = $update['has_relation'];
        
                // Ambil CPL berdasarkan ID dan pastikan prodi sesuai
                $cpl = ModelCpl::where('id', $cplId)
                    ->whereHas('kurikulum', function ($query) use ($prodiId) {
                        $query->where('prodi_id', $prodiId);
                    })
                    ->first();
        
                if (!$cpl) {
                    return response()->json(['error' => 'CPL not found or not associated with the given prodi'], 404);
                }
        
                // Tambahkan atau hapus hubungan di tabel pivot
                if ($hasRelation) {
                    // Tambahkan relasi jika belum ada
                    $cpl->iea()->syncWithoutDetaching([$ieaId]);
                } else {
                    // Hapus relasi jika ada
                    $cpl->iea()->detach($ieaId);
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
