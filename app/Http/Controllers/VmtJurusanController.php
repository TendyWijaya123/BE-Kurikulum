<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateVmtJurusanRequest;
use App\Models\Kurikulum;
use App\Models\Prodi;
use App\Models\VmtJurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class VmtJurusanController extends Controller
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
                return response()->json(['status' => 'Tidak ada kurikulum  yang aktif pada prodi ini'], 404);
            }
            $vmtJurusan = VmtJurusan::with('misiJurusans')
                ->where('kurikulum_id', $activeKurikulum->id)
                ->first();


            if (!$vmtJurusan) {
                $vmtJurusan = VmtJurusan::create([
                    'kurikulum_id' => $activeKurikulum->id,
                    'visi_jurusan' => "Isikan visi jurusan",
                    'visi_keilmuan_prodi' => "Isikan visi keilmuan prodi",
                ]);
            }

            return response()->json(['message' => 'Data berhasil disimpan', 'data' => $vmtJurusan], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validasi gagal', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
        }
    }


    public function update(UpdateVmtJurusanRequest $request, $id)
    {
        try {
            $vmtJurusan = VmtJurusan::find($id);

            if (!$vmtJurusan) {
                return response()->json(['error' => 'VmtJurusan tidak ditemukan'], 404);
            }

            $vmtJurusan->update($request->validated());

            return response()->json([
                'message' => 'Data berhasil diperbarui',
                'data' => $vmtJurusan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
