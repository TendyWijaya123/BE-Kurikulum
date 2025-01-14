<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kurikulum;
use App\Models\IpteksPengetahuan;
use App\Models\IpteksTeknologi;
use App\Models\IpteksSeni;

class IpteksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Menggunakan kurikulum yang sudah ada dari KurikulumSeeder
        $kurikulum = Kurikulum::where('tahun_awal', 2020)
            ->where('tahun_akhir', 2024)
            ->where('prodi_id', 1)
            ->first();

        if (!$kurikulum) {
            $this->command->info('Kurikulum tidak ditemukan. Pastikan menjalankan KurikulumSeeder terlebih dahulu.');
            return;
        }
        ;

        // Seeder untuk ipteks_pengetahuan
        $pengetahuan = [
            'Fisika',
            'Matematika',
        ];

        foreach ($pengetahuan as $ilmu) {
            IpteksPengetahuan::firstOrCreate([
                'ilmu_pengetahuan' => $ilmu,
                'kurikulum_id' => $kurikulum->id,
            ]);
        }

        // Seeder untuk ipteks_teknologi
        $teknologi = [
            'AI',
            'Blockchain',
        ];

        foreach ($teknologi as $tech) {
            IpteksTeknologi::firstOrCreate([
                'teknologi' => $tech,
                'kurikulum_id' => $kurikulum->id,
            ]);
        }

        // Seeder untuk ipteks_seni
        $seni = [
            'Musik',
            'Seni Rupa',
        ];

        foreach ($seni as $art) {
            IpteksSeni::firstOrCreate([
                'seni' => $art,
                'kurikulum_id' => $kurikulum->id,
            ]);
        }
    }
}
;