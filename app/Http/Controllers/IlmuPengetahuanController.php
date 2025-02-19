<?php

namespace App\Http\Controllers;

use App\Models\IpteksPengetahuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Exports\IlmuPengetahuanTemplateExport;
use App\Imports\IlmuPengetahuanImport;
use Maatwebsite\Excel\Facades\Excel;

class IlmuPengetahuanController extends Controller
{
    public function index(Request $request)
    {
        try {
            $prodiId = $request->query('prodiId');

            // Validate prodiId
            if (!$prodiId) {
                return response()->json(['message' => 'prodiId parameter is required'], 400);
            }

            $data = IpteksPengetahuan::whereHas('kurikulum', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })->get();

            return response()->json([
                'message' => 'Data Ilmu Pengetahuan berhasil diambil.',
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan.'], 404);
            }

            $dataList = $request->all();

            // Check if data is in correct format
            if (!is_array($dataList) || (is_array($dataList) && !isset($dataList[0]) && !is_array($dataList[0]))) {
                $dataList = [$dataList]; // Convert to array of items if single item
            }

            $savedItems = [];
            foreach ($dataList as $data) {
                $validator = Validator::make($data, [
                    'deskripsi' => 'required|string|max:5000',
                    'link_sumber' => 'nullable|url',
                ]);

                if ($validator->fails()) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Validasi gagal',
                        'errors' => $validator->errors()
                    ], 422);
                }

                $validatedData = $validator->validated();
                $validatedData['kurikulum_id'] = $activeKurikulum->id;

                $item = IpteksPengetahuan::updateOrCreate(
                    ['id' => $data['id'] ?? null],
                    $validatedData
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
        try {
            $user = Auth::user();
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan.'], 404);
            }

            $item = IpteksPengetahuan::where('kurikulum_id', $activeKurikulum->id)->find($id);

            if (!$item) {
                return response()->json(['error' => 'Data tidak ditemukan.'], 404);
            }

            $item->delete();

            return response()->json(['message' => 'Data berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyMultiple(Request $request)
    {
        try {
            DB::beginTransaction();

            $ids = $request->input('ids');

            // Validate that ids is provided and is an array
            if (!$ids || !is_array($ids) || empty($ids)) {
                return response()->json(['error' => 'IDs harus berupa array dan tidak boleh kosong.'], 400);
            }

            $user = Auth::user();
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan.'], 404);
            }

            $deletedCount = IpteksPengetahuan::whereIn('id', $ids)
                ->where('kurikulum_id', $activeKurikulum->id)
                ->delete();

            if ($deletedCount == 0) {
                DB::rollBack();
                return response()->json(['message' => 'Tidak ada data yang dihapus.'], 404);
            }

            DB::commit();
            return response()->json([
                'message' => 'Data berhasil dihapus.',
                'count' => $deletedCount
            ], 200);

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
            return Excel::download(new IlmuPengetahuanTemplateExport(), 'template-ilmu-pengetahuan.xlsx');
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

            Excel::import(new IlmuPengetahuanImport(), $request->file('file'));

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