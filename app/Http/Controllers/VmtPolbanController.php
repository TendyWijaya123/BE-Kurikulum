<?php

namespace App\Http\Controllers;

use App\Models\VmtPolban;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class VmtPolbanController extends Controller
{
    public function firstOrCreate(Request $request)
    {
        try {
            // Autentikasi pengguna
            $user = JWTAuth::parseToken()->authenticate();

            // Ambil kurikulum aktif
            $activeKurikulum = $user->activeKurikulum();
            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
            }

            // Ambil atau buat VmtPolban berdasarkan kurikulum aktif
            $vmtPolban = VmtPolban::with(['misiPolbans', 'tujuanPolbans'])
                ->firstOrCreate(
                    ['kurikulum_id' => $activeKurikulum->id],
                    ['visi_polban' => 'Isikan visi polban'] // Nilai default jika tidak ditemukan
                );

            return response()->json([
                'message' => 'Data berhasil diambil atau dibuat',
                'data' => $vmtPolban
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Penanganan validasi
            return response()->json(['error' => 'Validasi gagal', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Penanganan kesalahan umum
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'visi_polban' => 'required|string',
            ]);

            $vmtPolban = VmtPolban::find($id);

            if (!$vmtPolban) {
                return response()->json(['error' => 'VmtPolban tidak ditemukan'], 404);
            }

            $vmtPolban->update([
                'visi_polban' => $validated['visi_polban'],
            ]);

            return response()->json(['message' => 'Data berhasil diperbarui', 'data' => $vmtPolban], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validasi gagal', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
        }
    }
}
