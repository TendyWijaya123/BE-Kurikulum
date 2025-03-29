<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Cache;
use App\Models\Cpl;
use App\Models\Ppm;
use App\Models\Kurikulum;
use App\Providers\PromptProvider;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessProdiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public $tries = 3;
    public $timeout = 300;
    protected $kurikulumId;

    public function __construct($kurikulumId)
    {
        $this->kurikulumId = $kurikulumId;
    }

    public function handle()
    {
        try {
            $kurikulum = Kurikulum::with('prodi')->find($this->kurikulumId);

            if (!$kurikulum) {
                Log::warning("Kurikulum ID {$this->kurikulumId} tidak ditemukan.");
                return;
            }

            $cacheKey = "processed_kurikulum_{$kurikulum->id}";
            if (Cache::has($cacheKey)) {
                return;
            }

            $prodiName = $kurikulum->prodi->name ?? "Unknown_Prodi_{$kurikulum->id}";

            $cpls = Cpl::where('kurikulum_id', $kurikulum->id)
                ->select('id', 'kode', 'keterangan')
                ->orderBy('id', 'asc')
                ->get();

            if ($cpls->isEmpty()) {
                return;
            }

            $prompt = PromptProvider::promptTranslate($cpls);
            $geminiApiKey = env('GEMINI_API_KEY');

            $response = Http::retry(3, 2000)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$geminiApiKey}", [
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

            $flaskResponse = Http::post("http://127.0.0.1:5000/analyze", $formattedData);
            $flaskResults = $flaskResponse->json()['results'] ?? [];

            foreach ($cpls as $index => $cpl) {
                $issues = $flaskResults[$index]['issues'] ?? [];
                $cpl->issues = is_array($issues) ? implode(', ', $issues) : $issues;
            }

            $ppms = Ppm::where('kurikulum_id', $kurikulum->id)
                ->select('id', 'kode', 'deskripsi')
                ->get();

            $result = [
                $prodiName => [
                    'kurikulum' => [
                        'id' => $kurikulum->id,
                        'tahun_awal' => $kurikulum->tahun_awal,
                        'tahun_akhir' => $kurikulum->tahun_akhir,
                    ],
                    'cpls' => $cpls,
                    'ppms' => $ppms,
                ]
            ];

            Cache::put($cacheKey, $result, now()->addMinutes(30));
        } catch (\Exception $e) {
            Log::error("Error di ProcessProdiJob untuk Kurikulum ID {$this->kurikulumId}: " . $e->getMessage());
            $this->fail($e);
        }
    }
}
