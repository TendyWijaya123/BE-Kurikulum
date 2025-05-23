<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Cache;
use App\Models\Cpl;
use App\Models\Ppm;
use App\Models\Kurikulum;
use App\Providers\PromptCekCPLProvider;
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

            $cplList = $cpls->pluck('keterangan')->toArray();

            $prompt = PromptCekCPLProvider::generatePrompt($cplList);
            $geminiApiKey = env('GEMINI_API_KEY');

            $response = Http::retry(3, 2000)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$geminiApiKey}", [
                "contents" => [
                    [
                        "parts" => [
                            ["text" => $prompt]
                        ]
                    ]
                ]
            ]);


            $result = $response->json();
            $cplTexts = [];

            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $rawText = $result['candidates'][0]['content']['parts'][0]['text'];

                // Hapus blok kode ```json ... ```
                $cleaned = preg_replace('/^```json\s*|\s*```$/', '', trim($rawText));

                // Decode JSON ke array
                $parsed = json_decode($cleaned, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($parsed)) {
                    $structuredCPLs = [];

                    foreach ($parsed as $item) {
                        $structuredCPLs[] = [
                            'cpl_text' => $item['cpl_text'] ?? '',
                            'behavior' => [
                                'verbs' => $item['behavior']['verbs'] ?? [],
                                'classification' => $item['behavior']['classification'] ?? []
                            ],
                            'subject_matters' => $item['subject_matters'] ?? [],
                            'context' => $item['context'] ?? [],
                            'issues' => $item['issues'] ?? ["CPL sesuai dengan standar."]
                        ];
                    }

                    // Sekarang $structuredCPLs sudah siap dipakai
                } else {
                    // Tangani kesalahan decoding JSON
                    Log::error('Gagal decode CPL response dari Gemini:', ['raw' => $cleaned]);
                }
            }


            // $flaskUrl = env('FLASK_URL');
    
            // //api untuk pengecekan cpl menggunakan dependency parsing menggunakan framework flask
            // $flaskResponse = Http::post($flaskUrl, $formattedData);
            // $flaskResults = $flaskResponse->json()['results'] ?? [];

            foreach ($cpls as $index => $cpl) {
                $issues = $structuredCPLs[$index]['issues'] ?? [];
                $cpl->issues = is_array($issues) ? implode(', ', $issues) : $issues;
            }

            $ppms = Ppm::where('kurikulum_id', $kurikulum->id)
                ->select('id', 'kode', 'deskripsi')
                ->get();

            $resultFormat = [
                $prodiName => [
                    'kurikulum' => [
                        'id' => $kurikulum->id,
                        'tahun_awal' => $kurikulum->tahun_awal,
                        'tahun_akhir' => $kurikulum->tahun_akhir,
                    ],
                    'cpls' => $cpls,
                    'ppms' => $ppms,
                    'prompt' => $prompt
                ]
            ];

            Cache::put($cacheKey, $resultFormat, now()->addMinutes(60));
        } catch (\Exception $e) {
            Log::error("Error di ProcessProdiJob untuk Kurikulum ID {$this->kurikulumId}: " . $e->getMessage());
            $this->fail($e);
        }
    }
}
