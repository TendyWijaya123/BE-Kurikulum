<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Cache;
use App\Models\Cpl;
use App\Models\Ppm;
use App\Providers\PromptProvider;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessProdiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable ;

    protected $kurikulum;

    public function __construct($kurikulum)
    {
        $this->kurikulum = $kurikulum;
    }

    public function handle()
    {
        // Ambil nama prodi dari kurikulum
        $prodiName = Cpl::join('kurikulums', 'cpls.kurikulum_id', '=', 'kurikulums.id')
            ->join('prodis', 'kurikulums.prodi_id', '=', 'prodis.id')
            ->where('cpls.kurikulum_id', $this->kurikulum->id)
            ->select('prodis.name', 'prodis.kode') // 🔹 Ambil nama prodi
            ->first()
            ?->name ?? "Unknown_Prodi_{$this->kurikulum->id}";

        // Ambil data CPL
        $cpls = Cpl::where('kurikulum_id', $this->kurikulum->id)
            ->select('id', 'kode', 'keterangan')
            ->orderBy('id', 'asc')
            ->get();
        
        // Ambil data lainnya
        $ppms = Ppm::where('kurikulum_id', $this->kurikulum->id)
            ->select('id', 'kode', 'deskripsi')
            ->get();
        
        if ($cpls->isEmpty() && $ppms->isEmpty()){
            return;
        }

        if ($cpls->isNotEmpty()) {
            $prompt = PromptProvider::promptTranslate($cpls);
            $geminiApiKey = env('GEMINI_API_KEY');
    
            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$geminiApiKey}", [
                "contents" => [
                    [
                        "parts" => [
                            ["text" => $prompt]
                        ]
                    ]
                ]
            ]);
    
            $translatedText = $response->json();
            $cplTexts = [];
    
            if (isset($translatedText['candidates'][0]['content']['parts'][0]['text'])) {
                $rawText = $translatedText['candidates'][0]['content']['parts'][0]['text'];
                $cplTexts = array_filter(array_map('trim', explode("\n\n", $rawText)));
            }
    
            $formattedData = [
                "cpl_texts" => array_values($cplTexts)
            ];
    
            $flaskResponse = Http::post("http://localhost:5000/analyze", $formattedData);
            $flaskResults = $flaskResponse->json()['results'] ?? [];
    
            foreach ($cpls as $index => $cpl) {
                $issues = $flaskResults[$index]['issues'] ?? [];
                $cpl->issues = is_array($issues) ? implode(', ', $issues) : $issues;
            }
        }
        
        $result = [
            $prodiName => [
                'kurikulum' => [
                    'id' => $this->kurikulum->id,
                    'tahun_awal' => $this->kurikulum->tahun_awal,
                    'tahun_akhir' => $this->kurikulum->tahun_akhir,
                ],
                'cpls' => $cpls,
                'ppms' => $ppms,
            ]
        ];

        // Simpan ke cache
        Cache::put("processed_kurikulum_{$this->kurikulum->id}", $result, now()->addMinutes(60));
    }
}
