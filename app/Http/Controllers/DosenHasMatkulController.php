<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataKuliah;
use App\Models\Dosen;
use Illuminate\Support\Facades\DB;


class DosenHasMatkulController extends Controller
{
    public function index(Request $request)
    {
        try {
            $prodiId = $request->query('id');
            $dosens = Dosen::all();

            $mataKuliah = MataKuliah::whereHas('kurikulum', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId)->where('is_active', true);
            })->with('dosens')
                ->get();

            return response()->json([
                'mata_kuliahs' => $mataKuliah,
                'dosens' => $dosens
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();

        DB::beginTransaction();
        try {
            foreach ($data as $item) {
                // Pastikan dosen_id ada dan valid
                if (!isset($item['dosen_id']) || !is_array($item['dosen_id']) || count($item['dosen_id']) === 0) {
                    throw new \Exception("Dosen untuk mata kuliah ID {$item['mata_kuliah_id']} tidak boleh kosong.");
                }

                // Ambil data mata kuliah
                $mataKuliah = MataKuliah::findOrFail($item['mata_kuliah_id']);

                // Perbarui relasi dosen dengan sync
                $mataKuliah->dosens()->sync($item['dosen_id']);
            }

            DB::commit();

            return response()->json([
                'message' => 'Relasi mata kuliah dan dosen berhasil diperbarui',
                'data' => $request->mata_kuliah_dosen
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Terjadi kesalahan',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    // public function getRoleDropdown()
    // {
    //     $roles = Dosen::select('id', 'name')->get();

    //     return response()->json($roles);
    // }

    // public function destroy($id){
    //     $sksu = Dosen::find($id);
    //     if (!$sksu) {
    //         return response()->json([
    //             'message' => 'role not found.',
    //         ], 404);
    //     }
    //     $sksu->delete();

    //     return response()->json([
    //         'message' => `role berhasil dihapus`
    //     ], 200);
    // }

    // public function destroyPermissions(Request $request)
    // {
    //     try {
    //         // Ambil daftar ID dari request
    //         $data = $request->all();

    //         if (empty($data) || !is_array($data)) {
    //             return response()->json([
    //                 'data' => $data,
    //                 'message' => 'Harap sertakan daftar ID yang valid untuk dihapus',
    //             ], 400);
    //         }

    //         $ids = array_column($data, '_id');

    //         // Cari SKSU berdasarkan ID
    //         $dosens = Dosen::whereIn('id', $ids)->get();

    //         if ($dosens->isEmpty()) {
    //             return response()->json([
    //                 'data' => $ids,
    //                 'message' => 'Data tidak ditemukan untuk ID yang diberikan',
    //             ], 404);
    //         }

    //         // Loop untuk menghapus data terkait, jika ada
    //         foreach ($dosens as $dosen){
    //             // Hapus data SKSU
    //             $dosen->delete();
    //         }

    //         return response()->json([
    //             'message' => 'Data berhasil dihapus',
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'data' => $ids,
    //             'message' => 'Terjadi kesalahan saat menghapus data',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }
}
