<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ipteks;

class IpteksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Ipteks::create([
            'kategori' => 'ilmu_pengetahuan',
            'deskripsi' => 'Contoh deskripsi ilmu pengetahuan',
            'link_sumber' => 'http://example.com',
            'kurikulum_id' => 1, // Ganti dengan ID kurikulum yang sesuai
        ]);

        Ipteks::create([
            'kategori' => 'teknologi',
            'deskripsi' => 'Contoh deskripsi teknologi',
            'link_sumber' => 'http://example.com',
            'kurikulum_id' => 1,
        ]);

        Ipteks::create([
            'kategori' => 'seni',
            'deskripsi' => 'Contoh deskripsi seni',
            'link_sumber' => 'http://example.com',
            'kurikulum_id' => 1,
        ]);
    }
}