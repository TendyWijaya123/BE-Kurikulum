<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kurikulum;
use App\Models\Prodi;
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
        // Pastikan prodi ada
        $prodi = Prodi::where('name', 'D-3 Teknik Kimia')->first();

        if (!$prodi) {
            $this->command->info('Prodi D-3 Teknik Kimia tidak ditemukan. Pastikan menjalankan ProdiSeeder terlebih dahulu.');
            return;
        }

        // Pastikan kurikulum belum ada untuk prodi ini
        $kurikulum = Kurikulum::firstOrCreate([
            'tahun_awal' => 2022,
            'tahun_akhir' => 2026,
            'is_active' => true,
            'prodi_id' => $prodi->id,
        ]);

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
