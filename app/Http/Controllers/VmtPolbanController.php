<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateVmtPolbanRequest;
use App\Models\Prodi;
use App\Models\VmtPolban;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class VmtPolbanController extends Controller
{
    public function firstOrCreate(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $prodiId = $request->input('prodiId');

            if ($prodiId) {
                $prodi = Prodi::find($prodiId);
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

            $vmtPolban = VmtPolban::with(['misiPolbans', 'tujuanPolbans'])
                ->firstOrCreate(
                    ['kurikulum_id' => $activeKurikulum->id],
                    ['visi_polban' => 'Isikan visi polban']
                );

            return response()->json([
                'message' => 'Data berhasil diambil atau dibuat',
                'data' => $vmtPolban
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validasi gagal', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateVmtPolbanRequest $request, $id)
    {
        try {
            $vmtPolban = VmtPolban::find($id);

            if (!$vmtPolban) {
                return response()->json(['error' => 'VmtPolban tidak ditemukan'], 404);
            }

            $vmtPolban->update($request->validated());

            return response()->json([
                'message' => 'Data berhasil diperbarui',
                'data' => $vmtPolban
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
