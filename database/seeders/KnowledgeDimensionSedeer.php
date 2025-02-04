<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KnowledgeDimensionSedeer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $data = [
        [
            'code' => 'F',
            'name' => 'Factual Knowledge',
            'description' => 'Mahasiswa mempelajari definisi dan terminologi dasar seperti aktiva, kewajiban, modal.'
        ],
        [
            'code' => 'C',
            'name' => 'Conceptual Knowledge',
            'description' => 'Mahasiswa memahami bagaimana komponen-komponen laporan keuangan saling terkait dan bagaimana transaksi bisnis memengaruhi elemen-elemen tersebut.'
        ],
        [
            'code' => 'P',
            'name' => 'Procedural Knowledge',
            'description' => 'Mahasiswa belajar cara menyusun laporan keuangan menggunakan metode tertentu, seperti pencatatan jurnal, buku besar, hingga neraca.'
        ],
        [
            'code' => 'M',
            'name' => 'Metacognitive Knowledge',
            'description' => 'Mahasiswa mampu mengidentifikasi area di mana mereka kesulitan dalam menyusun laporan keuangan dan menerapkan strategi belajar yang efektif untuk memperbaiki pemahaman.'
        ],
    ];

    foreach ($data as $item) {
        DB::table('knowledge_dimensions')->insert($item);
    }
}

}
