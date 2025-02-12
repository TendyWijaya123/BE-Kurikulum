<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\KemampuanAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class MataKuliahController extends Controller
{
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $activeKurikulum = $user->activeKurikulum();

        if (!$activeKurikulum) {
            return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
        }

        $data = MataKuliah::with([
            'kemampuanAkhirs' => function ($query) {
                $query->with(['bentukPembelajarans:id', 'metodePembelajarans:id']);
            },
            'formulasiCpas:id'
        ])
            ->where('kurikulum_id', $activeKurikulum->id)
            ->get()
            ->map(function ($mataKuliah) {
                return [
                    'id' => $mataKuliah->id,
                    'nama' => $mataKuliah->nama,
                    'tujuan' => $mataKuliah->tujuan,
                    'kode' => $mataKuliah->kode,
                    'sks' => $mataKuliah->sks,
                    'total_beban_belajar' => $mataKuliah->total_beban_belajar,
                    'semester' => $mataKuliah->semester,
                    'teori_bt' => $mataKuliah->teori_bt,
                    'teori_pt' => $mataKuliah->teori_pt,
                    'teori_m' => $mataKuliah->teori_m,
                    'praktek_bt' => $mataKuliah->praktek_bt,
                    'praktek_pt' => $mataKuliah->praktek_pt,
                    'praktek_m' => $mataKuliah->praktek_m,


                    'kemampuan_akhir' => $mataKuliah->kemampuanAkhirs->map(function ($kemampuan) {
                        return [
                            'id' => $kemampuan->id,
                            'deskripsi' => $kemampuan->deskripsi,
                            'estimasi_beban_belajar' => $kemampuan->estimasi_beban_belajar,
                            'bentuk_pembelajaran' => $kemampuan->bentukPembelajarans->pluck('id'),
                            'metode_pembelajaran' => $kemampuan->metodePembelajarans->pluck('id'),
                        ];
                    }),
                    'formulasi_cpas' => $mataKuliah->formulasiCpas->pluck('id'), // ID formulasi cpas
                ];
            });



        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {

        $user = JWTAuth::parseToken()->authenticate();

        $activeKurikulum = $user->activeKurikulum();

        if (!$activeKurikulum) {
            return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
        }

        $request->validate([
            'kode' => 'required|string|unique:mata_kuliahs,kode,' . $request->id,
            'nama' => 'required|string',
            'tujuan' => 'required|string',
            'semester' => 'nullable|integer|min:1',
            'teori_bt' => 'nullable|integer|min:0',
            'teori_pt' => 'nullable|integer|min:0',
            'teori_m' => 'nullable|integer|min:0',
            'praktek_bt' => 'nullable|integer|min:0',
            'praktek_pt' => 'nullable|integer|min:0',
            'praktek_m' => 'nullable|integer|min:0',
            'kemampuan_akhirs' => 'array',
            'kemampuan_akhirs.*.deskripsi' => 'required|string',
            'kemampuan_akhirs.*.estimasi_beban_belajar' => 'required|numeric',
            'kemampuan_akhirs.*.metode_pembelajaran_ids' => 'array',
            'kemampuan_akhirs.*.bentuk_pembelajaran_ids' => 'array',
            'formulasi_cpa_ids' => 'array',
        ]);

        DB::beginTransaction();

        try {
            $mataKuliah = MataKuliah::create([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'tujuan' => $request->tujuan,
                'semester' => $request->semester,
                'teori_bt' => $request->teori_bt,
                'teori_pt' => $request->teori_pt,
                'teori_m' => $request->teori_m,
                'praktek_bt' => $request->praktek_bt,
                'praktek_pt' => $request->praktek_pt,
                'praktek_m' => $request->praktek_m,
                'kurikulum_id' => $activeKurikulum->id,
            ]);

            if ($request->has('kemampuan_akhirs')) {
                foreach ($request->kemampuan_akhirs as $kemampuan) {
                    $kemampuanAkhir = KemampuanAkhir::create([
                        'deskripsi' => $kemampuan['deskripsi'],
                        'estimasi_beban_belajar' => $kemampuan['estimasi_beban_belajar'],
                        'mata_kuliah_id' => $mataKuliah->id, // Mengaitkan kemampuan akhir dengan MataKuliah
                    ]);

                    if (isset($kemampuan['metode_pembelajaran_ids'])) {
                        $kemampuanAkhir->metodePembelajarans()->sync($kemampuan['metode_pembelajaran_ids']);
                    }

                    if (isset($kemampuan['bentuk_pembelajaran_ids'])) {
                        $kemampuanAkhir->bentukPembelajarans()->sync($kemampuan['bentuk_pembelajaran_ids']);
                    }
                }
            }

            if ($request->has('formulasi_cpa_ids')) {
                $mataKuliah->formulasiCpas()->sync($request->formulasi_cpa_ids);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $mataKuliah,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $activeKurikulum = $user->activeKurikulum();

        if (!$activeKurikulum) {
            return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
        }
        $request->validate([
            'kode' => 'required|string|unique:mata_kuliahs,kode,' . $request->id,
            'nama' => 'required|string',
            'tujuan' => 'required|string',
            'semester' => 'nullable|integer|min:1',
            'teori_bt' => 'nullable|integer|min:0',
            'teori_pt' => 'nullable|integer|min:0',
            'teori_m' => 'nullable|integer|min:0',
            'praktek_bt' => 'nullable|integer|min:0',
            'praktek_pt' => 'nullable|integer|min:0',
            'praktek_m' => 'nullable|integer|min:0',
            'kemampuan_akhirs' => 'array',
            'kemampuan_akhirs.*.deskripsi' => 'required|string',
            'kemampuan_akhirs.*.estimasi_beban_belajar' => 'required|numeric',
            'kemampuan_akhirs.*.metode_pembelajaran_ids' => 'array',
            'kemampuan_akhirs.*.bentuk_pembelajaran_ids' => 'array',
            'formulasi_cpa_ids' => 'array',
        ]);

        DB::beginTransaction();

        try {
            $mataKuliah = MataKuliah::findOrFail($id);

            $mataKuliah->update([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'tujuan' => $request->tujuan,
                'semester' => $request->semester,
                'teori_bt' => $request->teori_bt,
                'teori_pt' => $request->teori_pt,
                'teori_m' => $request->teori_m,
                'praktek_bt' => $request->praktek_bt,
                'praktek_pt' => $request->praktek_pt,
                'praktek_m' => $request->praktek_m,
                'kurikulum_id' => $activeKurikulum->id,
            ]);

            // Menangani kemampuan akhir
            if ($request->has('kemampuan_akhirs')) {

                $mataKuliah->kemampuanAkhirs()->delete();

                foreach ($request->kemampuan_akhirs as $kemampuan) {
                    $kemampuanAkhir = KemampuanAkhir::updateOrCreate(
                        ['id' => $kemampuan['id'] ?? null], // Jika id tidak ada, berarti ini data baru
                        [
                            'deskripsi' => $kemampuan['deskripsi'],
                            'estimasi_beban_belajar' => $kemampuan['estimasi_beban_belajar'],
                            'mata_kuliah_id' => $mataKuliah->id,
                        ]
                    );

                    if (isset($kemampuan['metode_pembelajaran_ids'])) {
                        $kemampuanAkhir->metodePembelajarans()->sync($kemampuan['metode_pembelajaran_ids']);
                    }

                    if (isset($kemampuan['bentuk_pembelajaran_ids'])) {
                        $kemampuanAkhir->bentukPembelajarans()->sync($kemampuan['bentuk_pembelajaran_ids']);
                    }
                }
            }

            // Menangani formulasi cpas
            if ($request->has('formulasi_cpa_ids')) {
                $mataKuliah->formulasiCpas()->sync($request->formulasi_cpa_ids);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $mataKuliah,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $mataKuliah = MataKuliah::findOrFail($id);

            $mataKuliah->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'MataKuliah berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
