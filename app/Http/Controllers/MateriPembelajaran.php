<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MateriPembelajaran as ModelMP;

class MateriPembelajaran extends Controller
{
    public function index(Request $request){
        $prodiId = $request->query('prodiId');
        $mp = ModelMP::
        whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId);
        })
        ->get();

        return response()->json(data: $mp);
    }

    public function store(Request $request){
        try{
            $dataList = $request->all();
            $kurikulumId = ModelMP::where('prodi_id', $dataList[0]['prodiId'])
                ->where('is_active', true)
                ->value('id');
            
            if (!$kurikulumId) {
                return response()->json([
                    'message' => "Kurikulum aktif tidak ditemukan untuk prodi_id: {$request[0]['prodiId']}",
                ], 404);
            }

            foreach ($dataList as $data) {
                ModelMP::updateOrCreate(
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
        $mp = ModelMP::find($id);
        if (!$mp) {
            return response()->json([
                'message' => 'materi pembelajaran not found.',
            ], 404);
        }
        // Hapus data SKSU
        $mp->delete();

        return response()->json([
            'message' => `materi pembelajaran berhasil dihapus`
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
            $mp = ModelMP::whereIn('id', $ids)->get();

            if ($mp->isEmpty()) {
                return response()->json([
                    'data' => $ids,
                    'message' => 'Data tidak ditemukan untuk ID yang diberikan',
                ], 404);
            }

            // Loop untuk menghapus data terkait, jika ada
            foreach ($mp as $materiPembelajaran) {
                // Hapus data SKSU
                $materiPembelajaran->delete();
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
