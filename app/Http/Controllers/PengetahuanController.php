<?php

namespace App\Http\Controllers;

use App\Exports\PengetahuanTemplateExport;
use App\Imports\PengetahuanImport;
use Illuminate\Http\Request;
use App\Models\Pengetahuan;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PengetahuanController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json([
                    'message' => 'Kurikulum aktif tidak ditemukan'
                ], 404);
            }

            $pengetahuan = Pengetahuan::where('kurikulum_id', $activeKurikulum->id)
                ->orderBy('kode_pengetahuan', 'asc')
                ->get();

            return response()->json([
                'message' => 'Data pengetahuan berhasil diambil',
                'data' => $pengetahuan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data pengetahuan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json([
                    'message' => 'Kurikulum aktif tidak ditemukan'
                ], 404);
            }

            $validated = $request->validate([
                'deskripsi' => 'required|string'
            ]);

            $pengetahuan = new Pengetahuan();
            $pengetahuan->deskripsi = $validated['deskripsi'];
            $pengetahuan->kurikulum_id = $activeKurikulum->id;
            $pengetahuan->save();

            return response()->json([
                'message' => 'Pengetahuan berhasil dibuat',
                'data' => $pengetahuan
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat pengetahuan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json([
                    'message' => 'Kurikulum aktif tidak ditemukan'
                ], 404);
            }

            $pengetahuan = Pengetahuan::where('kurikulum_id', $activeKurikulum->id)
                ->findOrFail($id);

            $validated = $request->validate([
                'kode_pengetahuan' => 'required|string|max:255',
                'deskripsi' => 'nullable|string'
            ]);

            $pengetahuan->update($validated);

            return response()->json([
                'message' => 'Pengetahuan berhasil diperbarui',
                'data' => $pengetahuan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui pengetahuan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json([
                    'message' => 'Kurikulum aktif tidak ditemukan'
                ], 404);
            }

            $pengetahuan = Pengetahuan::where('kurikulum_id', $activeKurikulum->id)
                ->findOrFail($id);

            $pengetahuan->delete();

            DB::commit();
            return response()->json([
                'message' => 'Pengetahuan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menghapus pengetahuan',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        try {
            Excel::import(new PengetahuanImport, $request->file('file'));
            return response()->json(['message' => 'Data berhasil diimport.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function downloadTemplate()
    {
        $fileName = 'pengetahuan_template.xlsx';

        return Excel::download(new PengetahuanTemplateExport, $fileName);
    }
}
