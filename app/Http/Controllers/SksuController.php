<?php

namespace App\Http\Controllers;

use App\Exports\SksuTemplateExport;
use App\Http\Requests\UpsertSksuRequest;
use App\Imports\SksuImport;
use App\Models\Kurikulum;
use App\Models\Prodi;
use Illuminate\Http\Request;
use App\Models\Sksu as sksuModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Tymon\JWTAuth\Facades\JWTAuth;

class SksuController extends Controller
{
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

        $sksus = sksuModel::where('kurikulum_id', $activeKurikulum->id)->get();

        return response()->json($sksus);
    }

    public function store(UpsertSksuRequest $request)
    {
        try {
            DB::beginTransaction();

            $dataList = $request->validated();

            // Pastikan data tidak kosong sebelum mencari `prodiId`
            if (empty($dataList) || !isset($dataList[0]['prodiId'])) {
                return response()->json([
                    'message' => 'Data tidak valid atau prodiId tidak ditemukan dalam request.',
                ], 400);
            }

            $kurikulumId = Kurikulum::where('prodi_id', $dataList[0]['prodiId'])
                ->where('is_active', true)
                ->value('id');

            if (!$kurikulumId) {
                return response()->json([
                    'message' => "Kurikulum aktif tidak ditemukan untuk prodi_id: {$dataList[0]['prodiId']}",
                ], 404);
            }

            foreach ($dataList as $data) {
                SksuModel::updateOrCreate(
                    ['id' => $data['_id'] ?? null],
                    [
                        'profil_lulusan' => $data['profilLulusan'],
                        'kualifikasi' => $data['kualifikasi'],
                        'kategori' => $data['kategori'],
                        'kompetensi_kerja' => $data['kompetensiKerja'],
                        'kurikulum_id' => $kurikulumId,
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $sksu = sksuModel::find($id);
        if (!$sksu) {
            return response()->json([
                'message' => 'sksu not found.',
            ], 404);
        }
        $sksu->delete();

        return response()->json([
            'message' => `sksu berhasil dihapus`
        ], 200);
    }

    public function destroySksus(Request $request)
    {
        try {
            // Ambil daftar ID dari request
            $data = $request->all();

            if (empty($data) || !is_array($data)) {
                return response()->json([
                    'data' => $data,
                    'message' => 'Harap sertakan daftar ID yang valid untuk dihapus',
                ], 400);
            }

            $ids = array_column($data, '_id');

            // Cari SKSU berdasarkan ID
            $sksus = sksuModel::whereIn('id', $ids)->get();

            if ($sksus->isEmpty()) {
                return response()->json([
                    'data' => $ids,
                    'message' => 'Data tidak ditemukan untuk ID yang diberikan',
                ], 404);
            }

            // Loop untuk menghapus data terkait, jika ada
            foreach ($sksus as $sksu) {

                // Hapus data SKSU
                $sksu->delete();
            }

            return response()->json([
                'message' => 'Data berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => $ids,
                'message' => 'Terjadi kesalahan saat menghapus data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        try {
            Excel::import(new SksuImport, $request->file('file'));
            return response()->json(['message' => 'Data berhasil diimport.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function downloadTemplate()
    {
        $fileName = 'sksu_template.xlsx';

        return Excel::download(new SksuTemplateExport, $fileName);
    }
}
