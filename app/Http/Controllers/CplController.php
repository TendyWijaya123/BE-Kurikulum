<?php

namespace App\Http\Controllers;

use App\Exports\CplTemplateExport;
use App\Http\Requests\UpsertCPLRequest;
use App\Imports\CplImport;
use App\Models\Cpl;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Tymon\JWTAuth\Facades\JWTAuth;

class CplController extends Controller
{
    /**
     * Get all CPLs for the active kurikulum of the authenticated user.
     */
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
                return response()->json(['status' => 'Tidak ada kurikulum  yang aktif pada prodi ini'], 404);
            }

            $cpl = Cpl::where('kurikulum_id', $activeKurikulum->id)
                ->orderByRaw("CAST(SUBSTRING(kode, 5) AS UNSIGNED) ASC")
                ->get(['id', 'kode', 'keterangan']);


            return response()->json([
                'success' => true,
                'data' => $cpl,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'error' => 'Validasi gagal',
                'messages' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {

            return response()->json([
                'error' => 'Terjadi kesalahan',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function upsert(UpsertCPLRequest $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $activeKurikulum = $user->activeKurikulum();

        if (!$activeKurikulum) {
            return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
        }

        try {
            $validated = $request->validated();

            foreach ($validated['cpls'] as $cpl) {
                $data = [
                    'keterangan' => $cpl['keterangan'],
                    'kurikulum_id' => $activeKurikulum->id,
                ];

                if (!empty($cpl['id'])) {
                    $existingCpl = Cpl::find($cpl['id']);
                    if ($existingCpl) {
                        $existingCpl->update($data);
                    }
                } else {
                    Cpl::create($data);
                }
            }

            return response()->json(['message' => 'Upsert berhasil'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }




    public function delete($id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $activeKurikulum = $user->activeKurikulum();

        if (!$activeKurikulum) {
            return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
        }

        try {
            $cpl = Cpl::where('kurikulum_id', $activeKurikulum->id)
                ->where('id', $id)
                ->first();

            if (!$cpl) {
                return response()->json(['error' => 'CPL tidak ditemukan'], 404);
            }

            $cpl->delete();

            return response()->json(['message' => 'Data CPL berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyCpls(Request $request)
    {
        try {
            $validated = $request->validate([
                'cplss_id' => 'array',
                'cpls_id.*' => 'integer|exists:cpls,id',
            ]);

            $cplIds = $validated['cpls_id'];

            $deleted = Cpl::whereIn('id', $cplIds)->get();

            foreach ($deleted as $delete) {
                $delete->delete();
            }

            if ($deleted === 0) {
                return response()->json(['error' => 'Tidak ada CPL yang dihapus'], 404);
            }

            return response()->json(['message' => 'CPL berhasil dihapus'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validasi gagal',
                'messages' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        try {
            Excel::import(new CplImport, $request->file('file'));
            return response()->json(['message' => 'Data berhasil diimport.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function downloadTemplate()
    {
        $fileName = 'cpl_template.xlsx';

        return Excel::download(new CplTemplateExport, $fileName);
    }
}
