<?php

namespace App\Http\Controllers;

use App\Exports\CplTemplateExport;
use App\Imports\CplImport;
use App\Models\Cpl;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Tymon\JWTAuth\Facades\JWTAuth;

class CplController extends Controller
{
    /**
     * Get all CPLs for the active kurikulum of the authenticated user.
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

            // Get CPLs associated with the active kurikulum
            $cpl = Cpl::where('kurikulum_id', $activeKurikulum->id)->get(['id', 'kode', 'keterangan']);

            // Return success response
            return response()->json([
                'success' => true,
                'data' => $cpl,
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

    public function upsert(Request $request)
    {
        // Ambil user yang sedang terautentikasi menggunakan JWT
        $user = JWTAuth::parseToken()->authenticate();

        // Ambil kurikulum aktif yang terkait dengan user
        $activeKurikulum = $user->activeKurikulum();

        if (!$activeKurikulum) {
            return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
        }

        try {
            // Validasi data yang dikirimkan
            $validated = $request->validate([
                'cpls' => 'required|array',
                'cpls.*.id' => 'nullable|integer',  // id boleh kosong, namun jika ada harus berupa integer
                'cpls.*.keterangan' => 'required|string|max:255',
            ]);

            // Loop untuk upsert setiap item dalam cpls
            foreach ($validated['cpls'] as $cpl) {
                $data = [
                    'keterangan' => $cpl['keterangan'],
                    'kurikulum_id' => $activeKurikulum->id,
                ];

                if (isset($cpl['id'])) {
                    // Update jika ada id
                    $existingCpl = Cpl::find($cpl['id']);
                    if ($existingCpl) {
                        $existingCpl->update($data);
                    }
                } else {
                    // Buat data baru jika tidak ada id
                    Cpl::create($data);
                }
            }

            return response()->json(['message' => 'Upsert berhasil'], 200);
        } catch (\Exception $e) {
            // Tangani error jika terjadi
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
            // Cari CPL berdasarkan id dan kurikulum_id aktif
            $cpl = Cpl::where('kurikulum_id', $activeKurikulum->id)
                ->where('id', $id)
                ->first();

            if (!$cpl) {
                return response()->json(['error' => 'CPL tidak ditemukan'], 404);
            }

            // Hapus data CPL
            $cpl->delete();

            return response()->json(['message' => 'Data CPL berhasil dihapus'], 200);
        } catch (\Exception $e) {
            // Menangani error umum
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
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
