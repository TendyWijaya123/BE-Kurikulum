<?php

namespace App\Http\Controllers;

use App\Exports\KkniTemplateExport;
use App\Http\Requests\UpsertKKNIRequest;
use App\Imports\KkniImport;
use App\Models\BenchKurikulum;
use App\Models\Ipteks;
use App\Models\KemampuanKerjaKKNI;
use App\Models\PengetahuanKKNI;
use App\Models\Sksu;
use Illuminate\Http\Request;
use App\Models\CplKkni as ModelKkni;
use App\Models\Kurikulum;
use App\Models\Prodi;
use App\Providers\PromptProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class KkniController extends Controller
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
            return response()->json(['status' => 'Tidak ada kurikulum  yang aktif pada prodi ini'], 404);
        }
        $pengetahuan = PengetahuanKKNI::all();
        $kemampuanKerja = KemampuanKerjaKKNI::all();
        $kkni = ModelKkni::where("kurikulum_id", $activeKurikulum->id)
            ->get();

        return response()
            ->json([
                'kkni' => $kkni,
                'pengetahuan' => $pengetahuan,
                'kemampuanKerja' => $kemampuanKerja,
            ]);
    }

    public function store(UpsertKKNIRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $dataList = $validated['dataSource'];
            
            $kurikulumId = Kurikulum::where('prodi_id', $dataList[0]['prodiId'])
                ->where('is_active', true)
                ->value('id');

            if (!$kurikulumId) {
                return response()->json([
                    'message' => "Kurikulum aktif tidak ditemukan untuk prodi_id: {$dataList[0]['prodiId']}",
                ], 404);
            }

            $savedItems = [];

            foreach ($dataList as $data) {
                $item = ModelKkni::updateOrCreate(
                    ['id' => $data['_id'] ?? null],
                    [
                        'code' => $data['code'],
                        'description' => $data['description'],
                        'kurikulum_id' => $kurikulumId
                    ]
                );
                $savedItems[] = $item;
            }

            DB::commit();

            return response()->json([
                'message' => 'Data berhasil disimpan.',
                'data' => $savedItems
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

    public function autoCpl(Request $request)
    {
        $prodiId = $request->query('prodiId');

        $prompt = PromptProvider::generatePrompt($prodiId, $request->query('pengatahuanId'), $request->query('kemampuanKerjaId'));
        if (Str::startsWith($prompt, 'Analisis konsideran belum lengkap')) {
            return response()->json([
                'warning' => $prompt
            ]);
        }

        $geminiApiKey = env('GEMINI_API_KEY'); // Simpan API Key di .env
        $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$geminiApiKey}", [
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
