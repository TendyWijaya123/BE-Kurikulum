<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BenchKurikulum as BenchKurikulumModel;
use App\Models\Kurikulum;
use Illuminate\Support\Facades\DB;

class BenchKurikulumsController extends Controller
{
    public function index(Request $request){
        $prodiId = $request->query('prodiId');
        $benchKurikulums = BenchKurikulumModel::with('bkCpls')->with('bkPpms')
        ->whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId)->where('is_active', true);
        })
        ->get();

        return response()->json($benchKurikulums);
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
                $benchKurikulums = BenchKurikulumModel::updateOrCreate(
                    ['id' => $data['_id'] ?? null],
                    [
                        'program_studi' => $data['programStudi'],
                        'kategori' => $data['kategori'],
                        'kurikulum_id' => $kurikulumId,
                    ]
                );

                // Menyimpan cpl dan ppm terkait
                if (!empty($data['cpl'])) {
                    $benchKurikulums->bkCpls()->delete();
                }
                foreach ($data['cpl'] as $cpl) {
                    $benchKurikulums->bkCpls()->create([
                        'cpl' => $cpl,
                    ]);
                }

                if (!empty($data['ppm'])) {
                    $benchKurikulums->bkPpms()->delete();
                }
                foreach ($data['ppm'] as $ppm) {
                    $benchKurikulums->bkPpms()->create([
                        'ppm' => $ppm,
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
        $benchKurikulums = BenchKurikulumModel::find($id);
        if (!$benchKurikulums) {
            return response()->json([
                'message' => 'bench kurikulum not found.',
            ], 404);
        }
        $benchKurikulums->bkCpls()->delete();
        $benchKurikulums->bkPpms()->delete();
        // Hapus data Bench kurikulum
        $benchKurikulums->delete();

        return response()->json([
            'message' => `bench kurikulum berhasil dihapus`
        ], 200);
    }

    public function destroyBenchKurikulums(Request $request)
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
            $benchKurikulums = BenchKurikulumModel::whereIn('id', $ids)->get();

            if ($benchKurikulums->isEmpty()) {
                return response()->json([
                    'data' => $ids,
                    'message' => 'Data tidak ditemukan untuk ID yang diberikan',
                ], 404);
            }

            // Loop untuk menghapus data terkait, jika ada
            foreach ($benchKurikulums as $bk) {
                // Hapus data di tabel kompetensi kerja terkait
                $bk->bkCpls()->delete();
                $bk->bkPpms()->delete();

                // Hapus data SKSU
                $bk->delete();
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
