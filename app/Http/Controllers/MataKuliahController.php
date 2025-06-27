<?php

namespace App\Http\Controllers;

use App\Exports\MataKuliahTemplateExport;
use App\Http\Requests\StoreMataKuliahRequest;
use App\Http\Requests\UpdateMataKuliahRequest;
use App\Imports\MataKuliahImport;
use App\Models\BukuReferensi;
use App\Models\MataKuliah;
use App\Models\KemampuanAkhir;
use App\Models\Prodi;
use App\Models\TujuanBelajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;
use Tymon\JWTAuth\Facades\JWTAuth;

class MataKuliahController extends Controller
{
    public function index(Request $request)
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

        $query = MataKuliah::with([
            'kemampuanAkhirs' => function ($query) {
                $query->with(['bentukPembelajarans:id', 'metodePembelajarans:id']);
            },
            'formulasiCpas:id',
            'tujuanBelajars:id,kode,deskripsi,mata_kuliah_id'
        ])->where('kurikulum_id', $activeKurikulum->id);

        if ($request->filled('nama')) {
            $query->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($request->nama) . '%']);
        }

        if ($request->has('kategori')) {
            if ($request->kategori === 'null') {
                $query->whereNull('kategori');
            } else {
                $query->where('kategori', $request->kategori);
            }
        }

        if ($request->has('semester')) {
            if ($request->semester === 'null') {
                $query->whereNull('semester');
            } else {
                $query->where('semester', $request->semester);
            }
        }

        $data = $query->get()->map(function ($mataKuliah) {
            return [
                'id' => $mataKuliah->id,
                'nama' => $mataKuliah->nama,
                'kategori' => $mataKuliah->kategori,
                'kategori_mata_kuliah_prodi' => $mataKuliah->kategori_mata_kuliah_prodi,
                'kategori_mata_kuliah_polban' => $mataKuliah->kategori_mata_kuliah_polban,
                'tujuan' => $mataKuliah->tujuan,
                'kode' => $mataKuliah->kode,
                'sks' => $mataKuliah->sks,
                'total_beban_belajar' => $mataKuliah->total_beban_belajar,
                'semester' => $mataKuliah->semester,
                'teori_bt' => $mataKuliah->teori_bt,
                'teori_pt' => $mataKuliah->teori_pt,
                'teori_m' => $mataKuliah->teori_m,
                'total_teori' => $mataKuliah->total_teori,

                'praktek_bt' => $mataKuliah->praktek_bt,
                'praktek_pt' => $mataKuliah->praktek_pt,
                'praktek_m' => $mataKuliah->praktek_m,
                'total_praktek' => $mataKuliah->total_praktek,

                'kemampuan_akhir' => $mataKuliah->kemampuanAkhirs->map(function ($kemampuan) {
                    return [
                        'id' => $kemampuan->id,
                        'deskripsi' => $kemampuan->deskripsi,
                        'estimasi_beban_belajar' => $kemampuan->estimasi_beban_belajar,
                        'bentuk_pembelajaran' => $kemampuan->bentukPembelajarans->pluck('id'),
                        'metode_pembelajaran' => $kemampuan->metodePembelajarans->pluck('id'),
                    ];
                }),

                'formulasi_cpas' => $mataKuliah->formulasiCpas->pluck('id'),

                'tujuan_belajar' => $mataKuliah->tujuanBelajars->map(function ($tujuan) {
                    return [
                        'id' => $tujuan->id,
                        'kode' => $tujuan->kode,
                        'deskripsi' => $tujuan->deskripsi,
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }


    public function showMataKuliahByDosenPengampu(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();

        if (!$dosen) {
            return response()->json(['message' => 'Dosen tidak ditemukan'], 404);
        }

        $query = MataKuliah::with([
            'materiPembelajarans',
            'kurikulum.prodi'
        ])
            ->whereRelation('kurikulum', 'is_active', true)
            ->whereRelation('kurikulum.prodi', 'is_active', true)
            ->whereRelation('kurikulum.prodi.jurusan', 'id', $dosen->jurusan_id);

        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('kode')) {
            $query->where('kode', 'like', '%' . $request->kode . '%');
        }

        if ($request->filled('prodi_id')) {
            $query->whereRelation('kurikulum.prodi', 'id', $request->prodi_id);
        }

        $mataKuliahs = $query
            ->select('id', 'kurikulum_id', 'kode', 'nama', 'nama_inggris', 'deskripsi_singkat', 'deskripsi_singkat_inggris', 'materi_pembelajaran_inggris')
            ->paginate(10);



        return response()->json($mataKuliahs);
    }



    public function showMataKuliahByJurusan(Request $request)
    {
        try {
            $user = Auth::guard('dosen')->user();

            if (!$user) {
                return response()->json([
                    'error' => 'User tidak ditemukan atau tidak terautentikasi',
                ], 401);
            }

            $query = MataKuliah::with(['bukuReferensis:id,judul', 'kurikulum.prodi'])
                ->whereRelation('kurikulum', 'is_active', true)
                ->whereRelation('kurikulum.prodi', function ($q) use ($user) {
                    $q->where('is_active', true)
                        ->where('jurusan_id', $user->jurusan_id);
                });

            if ($request->filled('nama')) {
                $query->where('nama', 'like', '%' . $request->nama . '%');
            }

            if ($request->filled('kode')) {
                $query->where('kode', 'like', '%' . $request->kode . '%');
            }

            if ($request->filled('prodi_id')) {
                $query->whereRelation('kurikulum.prodi', 'id', $request->prodi_id);
            }
            $mataKuliahs = $query->select('id', 'kode', 'nama', 'semester', 'kurikulum_id')->paginate(10);

            return response()->json($mataKuliahs, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Mata Kuliah by Jurusan: ' . $e->getMessage());

            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data',
                'message' => $e->getMessage(),
            ], 500);
        }
    }



    public function assignReferensiKeMataKuliah(Request $request)
    {
        Log::info($request->all());
        try {
            $request->validate([
                'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
                'buku_referensi_id' => 'array',
                'buku_referensi_id.*' => 'exists:buku_referensis,id',
            ]);

            $mataKuliah = MataKuliah::find($request->mata_kuliah_id);
            $bukuReferensiIds = $request->buku_referensi_id;

            if (!$mataKuliah) {
                return response()->json([
                    'error' => 'Mata Kuliah tidak ditemukan',
                ], 404);
            }

            $mataKuliah->bukuReferensis()->sync($bukuReferensiIds);

            return response()->json([
                'message' => 'Buku Referensi berhasil ditambahkan ke Mata Kuliah',
                'mata_kuliah' => $mataKuliah->load('bukuReferensis:id,judul'),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error assigning Buku Referensi to Mata Kuliah: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan saat menghubungkan Buku Referensi ke Mata Kuliah',
                'message' => $e->getMessage(),
            ], 500);
        }
    }



    public function store(StoreMataKuliahRequest $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $activeKurikulum = $user->activeKurikulum();

        if (!$activeKurikulum) {
            return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
        }

        DB::beginTransaction();
        try {
            $mataKuliah = MataKuliah::create([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'kategori' => $request->kategori,
                'kategori_mata_kuliah_prodi' => $request->kategori_mata_kuliah_prodi,
                'kategori_mata_kuliah_polban' => $request->kategori_mata_kuliah_polban,
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

            $mataKuliah->tujuanBelajars()->createMany($request->tujuan_belajar);

            foreach ($request->kemampuan_akhirs as $kemampuan) {
                $kemampuanAkhir = $mataKuliah->kemampuanAkhirs()->create([
                    'deskripsi' => $kemampuan['deskripsi'],
                    'estimasi_beban_belajar' => $kemampuan['estimasi_beban_belajar'],
                ]);

                $kemampuanAkhir->metodePembelajarans()->sync($kemampuan['metode_pembelajaran_ids']);
                $kemampuanAkhir->bentukPembelajarans()->sync($kemampuan['bentuk_pembelajaran_ids']);
            }

            $mataKuliah->formulasiCpas()->sync($request->formulasi_cpa_ids);

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


    public function update(UpdateMataKuliahRequest $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $activeKurikulum = $user->activeKurikulum();

        if (!$activeKurikulum) {
            return response()->json(['error' => 'Kurikulum aktif tidak ditemukan untuk prodi user'], 404);
        }

        DB::beginTransaction();

        try {
            $mataKuliah = MataKuliah::findOrFail($id);

            $mataKuliah->update([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'kategori' => $request->kategori,
                'kategori_mata_kuliah_prodi' => $request->kategori_mata_kuliah_prodi,
                'kategori_mata_kuliah_polban' => $request->kategori_mata_kuliah_polban,
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
                $mataKuliah->kemampuanAkhirs()->delete();
                foreach ($request->kemampuan_akhirs as $kemampuan) {
                    $kemampuanAkhir = KemampuanAkhir::updateOrCreate(
                        ['id' => $kemampuan['id'] ?? null],
                        [
                            'deskripsi' => $kemampuan['deskripsi'],
                            'estimasi_beban_belajar' => $kemampuan['estimasi_beban_belajar'],
                            'mata_kuliah_id' => $mataKuliah->id,
                        ]
                    );

                    if (!empty($kemampuan['metode_pembelajaran_ids'])) {
                        $kemampuanAkhir->metodePembelajarans()->sync($kemampuan['metode_pembelajaran_ids']);
                    }

                    if (!empty($kemampuan['bentuk_pembelajaran_ids'])) {
                        $kemampuanAkhir->bentukPembelajarans()->sync($kemampuan['bentuk_pembelajaran_ids']);
                    }
                }
            }

            if ($request->has('tujuan_belajar')) {
                $mataKuliah->tujuanBelajars()->delete();
                foreach ($request->tujuan_belajar as $tujuanBelajar) {
                    TujuanBelajar::updateOrCreate(
                        ['id' => $tujuanBelajar['id'] ?? null],
                        [
                            'deskripsi' => $tujuanBelajar['deskripsi'],
                            'mata_kuliah_id' => $mataKuliah->id,
                        ]
                    );
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
                'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function updateDeskripsiSingkat(Request $request, $id)
    {
        $request->validate([
            'deskripsi_singkat' => 'nullable|string',
            'deskripsi_singkat_inggris' => 'nullable|string',
            'nama_inggris' => 'nullable|string',
            'materi_pembelajaran_inggris' => 'nullable|string',
        ]);


        DB::beginTransaction();

        try {
            $mataKuliah = MataKuliah::findOrFail($id);
            $mataKuliah->update([
                'deskripsi_singkat' => $request->deskripsi_singkat,
                'deskripsi_singkat_inggris' => $request->deskripsi_singkat_inggris,
                'nama_inggris' => $request->nama_inggris,
                'materi_pembelajaran_inggris' => $request->materi_pembelajaran_inggris,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $mataKuliah,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui deskripsi singkat: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function exportTemplate()
    {
        return Excel::download(new MataKuliahTemplateExport(), 'template_mata_kuliah.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);


        if (!$request->hasFile('file')) {
            return response()->json(['message' => 'File tidak ditemukan'], 400);
        }

        $file = $request->file('file');

        if (!$file->isValid()) {
            return response()->json(['message' => 'File tidak valid'], 400);
        }

        try {
            Excel::import(new MataKuliahImport, $file);

            return response()->json(['message' => 'Data berhasil diimport.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
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

    public function dropdownByKurikulum(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $activeKurikulum = $user->activeKurikulum();

            if (!$activeKurikulum) {
                return response()->json(['error' => 'Kurikulum aktif tidak ditemukan'], 404);
            }
            $dropdownMatakuliah = MataKuliah::where("kurikulum_id", $activeKurikulum->id)->get(['id', 'nama']);
            return response()->json([
                'success' => true,
                'data' => $dropdownMatakuliah,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validasi gagal',
                'messages' => $e->errors(),
            ], 422);
        }
    }
}
