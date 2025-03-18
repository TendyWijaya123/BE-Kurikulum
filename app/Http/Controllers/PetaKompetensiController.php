<?php

namespace App\Http\Controllers;

use App\Models\PetaKompetensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PetaKompetensiController extends Controller
{
    public function getByProdi($prodiId)
    {
        try {
            $petaKompetensi = PetaKompetensi::where('prodi_id', $prodiId)->first();

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

        $validator = Validator::make($request->all(), [
            'gambar' => 'required|image|max:5120',
            'prodi_id' => 'required|exists:prodis,id',
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
            $petaKompetensi = PetaKompetensi::where('prodi_id', $request->prodi_id)->first();

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
                    'prodi_id' => $request->prodi_id,
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