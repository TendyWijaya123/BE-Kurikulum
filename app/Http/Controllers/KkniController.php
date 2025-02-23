<?php

namespace App\Http\Controllers;

use App\Exports\KkniTemplateExport;
use App\Imports\KkniImport;
use App\Models\BenchKurikulum;
use App\Models\Ipteks;
use App\Models\KemampuanKerjaKKNI;
use App\Models\PengetahuanKKNI;
use App\Models\Sksu;
use Illuminate\Http\Request;
use App\Models\CplKkni as ModelKkni;
use App\Models\Kurikulum;
use App\Providers\PromptProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class KkniController extends Controller
{
    public function index(Request $request)
    {
        $pengetahuan = PengetahuanKKNI::all();
        $kemampuanKerja = KemampuanKerjaKKNI::all();
        $prodiId = $request->query('prodiId');
        $kkni = ModelKkni::whereHas('kurikulum', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId)->where('is_active', true);
            })
            ->get();

        return response()
        ->json([
            'kkni' => $kkni,
            'pengetahuan' => $pengetahuan,
            'kemampuanKerja' => $kemampuanKerja,
        ]);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $dataList = $request->input('dataSource', []);
            $kurikulumId = Kurikulum::where('prodi_id', $dataList[0]['prodiId'])
                ->where('is_active', true)
                ->value('id');

            $selectedPengetahuan = $request['selectedPengetahuan'] ?? null;
            $selectedKemampuanKerja = $request['selectedKemampuanKerja'] ?? null;

            if (!$kurikulumId) {
                return response()->json([
                    'message' => "Kurikulum aktif tidak ditemukan untuk prodi_id: {$request[0]['prodiId']}",
                ], 404);
            }

            foreach ($dataList as $data) {
                ModelKkni::updateOrCreate(
                    ['id' => $data['_id'] ?? null],
                    [
                        'code' => $data['code'],
                        'description' => $data['description'],
                        'kurikulum_id' => $kurikulumId,
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => 'Data berhasil disimpan',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $sksu = ModelKkni::find($id);
        if (!$sksu) {
            return response()->json([
                'message' => 'sksu not found.',
            ], 404);
        }
        // Hapus data SKSU
        $sksu->delete();

        return response()->json([
            'message' => `sksu berhasil dihapus`
        ], 200);
    }

    public function destroyCpkKknis(Request $request)
    {
        try {

            DB::beginTransaction();

            // Ambil daftar ID dari request
            $data = $request->all();

            if (empty($data) || !is_array($data)) {
                return response()->json([
                    'data' => $data,
                    'message' => 'Harap sertakan daftar ID yang valid untuk dihapus',
                ], 400);
            }

            $ids = array_column($data, '_id');

            // Cari SKSU berdasarkan ID
            $kkni = ModelKkni::whereIn('id', $ids)->get();

            if ($kkni->isEmpty()) {
                return response()->json([
                    'data' => $ids,
                    'message' => 'Data tidak ditemukan untuk ID yang diberikan',
                ], 404);
            }

            // Loop untuk menghapus data terkait, jika ada
            foreach ($kkni as $cpl) {
                // Hapus data SKSU
                $cpl->delete();
            }

            DB::commit();

            return response()->json([
                'message' => 'Data berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'data' => $ids,
                'message' => 'Terjadi kesalahan saat menghapus data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        try {
            Excel::import(new KkniImport, $request->file('file'));
            return response()->json(['message' => 'Data berhasil diimport.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function downloadTemplate()
    {
        $fileName = 'Kkni_template.xlsx';

        return Excel::download(new KkniTemplateExport, $fileName);
    }

    public function autoCpl(Request $request){
        // dd($request);
        $prodiId = $request->query('prodiId');

        $prompt = PromptProvider::generatePrompt($prodiId, $request->query('pengatahuanId'), $request->query('kemampuanKerjaId'));
        if($prompt == "analisis konsideran belum lengkap") {
            return response()->json([
                'warning' => 'analisis konsideran belum lengkap'
            ]);
        };
        
        $geminiApiKey = env('GEMINI_API_KEY'); // Simpan API Key di .env
        $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$geminiApiKey}", [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ]
        ]);

        // Ambil respons dari Gemini API
        $geminiResult = $response->json();
        // Ambil teks JSON dari respons
        $jsonText = $geminiResult['candidates'][0]['content']['parts'][0]['text'] ?? '';

        // Bersihkan format teks, hapus ```json dan ```
        $jsonText = preg_replace('/```json|```/', '', trim($jsonText));

        // // Konversi JSON menjadi array PHP
        $dataArray = json_decode($jsonText, true);

        return response()->json(['data' => $dataArray]);
    }
}
