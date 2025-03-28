<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IpteksTeknologi;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Exports\TeknologiTemplateExport;
use App\Http\Requests\UpsertTeknologiRequest;
use App\Imports\TeknologiImport;
use App\Models\Prodi;
use Maatwebsite\Excel\Facades\Excel;

class TeknologiController extends Controller
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
        $data = IpteksTeknologi::where("kurikulum_id", $activeKurikulum->id)->get();

        return response()->json([
            'message' => 'Data Ilmu Pengetahuan berhasil diambil.',
            'data' => $data,
        ], 200);
    }

    public function store(UpsertTeknologiRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = JWTAuth::parseToken()->authenticate();
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan.'], 404);
            }

            $dataList = $request->validated();

            $savedItems = [];
            foreach ($dataList as $data) {
                $data['kurikulum_id'] = $activeKurikulum->id;

                $item = IpteksTeknologi::updateOrCreate(
                    ['id' => $data['id'] ?? null],
                    $data
                );

                $savedItems[] = $item;
            }

            DB::commit();
            return response()->json([
                'message' => 'Data berhasil disimpan.',
                'data' => $savedItems
            ], 200);
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

        $item = IpteksTeknologi::where('kurikulum_id', $activeKurikulum->id)->findOrFail($id);
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

            IpteksTeknologi::whereIn('id', $ids)
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
            return Excel::download(new TeknologiTemplateExport(), 'template-teknologi.xlsx');
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

            Excel::import(new TeknologiImport(), $request->file('file'));

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
