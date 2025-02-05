<?php

namespace App\Http\Controllers;

use App\Exports\PpmTemplateExport;
use App\Imports\PpmImport;
use App\Models\Ppm;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Tymon\JWTAuth\Facades\JWTAuth;

class PpmController extends Controller
{
    /**
     * Get all PPMs for the active kurikulum of the authenticated user.
     */
    public function index()
    {
        try {
            // Authenticate user using JWT
            $user = JWTAuth::parseToken()->authenticate();

            // Retrieve the active kurikulum for the user
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
            }

            // Get PPMs associated with the active kurikulum
            $ppms = Ppm::where('kurikulum_id', $activeKurikulum->id)->get(['id', 'kode', 'deskripsi']);

            // Return success response
            return response()->json([
                'success' => true,
                'data' => $ppms,
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
     * Upsert PPM data for the active kurikulum of the authenticated user.
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
                'ppms' => 'required|array',
                'ppms.*.id' => 'nullable|exists:ppms,id', // Validate if id exists when provided
                'ppms.*.deskripsi' => 'required|string|max:255',
            ]);

            // Loop to process each PPM entry for upsert
            foreach ($validated['ppms'] as $ppm) {
                // Prepare the data for upsert
                $data = [
                    'deskripsi' => $ppm['deskripsi'],
                    'kurikulum_id' => $activeKurikulum->id, // Use the active kurikulum's ID
                ];

                // If id is provided, update the existing record
                if (isset($ppm['id'])) {
                    $existingPpm = Ppm::find($ppm['id']);
                    if ($existingPpm) {
                        $existingPpm->update($data); // Update the existing PPM
                    }
                } else {
                    // Create new record if no id is provided
                    Ppm::create($data);
                }
            }

            return response()->json(['message' => 'Data PPM berhasil disimpan atau diperbarui'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json(['error' => 'Validasi gagal', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Handle generic exceptions
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
        }
    }


    /**
     * Delete PPM by ID for the active kurikulum of the authenticated user.
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
