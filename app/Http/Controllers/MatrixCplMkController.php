<?php

namespace App\Http\Controllers;

use App\Models\Cpl;
use App\Models\MataKuliah;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class MatrixCplMkController extends Controller
{
    /**
     * Display the CPL-MK matrix with categories.
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
            return response()->json(['error' => 'Kurikulum aktif tidak ditemukan'], 404);
        }

        $cpls = Cpl::with(['mataKuliahs' => function ($query) {
            $query->select('mata_kuliahs.id', 'kode', 'nama')
                ->withPivot('kategori');
        }])
            ->where('kurikulum_id', $activeKurikulum->id)
            ->orderByRaw("CAST(SUBSTRING(kode, 5) AS UNSIGNED) ASC")
            ->get(['id', 'kode', 'keterangan']);

        $mataKuliahs = MataKuliah::where('kurikulum_id', $activeKurikulum->id)
            ->get(['id', 'kode', 'nama']);

        $matrix = $cpls->map(function ($cpl) use ($mataKuliahs) {
            return [
                'cpl' => $cpl,
                'mataKuliahs' => $mataKuliahs->map(function ($mataKuliah) use ($cpl) {
                    $pivotData = $cpl->mataKuliahs->firstWhere('id', $mataKuliah->id);

                    return [
                        'mk_id' => $mataKuliah->id,
                        'exists' => (bool) $pivotData,
                        'kategori' => $pivotData ? $pivotData->pivot->kategori : null,
                    ];
                }),
            ];
        });

        return response()->json([
            'cpls' => $cpls,
            'mataKuliahs' => $mataKuliahs,
            'matrix' => $matrix,
        ]);
    }


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
            'matrix.*.mk_ids' => 'nullable|array',
            'matrix.*.mk_ids.*.mk_id' => 'required|exists:mata_kuliahs,id',
            'matrix.*.mk_ids.*.kategori' => 'required|in:I,R,M,A',
        ]);

        DB::beginTransaction();

        try {
            foreach ($validated['matrix'] as $entry) {
                $cpl = Cpl::where('kurikulum_id', $activeKurikulum->id)
                    ->findOrFail($entry['cpl_id']);

                // Data untuk sinkronisasi
                $syncData = collect($entry['mk_ids'])->mapWithKeys(function ($mk) {
                    return [$mk['mk_id'] => ['kategori' => $mk['kategori']]];
                })->toArray();

                $cpl->mataKuliahs()->sync($syncData);
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
