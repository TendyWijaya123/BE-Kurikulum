<?php

namespace App\Http\Controllers;

use App\Models\IpteksSeni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Exports\SeniTemplateExport;
use App\Imports\SeniImport;
use Maatwebsite\Excel\Facades\Excel;

class SeniController extends Controller
{
    public function index(Request $request)
    {
        $prodiId = $request->query('prodiId');
        $data = IpteksSeni::whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId);
        })->get();

        return response()->json([
            'message' => 'Data Ilmu Pengetahuan berhasil diambil.',
            'data' => $data,
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = JWTAuth::parseToken()->authenticate();
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan.'], 404);
            }

            $dataList = $request->all();

            foreach ($dataList as $data) {
                $validator = Validator::make($data, [
                    'deskripsi' => 'required|string|max:5000',
                    'link_sumber' => 'nullable|url',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'message' => 'Validasi gagal',
                        'errors' => $validator->errors()
                    ], 422);
                }

                $validatedData = $validator->validated();
                $validatedData['kurikulum_id'] = $activeKurikulum->id;

                IpteksSeni::updateOrCreate(
                    ['id' => $data['id'] ?? null],
                    $validatedData
                );
            }

            DB::commit();
            return response()->json(['message' => 'Data berhasil disimpan.'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $activeKurikulum = $user->activeKurikulum();

        if (!$activeKurikulum) {
            return response()->json(['error' => 'Kurikulum aktif tidak ditemukan.'], 404);
        }

        $item = IpteksSeni::where('kurikulum_id', $activeKurikulum->id)->findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Data berhasil dihapus.'], 200);
    }

    public function destroyMultiple(Request $request)
    {
        try {
            DB::beginTransaction();

            $ids = $request->input('ids');
            $user = JWTAuth::parseToken()->authenticate();
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan.'], 404);
            }

            IpteksSeni::whereIn('id', $ids)
                ->where('kurikulum_id', $activeKurikulum->id)
                ->delete();

            DB::commit();
            return response()->json(['message' => 'Data berhasil dihapus.'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function downloadTemplate()
    {
        try {
            return Excel::download(new SeniTemplateExport(), 'template-seni.xlsx');
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengunduh template',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function import(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:xlsx,xls',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            Excel::import(new SeniImport(), $request->file('file'));

            DB::commit();

            return response()->json([
                'message' => 'Data berhasil diimport'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengimport data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
