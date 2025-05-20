<?php

namespace App\Http\Controllers;

use App\Exports\RpsTemplateExport;
use App\Imports\RpsImport;
use App\Models\MataKuliah;
use App\Models\RpsMatakuliah;
use Illuminate\Http\Request;
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
            'bukuReferensis:id,judul',
            'kemampuanAkhirs'
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

        $rps = RpsMatakuliah::where('mata_kuliah_id', $id)
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


    /**
     * Menyimpan data RPS Mata Kuliah
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
                'kemampuan_akhir_id' => 'nullable|exists:kemampuan_akhirs,id',
                'minggu' => 'required|integer|unique:rps_matakuliah,minggu,NULL,id,mata_kuliah_id,' . $request->mata_kuliah_id,
                'pokok_bahasan' => 'required|string',
                'modalitas_bentuk_strategi_metodepembelajaran' => 'nullable|string',
                'instrumen_penilaian' => 'nullable|string',
                'hasil_belajar' => 'nullable|string',
                'tujuan_belajar_id' => 'nullable|exists:tujuan_belajars,id',
                'cpl_id' => 'nullable|exists:cpls,id',
                'bobot_penilaian' => 'nullable|numeric',
            ]);

            $rps = RpsMatakuliah::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data RPS Mata Kuliah berhasil ditambahkan',
                'data' => $rps
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' =>  $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Mengupdate data RPS Mata Kuliah
     */
    public function update(Request $request, $id)
    {
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
                'minggu' => 'required|integer',
                'pokok_bahasan' => 'required|string',
                'modalitas_bentuk_strategi_metodepembelajaran' => 'nullable|string',
                'instrumen_penilaian' => 'nullable|string',
                'hasil_belajar' => 'nullable|string',
                'tujuan_belajar_id' => 'nullable|exists:tujuan_belajars,id',
                'cpl_id' => 'nullable|exists:cpls,id',
                'bobot_penilaian' => 'nullable|numeric',
            ]);

            $rps->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data RPS Mata Kuliah berhasil diperbarui',
                'data' => $rps
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
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

    public function generateRPSPDF($mataKuliahId)
    {
        $mataKuliah = MataKuliah::where('id', $mataKuliahId)->get();
        $pdf = PDF::loadView('pdf.template', $data);

        return $pdf->download('contoh-pdf.pdf');
    }
}
