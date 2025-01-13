<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CplKkni as ModelKkni;
use App\Models\Kurikulum;

class KkniController extends Controller
{
    public function index(Request $request){
        $prodiId = $request->query('prodiId');
        $kkni = ModelKkni::
        whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId);
        })
        ->get();

        return response()->json($kkni);
    }

    public function store(Request $request){
        try{
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
                ModelKkni::updateOrCreate(
                    ['id' => $data['_id'] ?? null],
                    [
                        'code' => $data['code'],
                        'description' => $data['description'],
                        'kurikulum_id' => $kurikulumId,
                    ]
                );
            }
            return response()->json([
                'success' => 'Data berhasil disimpan',
            ], 200);
        }catch(\Exception $e)
        {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id){
        $sksu = ModelKkni::find($id);
        if (!$sksu) {
            return response()->json([
                'message' => 'sksu not found.',
            ], 404);
        }
        // Hapus data SKSU
        $sksu->delete();

        return response()->json([
            'message' => `sksu berhasil dihapus`
        ], 200);
    }

    public function destroyCpkKknis(Request $request)
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
            $kkni = ModelKkni::whereIn('id', $ids)->get();

            if ($kkni->isEmpty()) {
                return response()->json([
                    'data' => $ids,
                    'message' => 'Data tidak ditemukan untuk ID yang diberikan',
                ], 404);
            }

            // Loop untuk menghapus data terkait, jika ada
            foreach ($kkni as $cpl) {
                // Hapus data SKSU
                $cpl->delete();
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
