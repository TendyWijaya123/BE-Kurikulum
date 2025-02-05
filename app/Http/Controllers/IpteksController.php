<?php

namespace App\Http\Controllers;

use App\Exports\IpteksTemplateExport;
use App\Imports\IpteksImport;
use Illuminate\Http\Request;
use App\Models\IpteksPengetahuan;
use App\Models\IpteksTeknologi;
use App\Models\IpteksSeni;
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
        $pengetahuan = IpteksPengetahuan::whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId);
        })->get();
        $teknologi = IpteksTeknologi::whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId);
        })->get();
        $seni = IpteksSeni::whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId);
        })->get();

        return response()->json([
            'message' => 'Daftar IPTEKS berhasil diambil.',
            'pengetahuan' => $pengetahuan,
            'teknologi' => $teknologi,
            'seni' => $seni,
        ], 200);
    }

    /**
     * Menyimpan data baru berdasarkan tipe.
     */
    public function create(Request $request, $type)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $activeKurikulum = $user->activeKurikulum();

        // Validasi keberadaan kurikulum aktif
        if (!$activeKurikulum) {
            return response()->json([
                'error' => 'Kurikulum aktif tidak ditemukan untuk prodi pengguna.'
            ], 404);
        }

        // Validasi input tambahan berdasarkan tipe
        switch ($type) {
            case 'pengetahuan':
                $validatedData = $request->validate([
                    'ilmu_pengetahuan' => 'required|string|max:255',
                ]);
                $validatedData['kurikulum_id'] = $activeKurikulum->id;
                $item = IpteksPengetahuan::create($validatedData);
                break;

            case 'teknologi':
                $validatedData = $request->validate([
                    'teknologi' => 'required|string|max:255',
                ]);
                $validatedData['kurikulum_id'] = $activeKurikulum->id;
                $item = IpteksTeknologi::create($validatedData);
                break;

            case 'seni':
                $validatedData = $request->validate([
                    'seni' => 'required|string|max:255',
                ]);
                $validatedData['kurikulum_id'] = $activeKurikulum->id;
                $item = IpteksSeni::create($validatedData);
                break;

            default:
                return response()->json([
                    'error' => 'Tipe tidak valid. Harus berupa "pengetahuan", "teknologi", atau "seni".'
                ], 400);
        }

        // Mengembalikan respon JSON sukses
        return response()->json([
            'message' => ucfirst($type) . ' berhasil dibuat.',
            'data' => $item,
        ], 201);
    }


    /**
     * Mengupdate data berdasarkan tipe dan ID.
     */
    public function update(Request $request, $type, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $activeKurikulum = $user->activeKurikulum();

        if (!$activeKurikulum) {
            return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
        }

        $data = [];

        if ($type === 'pengetahuan') {
            $item = IpteksPengetahuan::where('kurikulum_id', $activeKurikulum->id)
                ->findOrFail($id);
            $data['ilmu_pengetahuan'] = $request->validate(['ilmu_pengetahuan' => 'required|string|max:255'])['ilmu_pengetahuan'];
        } elseif ($type === 'teknologi') {
            $item = IpteksTeknologi::where('kurikulum_id', $activeKurikulum->id)
                ->findOrFail($id);
            $data['teknologi'] = $request->validate(['teknologi' => 'required|string|max:255'])['teknologi'];
        } elseif ($type === 'seni') {
            $item = IpteksSeni::where('kurikulum_id', $activeKurikulum->id)
                ->findOrFail($id);
            $data['seni'] = $request->validate(['seni' => 'required|string|max:255'])['seni'];
        } else {
            return response()->json(['message' => 'Tipe tidak valid.'], 400);
        }

        $item->update($data);

        return response()->json([
            'message' => ucfirst($type) . ' berhasil diperbarui.',
            'data' => $item,
        ], 200);
    }

    /**
     * Menghapus data berdasarkan ID.
     */
    public function destroy(Request $request, $type, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $activeKurikulum = $user->activeKurikulum();

        if (!$activeKurikulum) {
            return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
        }

        if ($type === 'pengetahuan') {
            $item = IpteksPengetahuan::where('kurikulum_id', $activeKurikulum->id)
                ->findOrFail($id);
        } elseif ($type === 'teknologi') {
            $item = IpteksTeknologi::where('kurikulum_id', $activeKurikulum->id)
                ->findOrFail($id);
        } elseif ($type === 'seni') {
            $item = IpteksSeni::where('kurikulum_id', $activeKurikulum->id)
                ->findOrFail($id);
        } else {
            return response()->json(['message' => 'Tipe tidak valid.'], 400);
        }

        $item->delete();

        return response()->json([
            'message' => ucfirst($type) . ' berhasil dihapus.',
        ], 200);
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
