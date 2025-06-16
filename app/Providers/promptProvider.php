<?php

namespace App\Providers;

use App\Models\CplKkni;
use App\Models\IpteksPengetahuan;
use App\Models\IpteksSeni;
use App\Models\IpteksTeknologi;
use App\Models\KemampuanKerjaKKNI;
use Illuminate\Support\ServiceProvider;
use App\Models\Prodi;
use App\Models\Sksu;
use App\Models\BenchKurikulum;
use App\Models\Ipteks;
use App\Models\PengetahuanKKNI;

class PromptProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Generate prompt berdasarkan prodi_id
     */
    public static function generatePrompt($prodiId, $pengetahuanId, $kemampuanKerjaId): string
    {
        $siapKerja = Sksu::whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId)->where('is_active', true);
        })->where('kategori', 'Siap Kerja')->get();

        $siapUsaha = Sksu::whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId)->where('is_active', true);
        })->where('kategori', 'Siap Usaha')->get();

        $benchCurriculum = BenchKurikulum::whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId)->where('is_active', true);
        })->get();

        $ipteksPengetahuan = IpteksPengetahuan::whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId)->where('is_active', true);
        })->get();

        $ipteksTeknologi = IpteksTeknologi::whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId)->where('is_active', true);
        })->get();

        $ipteksSeni = IpteksSeni::whereHas('kurikulum', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId)->where('is_active', true);
        })->get();

        // Cek yang kosong
        $kosong = [];

        if ($siapKerja->isEmpty() && $siapUsaha->isEmpty()) {
            $kosong[] = 'Siap Kerja & Siap Usaha (keduanya kosong)';
        }
        if ($benchCurriculum->isEmpty()) $kosong[] = 'Benchmark Kurikulum';
        if ($ipteksPengetahuan->isEmpty()) $kosong[] = 'Ipteks Pengetahuan';
        if ($ipteksTeknologi->isEmpty()) $kosong[] = 'Ipteks Teknologi';
        if ($ipteksSeni->isEmpty()) $kosong[] = 'Ipteks Seni';


        if (!empty($kosong)) {
            return 'Analisis konsideran belum lengkap: ' . implode(', ', $kosong);
        }

        // Ambil nama prodi untuk prompt
        $prodi = Prodi::find($prodiId);
        $prodiName = $prodi ? $prodi->nama : 'Prodi Tidak Diketahui';

        // --- AWAL PENERAPAN PE2: DETAILED DESCRIPTION & CONTEXT SPECIFICATION ---
// ...existing code...
        $prompt = <<<EOT
        Anda adalah seorang **Validator Kurikulum Ahli** dan **Perancang Capaian Pembelajaran Lulusan (CPL) yang Berpengalaman**. Tugas Anda adalah menganalisis data konsideran yang komprehensif dan menghasilkan rancangan CPL baru yang akurat, spesifik, dan sesuai dengan standar pendidikan tinggi, khususnya untuk program studi **{$prodiName}** jenjang D3. Fokus utama adalah mengintegrasikan seluruh konsideran yang diberikan ke dalam formulasi CPL yang koheren.

        **Tujuan**: Merancang Capaian Pembelajaran Lulusan (CPL) untuk program studi D3 Teknik Komputer Informatika {$prodiName} berdasarkan analisis mendalam terhadap konsideran yang disediakan.

        **Dasar Analisis**: Anda harus menganalisis secara komprehensif empat kelompok konsideran utama yang disediakan di bawah ini untuk menghasilkan rancangan CPL. Konsideran ini mencakup:
        1.  **SKSU (Siap Kerja atau Siap Usaha):** Profil lulusan, kualifikasi, kategori, dan kompetensi kerja yang relevan dengan kebutuhan dunia kerja dan wirausaha.
        2.  **Kajian Banding Kurikulum:** CPL dan mata kuliah dari program studi pembanding untuk mengidentifikasi praktik terbaik dan celah kompetensi.
        3.  **Perkembangan IPTEKS Terkini:** Pengetahuan, teknologi, dan seni terbaru yang relevan dengan bidang Teknik Komputer Informatika.
        4.  **Deskripsi Jenjang KKNI:** Kata kunci pengetahuan dan kemampuan kerja sesuai level KKNI D3 untuk memastikan keselarasan dengan standar nasional.

        **Definisi Struktur CPL (Anatomi Capaian Pembelajaran):**
        Setiap CPL yang Anda hasilkan harus secara eksplisit memenuhi komponen-komponen berikut:
        -   **Behavior (Tingkah Laku/Kemampuan):** Kata kerja yang mendeskripsikan tindakan atau proses kognitif yang dapat secara langsung didemonstrasikan atau diukur oleh mahasiswa pada akhir pembelajaran. Contoh: "Menganalisis", "Menerapkan", "Mendesain".
        -   **Subject Matters (Materi Pokok/Bahan Kajian):** Pengetahuan disiplin ilmu, konsep, teori, atau keterampilan spesifik yang dipelajari dan dikuasai oleh mahasiswa, di mana behavior tersebut diaplikasikan. Contoh: "Algoritma machine learning", "Prinsip-prinsip jaringan komputer".
        -   **Context (Konteks/Lingkup):** Kondisi, batasan, lingkungan, atau ruang lingkup spesifik di mana mahasiswa diharapkan mampu mendemonstrasikan behavior dengan subject matters tertentu. Contoh: "dalam pengembangan aplikasi berbasis web", "menggunakan perangkat lunak simulasi".
        ---
        EOT;
