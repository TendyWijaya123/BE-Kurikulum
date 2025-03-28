<?php

namespace App\Http\Controllers;

use App\Models\JejaringMkDiagram;
use App\Models\MataKuliah;
use App\Models\PrasyaratMatakuliah;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class JejaringMataKuliahController extends Controller
{

    public function index(Request $request)
    {
        try {
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
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan'], 404);
            }

            $data = MataKuliah::where('kurikulum_id', $activeKurikulum->id)
                ->with(['prasyaratFrom:id'])->orderBy('semester', 'asc')
                ->get()
                ->map(function ($mataKuliah) {
                    return [
                        'id' => $mataKuliah->id,
                        'nama' => $mataKuliah->nama,
                        'kode' => $mataKuliah->kode,
                        'semester' => $mataKuliah->semester,
                        'prasyaratIds' => $mataKuliah->prasyaratFrom->pluck('id')->toArray(),
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function getJejaringData(Request $request)
    {
        try {
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
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan'], 404);
            }

            $mataKuliahBySemester = MataKuliah::where('kurikulum_id', $activeKurikulum->id)
                ->orderBy('semester')
                ->select('id', 'nama', 'sks', 'kategori', 'semester') // Pilih hanya kolom yang diperlukan
                ->get()
                ->groupBy('semester');

            $mataKuliahIds = $mataKuliahBySemester->flatten()->pluck('id'); // Ambil semua ID mata kuliah

            $jejaringPrasyarat = PrasyaratMatakuliah::whereIn('from_id', $mataKuliahIds)
                ->orWhereIn('to_id', $mataKuliahIds)->orderBy('to_id')
                ->get(['to_id', 'from_id']);

            $data = [
                'matakuliah' => $mataKuliahBySemester,
                'jejaring' => $jejaringPrasyarat,
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function updateJejaringMataKuliah(Request $request, $id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
            }

            $mataKuliah = MataKuliah::findOrFail($id);

            $request->validate([
                'prasyarat_ids' => "array",
            ]);


            if ($request->has('prasyarat_ids')) {
                $mataKuliah->prasyaratFrom()->sync($request->prasyarat_ids);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function uploadJejaringMKDiagram(Request $request)
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
            return response()->json(['error' => 'Kurikulum aktif tidak ditemukan'], 404);
        }

        $request->validate([
            'gambar' => 'required|image|max:5120',
        ]);

        try {
            $jejaringMKDiagram = JejaringMkDiagram::where('kurikulum_id', $activeKurikulum->id)->first();

            $gambarPath = $request->file('gambar')->store('jejaring-mk-diagram', 'public');
            $storagePath = $gambarPath;

            Log::info('File stored at: ' . $gambarPath);

            if ($jejaringMKDiagram) {
                if ($jejaringMKDiagram->gambar_url) {
                    $oldPath = 'public/' . $jejaringMKDiagram->gambar_url;
                    if (Storage::exists($oldPath)) {
                        Storage::delete($oldPath);
                    }
                }

                $jejaringMKDiagram->gambar_url = $storagePath;
                $jejaringMKDiagram->save();
            } else {
                $jejaringMKDiagram = JejaringMkDiagram::create([
                    'kurikulum_id' => $activeKurikulum->id,
                    'gambar_url' => $storagePath
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $jejaringMKDiagram,
                'message' => 'Gambar Berhasil Diupload.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error uploading Jejaring Mata Kuliah Diagra: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupload gambar: ' . $e->getMessage(),
            ], 500);
        }
    }
}
