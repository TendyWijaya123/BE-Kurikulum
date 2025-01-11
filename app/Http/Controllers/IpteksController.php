<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IpteksPengetahuan;
use App\Models\IpteksTeknologi;
use App\Models\IpteksSeni;
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
        $prodiId = $request->query('prodiId');


        // Validate kurikulum belongs to the correct prodi
        $data = $request->validate([
            'kurikulum_id' => [
                'required',
                'exists:kurikulums,id',
                function ($attribute, $value, $fail) use ($prodiId) {
                    $exists = \App\Models\Kurikulum::where('id', $value)
                        ->where('prodi_id', $prodiId)
                        ->exists();
                    if (!$exists) {
                        $fail('Kurikulum tidak ditemukan untuk program studi ini.');
                    }
                },
            ],
        ]);

        if ($type === 'pengetahuan') {
            $data['ilmu_pengetahuan'] = $request->validate(['ilmu_pengetahuan' => 'required|string|max:255'])['ilmu_pengetahuan'];
            $item = IpteksPengetahuan::create($data);
        } elseif ($type === 'teknologi') {
            $data['teknologi'] = $request->validate(['teknologi' => 'required|string|max:255'])['teknologi'];
            $item = IpteksTeknologi::create($data);
        } elseif ($type === 'seni') {
            $data['seni'] = $request->validate(['seni' => 'required|string|max:255'])['seni'];
            $item = IpteksSeni::create($data);
        } else {
            return response()->json(['message' => 'Tipe tidak valid.'], 400);
        }

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
        $prodiId = $request->query('prodiId');

        // Validate kurikulum belongs to the correct prodi
        $data = $request->validate([
            'kurikulum_id' => [
                'required',
                'exists:kurikulums,id',
                function ($attribute, $value, $fail) use ($prodiId) {
                    $exists = \App\Models\Kurikulum::where('id', $value)
                        ->where('prodi_id', $prodiId)
                        ->exists();
                    if (!$exists) {
                        $fail('Kurikulum tidak ditemukan untuk program studi ini.');
                    }
                },
            ],
        ]);

        if ($type === 'pengetahuan') {
            $item = IpteksPengetahuan::whereHas('kurikulum', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })->findOrFail($id);
            $data['ilmu_pengetahuan'] = $request->validate(['ilmu_pengetahuan' => 'required|string|max:255'])['ilmu_pengetahuan'];
        } elseif ($type === 'teknologi') {
            $item = IpteksTeknologi::whereHas('kurikulum', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })->findOrFail($id);
            $data['teknologi'] = $request->validate(['teknologi' => 'required|string|max:255'])['teknologi'];
        } elseif ($type === 'seni') {
            $item = IpteksSeni::whereHas('kurikulum', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })->findOrFail($id);
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
        $prodiId = $request->query('prodiId');

        if ($type === 'pengetahuan') {
            $item = IpteksPengetahuan::whereHas('kurikulum', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })->findOrFail($id);
        } elseif ($type === 'teknologi') {
            $item = IpteksTeknologi::whereHas('kurikulum', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })->findOrFail($id);
        } elseif ($type === 'seni') {
            $item = IpteksSeni::whereHas('kurikulum', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })->findOrFail($id);
        } else {
            return response()->json(['message' => 'Tipe tidak valid.'], 400);
        }

        $item->delete();

        return response()->json([
            'message' => ucfirst($type) . ' berhasil dihapus.',
        ], 200);
    }
}
