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

    public function upsert(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json([
                    'message' => 'Kurikulum aktif tidak ditemukan'
                ], 404);
            }

            $dataList = $request->all();
            $results = [];

            DB::beginTransaction();
            foreach ($dataList as $data) {
                $pengetahuan = Pengetahuan::updateOrCreate(
                    ['id' => $data['id'] ?? null],
                    [
                        'deskripsi' => $data['deskripsi'],
                        'kurikulum_id' => $activeKurikulum->id,
                    ]
                );
                $results[] = $pengetahuan;
            }
            DB::commit();

            return response()->json([
                'message' => 'Data pengetahuan berhasil disimpan',
                'data' => $results
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menyimpan data pengetahuan',
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
