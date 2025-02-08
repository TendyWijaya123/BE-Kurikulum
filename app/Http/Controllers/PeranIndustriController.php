<?php

namespace App\Http\Controllers;

use App\Exports\PeranIndustriTemplateExport;
use App\Imports\PeranIndustriImport;
use App\Models\PeranIndustri;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Tymon\JWTAuth\Facades\JWTAuth;

class PeranIndustriController extends Controller
{
    /**
     * Get all Peran Industri for the active kurikulum of the authenticated user.
     */
    public function index()
    {
        try {
            // Authenticate user using JWT
            $user = JWTAuth::parseToken()->authenticate();

            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
            }

            // Get Peran Industri associated with the active kurikulum
            $peranIndustri = PeranIndustri::where('kurikulum_id', $activeKurikulum->id)->get(['id', 'jabatan', 'deskripsi']);

            // Return success response
            return response()->json([
                'success' => true,
                'data' => $peranIndustri,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'error' => 'Validasi gagal',
                'messages' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Handle generic exceptions
            return response()->json([
                'error' => 'Terjadi kesalahan',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upsert Peran Industri data for the active kurikulum of the authenticated user.
     */
    public function upsert(Request $request)
    {
        try {
            // Authenticate user using JWT
            $user = JWTAuth::parseToken()->authenticate();

            // Retrieve the active kurikulum for the user
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
            }

            // Validate the incoming request data
            $validated = $request->validate([
                'peran_industri' => 'required|array',
                'peran_industri.*.id' => 'nullable|exists:peran_industris,id', // Validate if id exists when provided
                'peran_industri.*.jabatan' => 'required|string|max:255',
                'peran_industri.*.deskripsi' => 'required|string',
            ]);

            foreach ($validated['peran_industri'] as $peran) {
                $data = [
                    'jabatan' => $peran['jabatan'],
                    'deskripsi' => $peran['deskripsi'],
                    'kurikulum_id' => $activeKurikulum->id, // Use the active kurikulum's ID
                ];

                if (isset($peran['id'])) {
                    $existingPeran = PeranIndustri::find($peran['id']);
                    if ($existingPeran) {
                        $existingPeran->update($data); // Update the existing Peran Industri
                    }
                } else {
                    PeranIndustri::create($data);
                }
            }

            return response()->json(['message' => 'Data Peran Industri berhasil disimpan atau diperbarui'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validasi gagal', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete Peran Industri by ID for the active kurikulum of the authenticated user.
     */
    public function delete(Request $request, $id)
    {
        try {
            // Authenticate user using JWT
            $user = JWTAuth::parseToken()->authenticate();

            // Retrieve the active kurikulum for the user
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
            }

            // Find the Peran Industri by ID
            $peranIndustri = PeranIndustri::where('kurikulum_id', $activeKurikulum->id)
                ->where('id', $id)
                ->first();

            if (!$peranIndustri) {
                return response()->json(['error' => 'Peran Industri tidak ditemukan'], 404);
            }

            // Delete the Peran Industri
            $peranIndustri->delete();

            return response()->json(['message' => 'Peran Industri berhasil dihapus'], 200);
        } catch (\Exception $e) {
            // Handle generic exceptions
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Import Peran Industri data from an Excel file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            Excel::import(new PeranIndustriImport, $request->file('file'));
            return response()->json(['message' => 'Data berhasil diimport.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Download the Peran Industri template Excel file.
     */
    public function downloadTemplate()
    {
        $fileName = 'peran_industri_template.xlsx';

        return Excel::download(new PeranIndustriTemplateExport, $fileName);
    }
}
