<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataKuliah;
use App\Models\Dosen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DosenHasMatkulController extends Controller
{
    public function index(Request $request)
    {
        try {
            $dosen = Auth::guard('dosen')->user();
            // Pastikan $dosen adalah instance Eloquent sebelum memuat relasi
            if ($dosen instanceof \Illuminate\Database\Eloquent\Model) {
                $dosen->load('kaprodi'); // Ini akan memuat relasi kaprodi
            }

            if (!$dosen) {
                return response()->json(['message' => 'Dosen tidak ditemukan'], 404);
            }

            $dosens = Dosen::select('id','kode', 'nama', 'jurusan_id')->with("prodi")->get();
            $prodiId = $dosen->kaprodi?->id;

            $query = MataKuliah::with(['dosens:id,kode,nama,jurusan_id', 'kurikulum.prodi'])
                ->whereRelation('kurikulum', 'is_active', true)
                ->whereRelation('kurikulum.prodi', 'is_active', true)
                ->whereRelation('kurikulum.prodi', 'id', $prodiId);

            $mataKuliahs = $query->select('id', 'kode', 'nama', 'semester', 'kurikulum_id')->paginate(10);

            return response()->json([
                'mata_kuliahs' => $mataKuliahs,
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
                if (!isset($item['dosen_id']) || !is_array($item['dosen_id']) || count($item['dosen_id']) === 0) {
                    throw new \Exception("Dosen untuk mata kuliah ID {$item['mata_kuliah_id']} tidak boleh kosong.");
                }
                $mataKuliah = MataKuliah::findOrFail($item['mata_kuliah_id']);

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

    public function update(Request $request)
    {
        $validated = $request->validate([
            'mataKuliahId' => 'required|exists:mata_kuliahs,id',
            'dosenId' => 'required|array|min:1',
            'dosenId.*' => 'exists:dosens,id',
        ]);

        DB::beginTransaction();
        try {
            $mataKuliah = MataKuliah::findOrFail($validated['mataKuliahId']);
            $mataKuliah->dosens()->sync($validated['dosenId']);

            DB::commit();

            return response()->json([
                'message' => 'Relasi dosen pada mata kuliah berhasil diperbarui',
                'mata_kuliah_id' => $validated['mataKuliahId'],
                'dosen_id' => $validated['dosenId'],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Terjadi kesalahan',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

}
