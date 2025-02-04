<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeDimension;
use App\Models\Kurikulum;
use Illuminate\Http\Request;
use App\Models\MateriPembelajaran as ModelMP;
use Illuminate\Support\Facades\DB;

class MateriPembelajaranController extends Controller
{
    public function index(Request $request){
        $prodiId = $request->query('prodiId');
        $knowledgeDimensions = KnowledgeDimension::all();
        $mp = ModelMP::whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId)
                  ->where('is_active', true);
        })
        ->with('knowledgeDimension')
        ->get();

        return response()->json(['data'=> $mp, 'knowledge' => $knowledgeDimensions]);
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
                $mp = ModelMP::updateOrCreate(
                    ['id' => $data['_id'] ?? null],
                    [
                        'code' => $data['code'],
                        'description' => $data['description'],
                        'cognitif_proses' => $data['cognitifProses'],
                        'kurikulum_id' => $kurikulumId,
                    ]
                );

                if (!empty($data['knowledgeDimension'])) {
                    $knowledgeCodes = array_map('trim', $data['knowledgeDimension']);
                    $knowledgeDimensions = KnowledgeDimension::whereIn('code', $knowledgeCodes)->pluck('code');
                    $mp->knowledgeDimension()->sync($knowledgeDimensions);
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
        $mp = ModelMP::find($id);
        if (!$mp) {
            return response()->json([
                'message' => 'materi pembelajaran not found.',
            ], 404);
        }
        // Hapus data SKSU
        $mp->knowledgeDimension()->detach();
        $mp->delete();

        return response()->json([
            'message' => `materi pembelajaran berhasil dihapus`
        ], 200);
    }

    public function destroyMateriPembelajarans(Request $request)
    {
        try {

            DB::beginTransaction();

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
                $materiPembelajaran->knowledgeDimension()->detach();
                // Hapus data SKSU
                $materiPembelajaran->delete();
            }

            DB::commit();

            return response()->json([
                'message' => 'Data berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();
            
            return response()->json([
                'data' => $ids,
                'message' => 'Terjadi kesalahan saat menghapus data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
