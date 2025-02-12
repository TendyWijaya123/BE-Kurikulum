<?php

namespace App\Http\Controllers;

use App\Exports\IpteksTemplateExport;
use App\Imports\IpteksImport;
use Illuminate\Http\Request;
use App\Models\Ipteks;
use Maatwebsite\Excel\Facades\Excel;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IpteksController extends Controller
{
    public function index(Request $request)
    {
        $prodiId = $request->query('prodiId');
        $ipteks = Ipteks::whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId);
        })->get();

        return response()->json([
            'message' => 'Daftar IPTEKS berhasil diambil.',
            'data' => $ipteks,
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = JWTAuth::parseToken()->authenticate();
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi pengguna.'], 404);
            }

            $dataList = $request->all();

            foreach ($dataList as $data) {
                $validator = Validator::make($data, [
                    'kategori' => 'required|in:ilmu_pengetahuan,teknologi,seni',
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

                Ipteks::updateOrCreate(
                    ['id' => $data['id'] ?? null],
                    $validatedData
                );
            }

            DB::commit();

            return response()->json([
                'message' => 'Data IPTEKS berhasil disimpan.',
                'success' => true
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
            return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi pengguna.'], 404);
        }

        $item = Ipteks::where('kurikulum_id', $activeKurikulum->id)->findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'IPTEKS berhasil dihapus.'], 200);
    }

    public function destroyMultiple(Request $request)
    {
        try {
            DB::beginTransaction();

            $ids = $request->input('ids');
            $user = JWTAuth::parseToken()->authenticate();
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi pengguna.'], 404);
            }

            Ipteks::whereIn('id', $ids)
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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        try {
            Excel::import(new IpteksImport, $request->file('file'));
            return response()->json(['message' => 'Data berhasil diimport.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function downloadTemplate()
    {
        $fileName = 'ipteks_template.xlsx';
        return Excel::download(new IpteksTemplateExport, $fileName);
    }
}