// ...existing code... // Menutup bagian intro PE2

        // --- DATA KONSIDERAN (Bagian yang sudah ada) ---
        $prompt .= "### Data Konsideran untuk Program Studi {$prodiName}\n\n";

        $prompt .= "**1. SKSU (Satuan Kredit Semester Universitas)**\n";
        if (!$siapKerja->isEmpty()) {
            $prompt .= "**Siap Kerja**\n Profil Lulusan, Kualifikasi, Kategori, Kompetensi Kerja\n";
            foreach ($siapKerja as $item) {
                $prompt .= "- {$item->profil_lulusan}, {$item->kualifikasi}, {$item->kategori}, {$item->kompetensi_kerja}\n";
            }
        }
        if (!$siapUsaha->isEmpty()) {
            $prompt .= "**Siap Usaha**\n Profil Lulusan, Kualifikasi, Kategori, Kompetensi Kerja\n";
            foreach ($siapUsaha as $item) {
                $prompt .= "- {$item->profil_lulusan}, {$item->kualifikasi}, {$item->kategori}, {$item->kompetensi_kerja}\n";
            }
        }
        
        $prompt .= "\n**2. Kurikulum Pembanding**\n Program Studi, Kategori, CPL, PPM\n";
        foreach ($benchCurriculum as $item) {
            $prompt .= "- {$item->program_studi}, {$item->kategori}, {$item->cpl}, {$item->ppm}\n";
        }

        $prompt .= "\n**3. IPTEKS Berdasarkan Kategori**\n";
        
        $prompt .= "\n- **Ilmu Pengetahuan:**\n";
        foreach ($ipteksPengetahuan as $item) {
            $prompt .= "  - {$item->deskripsi}\n";
        }

        $prompt .= "\n- **Teknologi:**\n";
        foreach ($ipteksTeknologi as $item) {
            $prompt .= "  - {$item->deskripsi}\n";
        }

        $prompt .= "\n- **Seni:**\n";
        foreach ($ipteksSeni as $item) {
            $prompt .= "  - {$item->deskripsi}\n";
        }

        $prompt .= "\n**4. Analisis Konsideran KKNI**\n";
        $pengetahuanKkni = PengetahuanKKNI::where('id', $pengetahuanId)
        ->value('pengetahuan_kkni');

        $kemampuanKerjaKkni = KemampuanKerjaKKNI::where('id', $kemampuanKerjaId)
        ->value('kemampuan_kerja_kkni');

        $prompt .= "**Kata Kunci Pengetahuan dalam KKNI:**\n{$pengetahuanKkni}\n\n";
        $prompt .= "**Kata Kunci Kemampuan Kerja dalam KKNI:**\n{$kemampuanKerjaKkni}\n";

        // --- AKHIR PENERAPAN PE2: STEP-BY-STEP REASONING TEMPLATE & STRUCTURED OUTPUT ---
        $prompt .= "
        ---
        **Instruksi Proses Penalaran (Step-by-Step Reasoning Template):**

        Ikuti langkah-langkah berikut untuk menghasilkan setiap CPL:

        1.  **Integrasi Konsideran:** Gabungkan dan sintesis informasi dari semua konsideran (SKSU, Kurikulum Pembanding, IPTEKS, dan KKNI). Identifikasi benang merah, kebutuhan kompetensi yang berulang, serta tren terbaru.
        2.  **Formulasi Ide CPL:** Berdasarkan integrasi konsideran, rumuskan ide-ide CPL awal yang mencerminkan kompetensi yang diharapkan.
        3.  **Strukturisasi CPL dengan Anatomi CP:** Untuk setiap ide CPL, pecah dan formulasikan menjadi tiga komponen wajib:
            * Identifikasi **Behavior** (kata kerja aksi kognitif) yang paling tepat dari kompetensi yang ingin dicapai.
            * Identifikasi **Subject Matters** (materi inti atau bidang keilmuan) yang menjadi fokus behavior tersebut.
            * Identifikasi **Context** (lingkup atau kondisi spesifik) di mana behavior dan subject matters diaplikasikan. Pastikan konteks ini relevan dengan jenjang { $prodi->jenjang $prodiName}
        4.  **Penomoran CPL:** Berikan kode CPL secara berurutan dimulai dari CPL-1, CPL-2, dst.

        **Format Hasil Akhir (Strict JSON Output):**

        Output Anda harus berupa objek JSON yang valid dan ringkas, tidak ada teks tambahan di luar JSON. Setiap elemen dalam array JSON harus mewakili satu CPL yang dirancang.

        ```json
        [
            {
                \"kode\": \"CPL-1\",
                \"deskripsi\": \"Teks Behavior + Teks Subject Matters + Teks Context\"
            },
            {
                \"kode\": \"CPL-2\",
                \"deskripsi\": \"Teks Behavior + Teks Subject Matters + Teks Context\"
            }
            // ... CPL-CPL selanjutnya ...
        ]
        ```
        "; // Menutup bagian PE2

        return $prompt;
    }


    public static function promptTranslate($cpl){
        // Ambil hanya data yang diperlukan (keterangan)
        $data = $cpl->pluck('keterangan')->toArray();
        
        // Gabungkan semua keterangan menjadi satu teks dengan pemisah newline
        $text = implode("\n", $data);
        
        // Buat prompt untuk GPT
        $prompt = "Terjemahkan teks berikut ke dalam bahasa Inggris:\n\n" . $text;
        
        return $prompt;
    }
}