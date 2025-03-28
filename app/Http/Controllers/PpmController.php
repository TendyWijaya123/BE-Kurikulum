<?php

namespace App\Http\Controllers;

use App\Exports\PpmTemplateExport;
use App\Http\Requests\UpsertPPMRequest;
use App\Imports\PpmImport;
use App\Models\Ppm;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Tymon\JWTAuth\Facades\JWTAuth;

class PpmController extends Controller
{
    /**
     * Get all PPMs for the active kurikulum of the authenticated user.
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

            $ppms = Ppm::where('kurikulum_id', $activeKurikulum->id)->orderByRaw("CAST(SUBSTRING(kode, 5) AS UNSIGNED) ASC")
                ->get(['id', 'kode', 'deskripsi']);

            return response()->json([
                'success' => true,
                'data' => $ppms,
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

    /**
     * Upsert PPM data for the active kurikulum of the authenticated user.
     */
    public function upsert(UpsertPPMRequest $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
            }

            $validated = $request->validated();

            foreach ($validated['ppms'] as $ppm) {
                $data = [
                    'deskripsi' => $ppm['deskripsi'],
                    'kurikulum_id' => $activeKurikulum->id,
                ];

                if (!empty($ppm['id'])) {
                    $existingPpm = Ppm::find($ppm['id']);
                    if ($existingPpm) {
                        $existingPpm->update($data);
                    }
                } else {
                    Ppm::create($data);
                }
            }
            return response()->json(['message' => 'Data PPM berhasil disimpan atau diperbarui'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
        }
    }



    /**
     * Delete PPM by ID for the active kurikulum of the authenticated user.
     */
    public function delete($id)
    {
        try {
            // Authenticate user using JWT
            $user = JWTAuth::parseToken()->authenticate();

            // Retrieve the active kurikulum for the user
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
            }

            // Find the PPM by ID
            $ppm = Ppm::where('kurikulum_id', $activeKurikulum->id)
                ->where('id', $id)
                ->first();

            if (!$ppm) {
                return response()->json(['error' => 'PPM tidak ditemukan'], 404);
            }

            // Delete the PPM
            $ppm->delete();

            return response()->json(['message' => 'PPM berhasil dihapus'], 200);
        } catch (\Exception $e) {
            // Handle generic exceptions
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyPpms(Request $request)
    {
        try {
            $validated = $request->validate([
                'ppms_id' => 'array',
                'ppms_id.*' => 'integer|exists:ppms,id', // Pastikan setiap ID adalah integer dan ada di database
            ]);

            $ppmIds = $validated['ppms_id'];

            $deleted = Ppm::whereIn('id', $ppmIds)->get();


            foreach ($deleted as $delete) {
                $delete->delete();
            }

            if ($deleted === 0) {
                return response()->json(['error' => 'Tidak ada PPM yang dihapus'], 404);
            }

            return response()->json(['message' => 'PPM berhasil dihapus'], 200);
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
            Excel::import(new PpmImport, $request->file('file'));
            return response()->json(['message' => 'Data berhasil diimport.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function downloadTemplate()
    {
        $fileName = 'ppm_template.xlsx';

        return Excel::download(new PpmTemplateExport, $fileName);
    }
}
