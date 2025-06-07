<?php

namespace App\Http\Controllers;

use App\Exports\RpsTemplateExport;
use App\Imports\RpsImport;
use App\Models\DetailMataKuliahRPS;
use App\Models\InstrumenPenilaianRps;
use App\Models\MataKuliah;
use App\Models\RpsMatakuliah;
use App\Models\TujuanBelajarRPS;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UpsertRpsMatakuliah;

class RpsMataKuliahController extends Controller
{
    public function showRpsMataKuliah($id)
    {
        $mataKuliah = MataKuliah::with([
            'materiPembelajarans',
            'cpls',
            'tujuanBelajars',
            'dosens:id,nama',
            'bukuReferensis',
            'kemampuanAkhirs',
            'tujuanBelajarRps',
        ])->find($id);

        if (!$mataKuliah) {
            return response()->json([
                'success' => false,
                'message' => 'Mata Kuliah tidak ditemukan'
            ], 404);
        }

        $kategoriOrder = ['I', 'R', 'M', 'A'];

        $mataKuliah->cpls->each(function ($cpl) use ($kategoriOrder) {
            if (isset($cpl->pivot->kategori)) {
                $kategoriArray = explode(',', $cpl->pivot->kategori);

                $sortedKategori = array_intersect($kategoriOrder, $kategoriArray);

                $cpl->pivot->kategori = implode(',', $sortedKategori);
            }
        });

        $rps = RpsMatakuliah::with(['instrumenPenilaians'])->where('mata_kuliah_id', $id)
            ->with(['tujuanBelajar', 'cpl'])
            ->get();

        $data = [
            'mataKuliah' => $mataKuliah,
            'rps' => $rps,
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
           $validated = $request->validate([
                'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
                'minggu' => 'required|integer|unique:rps_matakuliah,minggu,NULL,id,mata_kuliah_id,' . $request->mata_kuliah_id,
                'kategori' => 'required|string|in:ETS,EAS,Reguler',

                'bentuk_pembelajaran' => 'nullable|string',
                'modalitas_pembelajaran' => 'nullable|string',
                'strategi_pembelajaran' => 'nullable|string',
                'metode_pembelajaran' => 'nullable|string',
                'media_pembelajaran' => 'nullable|string',

                'pokok_bahasan' => 'nullable|string',
                'sumber_belajar' => 'nullable|string',
                'kemampuan_akhir' => 'nullable|string',
                'hasil_belajar' => 'nullable|string',
                
            ]);

            $rps = RpsMatakuliah::create($validated);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data RPS Mata Kuliah berhasil ditambahkan',
                'data' => $rps->load('instrumenPenilaians'),
            ], 201);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' =>  $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function bulkCreateOrUpdate(UpsertRpsMatakuliah $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $results = [];

