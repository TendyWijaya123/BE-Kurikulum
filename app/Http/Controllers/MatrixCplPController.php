<?php

namespace App\Http\Controllers;

use App\Models\Cpl;
use App\Models\Pengetahuan;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class MatrixCplPController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if ($request->has('prodiId')) {
                $prodi = Prodi::find($request->prodiId);
                if (!$prodi) {
                    return response()->json(['error' => 'Prodi tidak ditemukan'], 404);
                }
                $activeKurikulum = $prodi->activeKurikulum();
            } else {
                $activeKurikulum = $user->activeKurikulum();
            }

            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan'], 404);
            }

            $cpls = Cpl::with('pengetahuans:id')
                ->where('kurikulum_id', $activeKurikulum->id)
                ->orderByRaw("CAST(SUBSTRING(kode, 5) AS UNSIGNED) ASC")
                ->get(['id', 'kode', 'keterangan']);

            $pengetahuans = Pengetahuan::where('kurikulum_id', $activeKurikulum->id)
                ->get(['id', 'kode_pengetahuan as kode', 'deskripsi']);

            $matrix = $cpls->map(function ($cpl) use ($pengetahuans) {
                return [
                    'cpl' => $cpl,
                    'ps' => $pengetahuans->map(function ($pengetahuan) use ($cpl) {
                        return [
                            'p_id' => $pengetahuan->id,
                            'exists' => $cpl->pengetahuans->contains($pengetahuan->id),
                        ];
                    }),
                ];
            });

            return response()->json([
                'cpls' => $cpls,
                'ps' => $pengetahuans,
                'matrix' => $matrix,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in MatrixCplPController@index:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        // Get active curriculum
        $activeKurikulum = $user->activeKurikulum();
        if (!$activeKurikulum) {
            return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
        }

        $validated = $request->validate([
            'matrix' => 'required|array',
            'matrix.*.cpl_id' => 'required|exists:cpls,id',
            'matrix.*.p_ids' => 'nullable|array',
            'matrix.*.p_ids.*' => 'exists:pengetahuans,id',
        ]);

        DB::beginTransaction();

        try {
            foreach ($validated['matrix'] as $entry) {
                $cpl = Cpl::where('kurikulum_id', $activeKurikulum->id)
                    ->findOrFail($entry['cpl_id']);
                $cpl->pengetahuans()->sync($entry['p_ids']);
            }

            DB::commit();

            return response()->json([
                'message' => 'Matrix updated successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => 'Failed to update matrix.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
