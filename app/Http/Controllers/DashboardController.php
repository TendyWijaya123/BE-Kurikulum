<?php

namespace App\Http\Controllers;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Cpl;
use App\Models\Ppm;
use App\Models\VmtJurusan;
use App\Models\Pengetahuan;
use App\Models\MateriPembelajaran;
use App\Providers\PromptProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function getJurusans()
    {
        $jurusans = Jurusan::all();
        return response()->json($jurusans);
    }

    public function getProdis(Request $request)
    {
        $prodis = Prodi::all();
        return response()->json($prodis);
    }

    public function getCurriculumData($id)
    {
        // Get the latest kurikulum for the prodi
        $kurikulum = Prodi::find($id)
            ->kurikulums()
            ->latest()
            ->first();

        if (!$kurikulum) {
            return response()->json([
                'message' => 'No curriculum found for this program'
            ], 404);
        }

        // Get CPLs
        $cpls = Cpl::where('kurikulum_id', $kurikulum->id)
            ->select('id', 'kode', 'keterangan')->orderBy('id', 'asc') 
            ->get();
        
        $prompt = PromptProvider::promptTranslate($cpls);
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
        // Ambil hasil dari API Gemini
        $translatedText = $response->json();

        // Ambil teks dari bagian response
        $cplTexts = [];
        if (isset($translatedText['candidates'][0]['content']['parts'][0]['text'])) {
            $rawText = $translatedText['candidates'][0]['content']['parts'][0]['text'];
            
            // Pisahkan teks berdasarkan baris kosong sebagai pemisah antar CPL
            $cplTexts = array_filter(array_map('trim', explode("\n\n", $rawText)));
        }

        // Format ulang hasil untuk dikirim ke API Flask
        $formattedData = [
            "cpl_texts" => array_values($cplTexts)
        ];

        $flaskResponse = Http::post("http://127.0.0.1:5000/analyze", $formattedData);

        $flaskResults = $flaskResponse->json()['results'] ?? [];

        foreach ($cpls as $index => $cpl) {
            $issues = $flaskResults[$index]['issues'] ?? [];
            $cpl->issues = is_array($issues) ? implode(', ', $issues) : $issues;
        }

        // Get PPMs
        $ppms = Ppm::where('kurikulum_id', $kurikulum->id)
            ->select('id', 'kode', 'deskripsi')
            ->get();

        // Get Visi Misi
        $visiMisi = VmtJurusan::where('kurikulum_id', $kurikulum->id)
            ->with('misiJurusans')
            ->first();

        // Get Pengetahuan
        $pengetahuan = Pengetahuan::where('kurikulum_id', $kurikulum->id)
            ->select('id', 'kode_pengetahuan', 'deskripsi')
            ->get();

        // Get Materi Pembelajaran
        $materiPembelajaran = MateriPembelajaran::with('knowledgeDimension')->where('kurikulum_id', $kurikulum->id)
            ->select('id', 'code', 'description', 'cognitif_proses')
            ->get();

        return response()->json([
            'cpls' => $cpls,
            'ppms' => $ppms,
            'visi_misi' => $visiMisi,
            'pengetahuan' => $pengetahuan,
            'materi_pembelajaran' => $materiPembelajaran,
            'kurikulum' => [
                'id' => $kurikulum->id,
                'tahunAwal' => $kurikulum->tahun_awal,
                'tahunAkhir' => $kurikulum->tahun_akhir,
            ]
        ]);
    }
}
