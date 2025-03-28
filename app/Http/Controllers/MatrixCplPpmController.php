<?php

namespace App\Http\Controllers;

use App\Models\Cpl;
use App\Models\Ppm;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class MatrixCplPpmController extends Controller
{
    /**
     * Display the CPL-PPM matrix.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
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
            return response()->json(['status' => 'Tidak ada kurikulum  yang aktif pada prodi ini'], 404);
        }

        $cpls = Cpl::with('ppms:id')
            ->where('kurikulum_id', $activeKurikulum->id)
            ->get(['id', 'kode', 'keterangan']);
        $ppms = Ppm::where('kurikulum_id', $activeKurikulum->id)
            ->get(['id', 'kode', 'deskripsi']);

        $matrix = $cpls->map(function ($cpl) use ($ppms) {
            return [
                'cpl' => $cpl,
                'ppms' => $ppms->map(function ($ppm) use ($cpl) {
                    return [
                        'ppm_id' => $ppm->id,
                        'exists' => $cpl->ppms->contains($ppm->id),
                    ];
                }),
            ];
        });

        return response()->json([
            'cpls' => $cpls,
            'ppms' => $ppms,
            'matrix' => $matrix,
        ]);
    }

    /**
     * Update the CPL-PPM matrix in bulk.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        // Ambil kurikulum aktif
        $activeKurikulum = $user->activeKurikulum();
        if (!$activeKurikulum) {
            return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
        }

        $validated = $request->validate([
            'matrix' => 'required|array',
            'matrix.*.cpl_id' => 'required|exists:cpls,id',
            'matrix.*.ppm_ids' => 'nullable|array',
            'matrix.*.ppm_ids.*' => 'exists:ppms,id',
        ]);

        DB::beginTransaction();

        try {
            foreach ($validated['matrix'] as $entry) {
                $cpl = Cpl::where('kurikulum_id', $activeKurikulum->id)
                    ->findOrFail($entry['cpl_id']);
                $cpl->ppms()->sync($entry['ppm_ids']);
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
