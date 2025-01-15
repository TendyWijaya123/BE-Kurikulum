<?php

namespace App\Http\Controllers;

use App\Models\Kurikulum;
use Illuminate\Http\Request;
use App\Models\Sksu as sksuModel;
use Illuminate\Support\Facades\DB;


class SksuController extends Controller
{
    public function index(Request $request){
        $prodiId = $request->query('prodiId');
        $sksus = sksuModel::with('kompetensiKerja')
        ->whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId)->where('is_active', true);
        })
        ->get();

        return response()->json($sksus);
    }

    public function store(Request $request){
        try{

            DB::beginTransaction();

            $dataList = $request->all();
            $kurikulumId = Kurikulum::where('prodi_id', $dataList[0]['prodiId'])
                ->where('is_active', true)
                ->value('id');
            
            if (!$kurikulumId) {
                return response()->json([
                    'message' => "Kurikulum aktif tidak ditemukan untuk prodi_id: {$request[0]['prodiId']}",
                ], 404);
            }

            foreach ($dataList as $data) {
                $sksu = sksuModel::updateOrCreate(
                    ['id' => $data['_id'] ?? null],
                    [
                        'profil_lulusan' => $data['profilLulusan'],
                        'kualifikasi' => $data['kualifikasi'],
                        'kategori' => $data['kategori'],
                        'kurikulum_id' => $kurikulumId,
                    ]
                );

                // Menyimpan kompetensi kerja terkait
                if (!empty($data['kompetensiKerja'])) {
                    // Hapus data lama jika ada (untuk memperbarui data)
                    $sksu->kompetensiKerja()->delete();
                }
                foreach ($data['kompetensiKerja'] as $kompetensi) {
                    $sksu->kompetensiKerja()->create([
                        'kompetensi_kerja' => $kompetensi,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => 'Data berhasil disimpan',
            ], 200);
        }catch(\Exception $e)
        {
            DB::rollBack();
            
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id){
        $sksu = sksuModel::find($id);
        if (!$sksu) {
            return response()->json([
                'message' => 'sksu not found.',
            ], 404);
        }
        $sksu->kompetensiKerja()->delete();
        // Hapus data SKSU
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
                // Hapus data di tabel kompetensi kerja terkait
                $sksu->kompetensiKerja()->delete();

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

}
