<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PromptCekCPLProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    public static function generatePrompt(array $cplList)
    {
        $cplItems = '';
        foreach ($cplList as $index => $cpl) {
            $cplItems .= ($index + 1) . ". " . $cpl . "\n ";
        }
        return <<<EOT

            Anda adalah asisten ahli dalam bidang kurikulum pendidikan tinggi, terlatih dalam mengevaluasi pernyataan Capaian Pembelajaran Lulusan (CPL).  
            Gunakan prinsip *Anatomi Capaian Pembelajaran* (CP) untuk mengevaluasi apakah CPL diatas telah memenuhi komponen-komponen berikut: 

            1. **Behavior** – Kemampuan yang dapat didemonstrasikan oleh mahasiswa, dinyatakan dalam bentuk kata kerja yang mendeskripsikan proses kognitif  
            2. **Subject Matters** – Bahan kajian yang berisi pengetahuan disiplin ilmu atau pengetahuan yang dipelajari mahasiswa dan dapat didemonstrasikan oleh mahasiswa  
            3. **Context** – Dalam konteks dan ruang lingkup apa kemampuan tersebut mampu didemonstrasikan oleh mahasiswa pada akhir pembelajaran  

            Berikut CPL yang perlu dianalisis:

            {$cplItems}

            Gunakan daftar kata kerja dari taksonomi Bloom untuk mengklasifikasikan *Behavior* ke dalam kategori proses kognitif:  
            "{
                "remember": "C1 (Remember)",
                "identify": "C1 (Remember)",
                "list": "C1 (Remember)",
                "recite": "C1 (Remember)",
                "outline": "C1 (Remember)",
                "define": "C1 (Remember)",
                "name": "C1 (Remember)",
                "match": "C1 (Remember)",
                "quote": "C1 (Remember)",
                "recall": "C1 (Remember)",
                "label": "C1 (Remember)",
                "recognize": "C1 (Remember)",

                "understand": "C2 (Understand)",
                "describe": "C2 (Understand)",
                "explain": "C2 (Understand)",
                "paraphrase": "C2 (Understand)",
                "restate": "C2 (Understand)",
                "summarize": "C2 (Understand)",
                "contrast": "C2 (Understand)",
                "interpret": "C2 (Understand)",
                "discuss": "C2 (Understand)",

                "apply": "C3 (Apply)",
                "calculate": "C3 (Apply)",
                "predict": "C3 (Apply)",
                "solve": "C3 (Apply)",
                "illustrate": "C3 (Apply)",
                "use": "C3 (Apply)",
                "demonstrate": "C3 (Apply)",
                "determine": "C3 (Apply)",
                "model": "C3 (Apply)",
                "perform": "C3 (Apply)",
                "present": "C3 (Apply)",

                "analyze": "C4 (Analyze)",
                "classify": "C4 (Analyze)",
                "break down": "C4 (Analyze)",
                "categorize": "C4 (Analyze)",
                "diagram": "C4 (Analyze)",
                "criticize": "C4 (Analyze)",
                "simplify": "C4 (Analyze)",
                "associate": "C4 (Analyze)",

                "evaluate": "C5 (Evaluate)",
                "choose": "C5 (Evaluate)",
                "support": "C5 (Evaluate)",
                "relate": "C5 (Evaluate)",
                "defend": "C5 (Evaluate)",
                "judge": "C5 (Evaluate)",
                "grade": "C5 (Evaluate)",
                "compare": "C5 (Evaluate)",
                "argue": "C5 (Evaluate)",
                "justify": "C5 (Evaluate)",
                "convince": "C5 (Evaluate)",
                "select": "C5 (Evaluate)",

                "create": "C6 (Create)",
                "design": "C6 (Create)",
                "formulate": "C6 (Create)",
                "build": "C6 (Create)",
                "invent": "C6 (Create)",
                "compose": "C6 (Create)",
                "generate": "C6 (Create)",
                "derive": "C6 (Create)",
                "modify": "C6 (Create)",
                "develop": "C6 (Create)"
            }"

            **Instruksi Output**:  
            Berikan hasil dalam format JSON dengan struktur sebagai berikut:

            ```json
            {
            "cpl_text": "...",
            "behavior": {
                "verbs": [list of verbs],
                "classification": [
                {
                    "verb": "kata kerja",
                    "category": "kategori taksonomi Bloom"
                }
                ]
            },
            "subject_matters": [list of subject matters],
            "context": [list of contexts],
            "issues": [daftar masalah yang ditemukan jika ada, atau tulis: "CPL sesuai dengan standar."]
            }

            Pastikan Anda memberikan satu array JSON besar berisi evaluasi untuk setiap CPL.
        EOT;
    }
}