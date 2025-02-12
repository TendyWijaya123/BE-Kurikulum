<?php

namespace App\Http\Controllers;

use App\Exports\IpteksTemplateExport;
use App\Imports\IpteksImport;
use Illuminate\Http\Request;
use App\Models\Ipteks;
use Maatwebsite\Excel\Facades\Excel;
use Tymon\JWTAuth\Facades\JWTAuth;

class IpteksController extends Controller
{
    /**
     * Menampilkan semua data IPTEKS.
     */

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

    public function create(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $activeKurikulum = $user->activeKurikulum();

        if (!$activeKurikulum) {
            return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi pengguna.'], 404);
        }

        $validatedData = $request->validate([
            'kategori' => 'required|in:ilmu_pengetahuan,teknologi,seni',
            'deskripsi' => 'required|string|max:5000',
            'link_sumber' => 'nullable|url',
        ]);

        $validatedData['kurikulum_id'] = $activeKurikulum->id;
        $item = Ipteks::create($validatedData);

        return response()->json([
            'message' => 'IPTEKS berhasil dibuat.',
            'data' => $item,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $activeKurikulum = $user->activeKurikulum();

        if (!$activeKurikulum) {
            return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi pengguna.'], 404);
        }

        $item = Ipteks::where('kurikulum_id', $activeKurikulum->id)->findOrFail($id);
        $validatedData = $request->validate([
            'kategori' => 'required|in:ilmu_pengetahuan,teknologi,seni',
            'deskripsi' => 'required|string|max:5000',
            'link_sumber' => 'nullable|url',
        ]);

        $item->update($validatedData);

        return response()->json([
            'message' => 'IPTEKS berhasil diperbarui.',
            'data' => $item,
        ], 200);
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
