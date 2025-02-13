<?php

namespace App\Http\Controllers;

use App\Exports\BenchKurikulumTemplateExport;
use App\Imports\BenchKurikulumImport;
use Illuminate\Http\Request;
use App\Models\BenchKurikulum as BenchKurikulumModel;
use App\Models\Kurikulum;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BenchKurikulumsController extends Controller
{
    public function index(Request $request)
    {
        $prodiId = $request->query('prodiId');
        $benchKurikulums = BenchKurikulumModel::whereHas('kurikulum', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId)->where('is_active', true);
            })
            ->get();

        return response()->json($benchKurikulums);
    }

    public function store(Request $request)
    {
        try {
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
                        'cpl' => $data['cpl'],
                        'ppm' => $data['ppm'],
                        'kurikulum_id' => $kurikulumId,
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => 'Data berhasil disimpan',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $benchKurikulums = BenchKurikulumModel::find($id);
        if (!$benchKurikulums) {
            return response()->json([
                'message' => 'bench kurikulum not found.',
            ], 404);
        }
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
                // Hapus data BK
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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        try {
            Excel::import(new BenchKurikulumImport, $request->file('file'));
            return response()->json(['message' => 'Data berhasil diimport.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function downloadTemplate()
    {
        $fileName = 'benchkurikulum_template.xlsx';

        return Excel::download(new BenchKurikulumTemplateExport, $fileName);
    }
}