            foreach ($data['items'] as $item) {
                // Validasi per item jika perlu, atau asumsikan sudah tervalidasi oleh FormRequest
                $rps = RpsMatakuliah::updateOrCreate(
                    [
                        'mata_kuliah_id' => $item['mata_kuliah_id'],
                        'minggu' => $item['minggu']
                    ],
                    $item
                );

                // Handle instrumen_penilaians jika ada
                if (isset($item['instrumen_penilaians']) && is_array($item['instrumen_penilaians'])) {
                    // Hapus instrumen yang tidak ada di input
                    $existingIds = $rps->instrumenPenilaians()->pluck('id')->toArray();
                    $incomingIds = collect($item['instrumen_penilaians'])->pluck('id')->filter()->toArray();
                    $toDelete = array_diff($existingIds, $incomingIds);
                    if (!empty($toDelete)) {
                        InstrumenPenilaianRps::whereIn('id', $toDelete)->delete();
                    }

                    foreach ($item['instrumen_penilaians'] as $instrumen) {
                        $instrumen['rps_id'] = $rps->id;
                        if (isset($instrumen['id'])) {
                            $rps->instrumenPenilaians()->updateOrCreate(
                                ['id' => $instrumen['id']],
                                $instrumen
                            );
                        } else {
                            $rps->instrumenPenilaians()->create($instrumen);
                        }
                    }
                }

                $results[] = $rps->load('instrumenPenilaians');
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data RPS Mata Kuliah berhasil disimpan',
                'data' => $results
            ], 200);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function exportTemplate($mataKuliahid)
    {
        return Excel::download(new RpsTemplateExport($mataKuliahid), 'template_rps.xlsx');
    }

    public function import(Request $request, $mataKuliahId)
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
            Excel::import(new RpsImport($mataKuliahId), $file);

            return response()->json(['message' => 'Data berhasil diimport.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }



    /**
     * Menghapus data RPS Mata Kuliah
     */
    public function destroy($id)
    {
        $rps = RpsMatakuliah::find($id);

        if (!$rps) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $rps->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data RPS Mata Kuliah berhasil dihapus'
        ]);
    }

    //--------------------------------------------------TUjuan belajar------------------------------------------//

    public function storeTujuanBelajar(Request $request)
    {

        $mataKuliah = MataKuliah::find($request->mataKuliahId);
        if (!$mataKuliah) {
            return response()->json([
                'success' => false,
                'message' => 'Mata Kuliah tidak ditemukan',
            ], 404);
        }

        foreach ($request->tujuanBelajar as $tujuanBelajar) {
            TujuanBelajarRPS::updateOrCreate(
                ['id' => $tujuanBelajar['id'] ?? null],
                [
                    'deskripsi' => $tujuanBelajar['deskripsi'],
                    'mata_kuliah_id' => $mataKuliah->id,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Data Tujuan Belajar RPS berhasil diperbarui',
        ]);
    }

    public function removeTujuanBelajar($id)
    {
        $tujuan = TujuanBelajarRPS::find($id);

        if (!$tujuan) {
            return response()->json([
                'success' => false,
                'message' => 'Tujuan Belajar RPS tidak ditemukan',
            ], 404);
        }

        $tujuan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tujuan Belajar RPS berhasil dihapus',
        ]);
    }

    //------------------------------------------detail matakuliah-------------------------------------//
    public function storeDetailMatakuliahRps(Request $request){
        $mataKuliah = MataKuliah::find($request->mataKuliahId);

        if (!$mataKuliah) {
            return response()->json([
                'success' => false,
                'message' => 'Mata Kuliah tidak ditemukan',
            ], 404);
        }

        $mataKuliah->update([
            'deskripsi_singkat' => $request->detailMkRps['deskripsi_singkat'],
            'materi_pembelajaran' => $request->detailMkRps['materi_pembelajaran'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Detail Mata Kuliah RPS berhasil diperbarui',
        ]);
    }


    public function generateRPSPDF(Request $request,  $mataKuliahId)
    {
        $mataKuliah = MataKuliah::with(['bukuReferensis', 'rpss', 'rpss.instrumenPenilaians', 'kurikulum.prodi', 'materiPembelajarans', 'kurikulum.prodi.jurusan', 'cpls', 'tujuanBelajarRps', 'dosens'])->findOrFail($mataKuliahId);
        // Log::info("Pretty JSON Mata Kuliah:\n" . json_encode($mataKuliah, JSON_PRETTY_PRINT));
        

        $ringkasanInstrumen = [
            'ETS' => 0,
            'EAS' => 0,
            'Quiz' => 0,
            'Project' => 0,
            'Case Study' => 0,
            'Tugas' => 0
        ];

        foreach ($mataKuliah->rpss as $rps) {
            $kategori = $rps->kategori ?? '';

            if (in_array($kategori, ['ETS', 'EAS'])) {
                $ringkasanInstrumen[$kategori] += floatval($rps->bobot_penilaian ?? 0);
            } else {
                foreach ($rps->instrumenPenilaians ?? [] as $instrumen) {
                    $kategoriInstrumen = $instrumen->kategori ?? '';

                    if (in_array($kategoriInstrumen, ['Quiz', 'Project', 'Case Study', 'Tugas'])) {
                        $ringkasanInstrumen[$kategoriInstrumen] += floatval($instrumen->bobot_penilaian ?? 0);
                        Log::info("Tambah bobot untuk instrumen {$kategoriInstrumen}: " . floatval($instrumen->bobot_penilaian ?? 0));
                    }
                }
            }
        }


        $data = [
            'mataKuliah' => $mataKuliah,
            'ringkasanInstrumen' => $ringkasanInstrumen,
            'rpss' => $mataKuliah->rpss,
            // 'kaKbk' => $request->input('ka_kbk'),
            // 'koordProdi' => $request->input('koord_prodi'),
            // 'kaJurusan' => $request->input('ka_jurusan'),
            // 'wakilDirekturAkademik' => $request->input('wakil_direktur_akademik'),
        ];
        $pdf = Pdf::loadView('pdf.rps', $data)->setPaper('A4', 'landscape');

        return $pdf->download("RPS-{$mataKuliah->nama}.pdf");
    }

    

}
