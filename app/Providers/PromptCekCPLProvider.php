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

    public static function generatePrompt(array $cplList, array $cplIndo)
    {
        $cplItems = '';
        foreach ($cplList as $index => $cpl) {
            $cplItems .= ($index + 1) . ". " . $cpl . "\n";
        }

        $cplItemsIndo = ''; // <-- ganti nama variabel
        foreach ($cplIndo as $index => $cpl) {
            $cplItemsIndo .= ($index + 1) . ". " . $cpl . "\n";
        }
        return <<<EOT

            Anda adalah asisten ahli dalam bidang kurikulum pendidikan tinggi, terlatih dalam mengevaluasi pernyataan Capaian Pembelajaran Lulusan (CPL).  
            Gunakan prinsip *Anatomi Capaian Pembelajaran* (CP) untuk mengevaluasi apakah CPL diatas telah memenuhi komponen-komponen berikut: 

            1. **Behavior** – Kemampuan yang dapat didemonstrasikan oleh mahasiswa, dinyatakan dalam bentuk kata kerja yang mendeskripsikan proses kognitif  
            2. **Subject Matters** – Bahan kajian yang berisi pengetahuan disiplin ilmu atau pengetahuan yang dipelajari mahasiswa dan dapat didemonstrasikan oleh mahasiswa  
            3. **Context** – Dalam konteks dan ruang lingkup apa kemampuan tersebut mampu didemonstrasikan oleh mahasiswa pada akhir pembelajaran  

            Berikut CPL yang perlu dianalisis:

            Versi Indonesia
            {$cplItemsIndo}

            Versi Inggis
            {$cplItems}

            Gunakan daftar kata kerja dari taksonomi Bloom untuk mengklasifikasikan *Behavior* ke dalam kategori proses kognitif : 
            Catatan gunakan cpl versi inggris

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

            **Instruksi Proses Penalaran (Step-by-Step Reasoning Template):**

            Untuk setiap CPL dalam daftar, ikuti langkah-langkah penalaran berikut secara berurutan:

            1.  **Ekstraksi Komponen (Behavior, Subject Matters, Context):**
                * Baca dan analisis CPL (Gunakan **versi Bahasa Indonesia** sebagai acuan utama untuk ekstraksi ini).
                * Identifikasi dan ekstrak kata kerja utama yang merepresentasikan **Behavior**.
                * Identifikasi dan ekstrak frasa atau kata kunci yang mewakili **Subject Matters**.
                * Identifikasi dan ekstrak frasa yang menjelaskan **Context** (lingkup atau kondisi).

            2.  **Klasifikasi Behavior (Menggunakan CPL Inggris & Taksonomi Bloom):**
                * Ambil kata kerja (Behavior) yang telah Anda ekstrak dari CPL Bahasa Indonesia.
                * Cari padanan kata kerja tersebut dalam CPL **versi Bahasa Inggris** (jika ada, atau gunakan terjemahan akurat jika tidak ada padanan langsung di CPL Inggris) dan gunakan versi Inggris tersebut untuk mengklasifikasikannya berdasarkan daftar Taksonomi Bloom yang disediakan.
                * Catat kategori Taksonomi Bloom (C1-C6) yang sesuai.

            3.  **Evaluasi Kualitas CPL (Cross-Check):**
                * Periksa apakah ketiga komponen (Behavior, Subject Matters, Context) **hadir, jelas, spesifik, dan saling terkait** dalam CPL.
                * Nilai apakah CPL tersebut secara keseluruhan memenuhi standar Anatomi Capaian Pembelajaran.
                * **Kriteria "Issues":**
                    * Komponen utama (Behavior, Subject Matters, Context) **hilang atau sangat implisit** sehingga sulit diidentifikasi.
                    * **Ambiguitas tinggi:** Salah satu atau lebih komponen tidak jelas atau dapat diinterpretasikan secara berbeda.
                    * **Ketidaksesuaian:** Behavior tidak relevan dengan Subject Matters atau Context, atau sebaliknya.
                    * **Duplikasi atau redundansi yang tidak perlu.**
                * **Kriteria "CPL sesuai dengan standar" atau "masalah masih bisa ditoleransi":**
                    * Semua komponen hadir dan jelas.
                    * Jika ada sedikit ketidakjelasan, tetapi inti CPL tetap dapat dipahami dan memenuhi tujuan. Contoh: "Behavior jelas tetapi Context sedikit umum namun masih relevan." (Sebutkan alasan spesifik toleransi).

            4.  **Penyusunan Saran Perbaikan:**
                * Jika ada "issues" yang ditemukan, berikan saran perbaikan yang **spesifik dan konstruktif**.
                * Saran harus berupa kalimat CPL yang **direvisi** untuk mengatasi masalah yang teridentifikasi, mengikuti format Anatomi CP.
                * Jika tidak ada masalah, tulis "tidak ada saran perbaikan".

            **Instruksi Output:**

            Berikan hasil evaluasi untuk setiap CPL dalam format array JSON. Pastikan setiap objek JSON dalam array mewakili satu CPL yang dievaluasi. Gunakan **CPL versi Bahasa Indonesia** untuk `cpl_text` dan detail `subject_matters`, `context`, `issues`, dan `saran_perbaikan`. Untuk `behavior.classification`, gunakan hasil klasifikasi dari **CPL versi Bahasa Inggris** yang telah Anda lakukan pada langkah 2.
            catatan : gunakan cpl bahasa indonesia untuk cpl_text, output behavior, subject_matters, context, dan saran_perbaikan, gunakan cpl bahasa inggris untuk behavior

            ```json
            {
            "cpl_text": "CPL yang diberikan, dengan menandai secara eksplisit bagian behavior, subject matters, dan context menggunakan format: (Behavior: [kata kerja]) (Subject Matters: [bahan kajian]) (Context: [konteks])",
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
            "issues": [daftar masalah yang ditemukan jika ada antara behavior, subject matters, atau context tidak memenuhi standar, atau tulis: "CPL sesuai dengan standar. jika tidak ada masalah atau masalah masih bisa di toleransi (sebutkan alasan)"],
            "saran_perbaikan": "kalimat cpl (saran perbaikan cpl jika ada sesuai dengan issues nya, atau "tidak ada saran perbaikan" jika tidak ada masalah)"
            }
            ```
            }

            Pastikan Anda memberikan satu array JSON besar berisi evaluasi untuk setiap CPL.
        EOT;
    }
}