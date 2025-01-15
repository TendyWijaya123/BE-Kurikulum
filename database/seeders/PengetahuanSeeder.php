<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengetahuan;

class PengetahuanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'kode_pengetahuan' => 'P1',
                'deskripsi' => 'Pengetahuan tentang algoritma dasar',
                'kurikulum_id' => 1,
            ],
            [
                'kode_pengetahuan' => 'P2',
                'deskripsi' => 'Pengetahuan tentang pemrograman berorentasi objek',
                'kurikulum_id' => 1,
            ],
            [
                'kode_pengetahuan' => 'P3',
                'deskripsi' => 'Pengetahuan tentang struktur data',
                'kurikulum_id' => 1,
            ]
        ];

        foreach ($data as $item) {
            Pengetahuan::create($item);
        }
    }
}
