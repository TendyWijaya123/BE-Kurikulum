<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeranIndustri;
use App\Models\PeranIndustriDeskripsi;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class PeranIndustriController extends Controller
{
    public function index()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
            }

            $peranIndustri = PeranIndustri::with('peranIndustriDeskripsis')
                ->where('kurikulum_id', $activeKurikulum->id)
                ->get();

            return response()->json(['data' => $peranIndustri], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Menyimpan data Peran Industri beserta deskripsinya.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
            }

            $validatedData = $request->validate([
                'jabatan' => 'required|string|max:255',
                'descriptions' => 'nullable|array',
                'descriptions.*.deskripsi_point' => 'required|string|max:255',
            ]);

            $peranIndustri = PeranIndustri::create([
                'jabatan' => $validatedData['jabatan'],
                'kurikulum_id' => $activeKurikulum->id,
            ]);

            if (isset($validatedData['descriptions'])) {
                foreach ($validatedData['descriptions'] as $description) {
                    $peranIndustri->peranIndustriDeskripsis()->create($description);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Data berhasil disimpan', 'data' => $peranIndustri->load('peranIndustriDeskripsis')], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Memperbarui data Peran Industri beserta deskripsinya.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
            }

            $validatedData = $request->validate([
                'jabatan' => 'required|string|max:255',
                'descriptions' => 'nullable|array',
                'descriptions.*.id' => 'nullable|integer|exists:peran_industri_deskripsis,id',
                'descriptions.*.deskripsi_point' => 'required|string|max:255',
            ]);

            $peranIndustri = PeranIndustri::findOrFail($id);
            $peranIndustri->update([
                'jabatan' => $validatedData['jabatan'],
                'kurikulum_id' => $activeKurikulum->id,
            ]);

            $validDescriptionIds = [];

            if (isset($validatedData['descriptions'])) {
                foreach ($validatedData['descriptions'] as $description) {
                    if (isset($description['id'])) {
                        $deskripsi = PeranIndustriDeskripsi::findOrFail($description['id']);
                        $deskripsi->update(['deskripsi_point' => $description['deskripsi_point']]);
                        $validDescriptionIds[] = $description['id'];
                    } else {
                        $newDescription = $peranIndustri->peranIndustriDeskripsis()->create($description);
                        $validDescriptionIds[] = $newDescription->id;
                    }
                }
            }

            $peranIndustri->peranIndustriDeskripsis()
                ->whereNotIn('id', $validDescriptionIds)
                ->delete();

            DB::commit();
            return response()->json(['message' => 'Data berhasil diperbarui', 'data' => $peranIndustri->load('peranIndustriDeskripsis')], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }


    /**
     * Menghapus Peran Industri beserta deskripsinya.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $peranIndustri = PeranIndustri::findOrFail($id);
            $peranIndustri->peranIndustriDeskripsis()->delete();
            $peranIndustri->delete();

            DB::commit();
            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }
}
