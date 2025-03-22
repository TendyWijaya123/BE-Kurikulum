<?php

namespace App\Http\Controllers;

use App\Models\PetaKompetensi;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class PetaKompetensiController extends Controller
{
    public function getByProdi(Request $request)
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
            $petaKompetensi = PetaKompetensi::where('kurikulum_id', $activeKurikulum->id)->first();
            return response()->json([
                'success' => true,
                'data' => $petaKompetensi,
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting peta kompetensi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data.',
            ], 500);
        }
    }

    public function uploadGambar(Request $request)
    {
        Log::info('Upload request received', $request->all());

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

        $validator = Validator::make($request->all(), [
            'gambar' => 'required|image|max:5120',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed: ' . json_encode($validator->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $petaKompetensi = PetaKompetensi::where('kurikulum_id', $activeKurikulum->id)->first();

            $gambarPath = $request->file('gambar')->store('peta-kompetensi', 'public');
            $storagePath = $gambarPath;

            Log::info('File stored at: ' . $gambarPath);

            if ($petaKompetensi) {
                if ($petaKompetensi->gambar_url) {
                    $oldPath = 'public/' . $petaKompetensi->gambar_url;
                    if (Storage::exists($oldPath)) {
                        Storage::delete($oldPath);
                    }
                }

                $petaKompetensi->gambar_url = $storagePath;
                $petaKompetensi->save();
            } else {
                $petaKompetensi = PetaKompetensi::create([
                    'kurikulum_id' => $activeKurikulum->id,
                    'gambar_url' => $storagePath
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $petaKompetensi,
                'message' => 'Gambar peta kompetensi berhasil diupload.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error uploading peta kompetensi: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupload gambar: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function deleteGambar($id)
    {
        try {
            $petaKompetensi = PetaKompetensi::findOrFail($id);

            if ($petaKompetensi->gambar_url) {
                $path = 'public/' . $petaKompetensi->gambar_url;
                if (Storage::exists($path)) {
                    Storage::delete($path);
                }
            }

            $petaKompetensi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Gambar peta kompetensi berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting peta kompetensi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus gambar.',
            ], 500);
        }
    }
}
