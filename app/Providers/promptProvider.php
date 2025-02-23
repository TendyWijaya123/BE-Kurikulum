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

        if (
            $siapKerja->isEmpty() || 
            $siapUsaha->isEmpty() || 
            $benchCurriculum->isEmpty() || 
            $ipteksPengetahuan->isEmpty() || 
            $ipteksSeni->isEmpty() || 
            $ipteksTeknologi->isEmpty()
        ) {
            return "analisis konsideran belum lengkap";
        } 

        // Ambil nama prodi untuk prompt
        $prodi = Prodi::find($prodiId);
        $prodiName = $prodi ? $prodi->nama : 'Prodi Tidak Diketahui';

        $prompt = "**Tujuan** : Rancangan CPL \n **Dasar Analisis** : terdapat minimal empat konsiderans yang harus dianalisis secara komprehensif untuk menghasilkan rancangan CPL, yaitu konsiderans Siap Kerja atau Siap Usaha, Kajian Banding Kurikulum, perkembangan IPTEKS terkini, dan deskripsi jenjang KKNI, Data tersebut dibawah  ini \n";

        // Format hasil dalam bentuk markdown untuk prompt
        $prompt .= "### Analisis Capaian Pembelajaran Lulusan ($prodiName)\n\n";

        $prompt .= "**1. SKSU (Satuan Kredit Semester Universitas)**\n**Siap Kerja**\n profil lulusan, kualifikasi, kategori, kompetensi_kerja\n";
        foreach ($siapKerja as $item) {
            $prompt .= "- {$item->profil_lulusan}, {$item->kualifikasi}, {$item->kategori}, {$item->kompetensi_kerja}  \n";
        }

        $prompt .= "**Siap Usaha**\n profil lulusan, kualifikasi, kategori, kompetensi_kerja\n";
        foreach ($siapUsaha as $item) {
            $prompt .= "- {$item->profil_lulusan}, {$item->kualifikasi}, {$item->kategori}, {$item->kompetensi_kerja}  \n";
        }
        
        $prompt .= "\n**2. Kurikulum Pembanding**\n program studi, kategori, cpl, ppm";
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

        $prompt .= "**4. Analisis Konsideran KKNI**\n **Kata Kunci Pengetahuan dalam KKNI** \n";

        $pengetahuanKkni = PengetahuanKKNI::where('id', $pengetahuanId)
        ->value('pengetahuan_kkni');

        $kemampuanKerjaKkni = KemampuanKerjaKKNI::where('id', $kemampuanKerjaId)
        ->value('kemampuan_kerja_kkni');

        $prompt .= "{$pengetahuanKkni}\n\n";
        $prompt .= "**Kata Kunci Kemampuan kerja dalam KKNI**\n{$kemampuanKerjaKkni}\n";

        $prompt .= "**Format Hasil** : kode + Deskripsi  \n\n";
        $prompt .= "**Deskripsi terdiri dari:**\n";
        $prompt .= "- **Behavior**: Kemampuan yang dapat didemonstrasikan oleh mahasiswa, dinyatakan dalam bentuk kata kerja yang mendeskripsikan proses kognitif.\n";
        $prompt .= "- **Subject Matters**: Bahan kajian yang berisi pengetahuan disiplin ilmu atau pengetahuan yang dipelajari mahasiswa dan dapat didemonstrasikan oleh mahasiswa.\n";
        $prompt .= "- **Context**: Dalam konteks dan ruang lingkup apa kemampuan tersebut mampu didemonstrasikan oleh mahasiswa pada akhir pembelajaran.\n\n";
        $prompt .= "**Format Hasil Akhir** : kode CPL, Deskripsi (Behavior + Subject Matters + Context) \n\n output harus singkat padat jelas dan berupa objek json : \n";
        $prompt .= "[{
            kode : CPL-1,
            deskripsi : Behavior(awali dengan kata mampu) + Subject Matters + Context
        },
            kode : CPL-2,
            deskripsi : Behavior + Subject Matters + Context
        ]";

        return $prompt;
    }
}
