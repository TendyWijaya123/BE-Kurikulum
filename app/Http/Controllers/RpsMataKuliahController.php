<?php

namespace App\Http\Controllers;

use App\Exports\RpsTemplateExport;
use App\Imports\RpsImport;
use App\Models\InstrumenPenilaianRps;
use App\Models\MataKuliah;
use App\Models\RpsMatakuliah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

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
            ->with(['tujuanBelajar', 'kemampuanAkhir', 'cpl'])
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
                'kemampuan_akhir_id' => 'nullable|exists:kemampuan_akhirs,id',
                'minggu' => 'required|integer|unique:rps_matakuliah,minggu,NULL,id,mata_kuliah_id,' . $request->mata_kuliah_id,
                'pokok_bahasan' => 'nullable|string',
                'kategori' => 'required|string|in:ETS,EAS,Reguler',
                'modalitas_bentuk_strategi_metodepembelajaran' => 'nullable|string',
                'instrumen_penilaians' => 'nullable|array',
                'instrumen_penilaians.*.jenis_evaluasi' => 'required|string|in:Quiz,Project,Case Study,Tugas',
                'instrumen_penilaians.*.deskripsi' => 'required|string',
                'instrumen_penilaians.*.bobot_penilaian' => 'required|numeric',
                'hasil_belajar' => 'nullable|string',
                'tujuan_belajar_id' => 'nullable|exists:tujuan_belajars,id',
                'cpl_id' => 'nullable|exists:cpls,id',
                'bobot_penilaian' => 'nullable|numeric',
            ]);

            $rps = RpsMatakuliah::create($validated);

            if (!empty($validated['instrumen_penilaians'])) {
                foreach ($validated['instrumen_penilaians'] as $instrumen) {
                    $rps->instrumenPenilaians()->create($instrumen);
                }
                $totalBobot = collect($validated['instrumen_penilaians'])->sum('bobot_penilaian');
                $rps->update(['bobot_penilaian' => $totalBobot]);
            }

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

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $rps = RpsMatakuliah::find($id);

            if (!$rps) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $validated = $request->validate([
                'kemampuan_akhir_id' => 'nullable|exists:kemampuan_akhirs,id',
                'minggu' => 'required|integer|unique:rps_matakuliah,minggu,' . $id . ',id,mata_kuliah_id,' . $rps->mata_kuliah_id,
                'pokok_bahasan' => 'nullable|string',
                'kategori' => 'required|string|in:ETS,EAS,Reguler',
                'modalitas_bentuk_strategi_metodepembelajaran' => 'nullable|string',
                'instrumen_penilaians' => 'nullable|array',
                'instrumen_penilaians.*.id' => 'nullable|exists:instrumen_penilaian_rps,id',
                'instrumen_penilaians.*.jenis_evaluasi' => 'required|string|in:Quiz,Project,Case Study,Tugas',
                'instrumen_penilaians.*.deskripsi' => 'required|string',
                'instrumen_penilaians.*.bobot_penilaian' => 'required|numeric',
                'hasil_belajar' => 'nullable|string',
                'tujuan_belajar_id' => 'nullable|exists:tujuan_belajars,id',
                'cpl_id' => 'nullable|exists:cpls,id',
                'bobot_penilaian' => 'nullable|numeric',
            ]);

            $rps->update($validated);

            if (isset($validated['instrumen_penilaians'])) {
                $existingIds = $rps->instrumenPenilaians()->pluck('id')->toArray();
                $incomingIds = collect($validated['instrumen_penilaians'])->pluck('id')->filter()->toArray();

                $toDelete = array_diff($existingIds, $incomingIds);
                if (!empty($toDelete)) {
                    InstrumenPenilaianRps::whereIn('id', $toDelete)->delete();
                }

                foreach ($validated['instrumen_penilaians'] as $instrumen) {
                    if (isset($instrumen['id'])) {
                        $rps->instrumenPenilaians()->where('id', $instrumen['id'])->update([
                            'jenis_evaluasi' => $instrumen['jenis_evaluasi'],
                            'deskripsi' => $instrumen['deskripsi'],
                            'bobot_penilaian' => $instrumen['bobot_penilaian'],
                        ]);
                    } else {
                        $rps->instrumenPenilaians()->create($instrumen);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data RPS Mata Kuliah berhasil diperbarui',
                'data' => $rps->load('instrumenPenilaians'),
            ]);
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
                'message' => 'Terjadi kesalahan saat memperbarui data',
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


    public function generateRPSPDF(Request $request,  $mataKuliahId)
    {
        $mataKuliah = MataKuliah::with(['bukuReferensis', 'rpss', 'rpss.instrumenPenilaians', 'rpss.kemampuanAkhir', 'rpss.tujuanBelajar', 'rpss.cpl', 'kurikulum.prodi', 'materiPembelajarans', 'kurikulum.prodi.jurusan', 'cpls', 'tujuanBelajars', 'dosens'])->findOrFail($mataKuliahId);
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
                    $kategoriInstrumen = $instrumen->jenis_evaluasi ?? '';

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

            'kaKbk' => $request->input('ka_kbk'),
            'koordProdi' => $request->input('koord_prodi'),
            'kaJurusan' => $request->input('ka_jurusan'),
            'wakilDirekturAkademik' => $request->input('wakil_direktur_akademik'),
        ];
        $pdf = Pdf::loadView('pdf.rps', $data)->setPaper('A4', 'landscape');

        return $pdf->download("RPS-{$mataKuliah->nama}.pdf");
    }
}
