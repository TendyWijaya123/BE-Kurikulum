<?php

namespace App\Imports;

use App\Models\BenchKurikulum;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BenchKurikulumImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $currentBenchKurikulum = null;

        $kurikulum = Auth::user()->activeKurikulum();

        $validCategories = ['Luar Negeri', 'Dalam Negeri'];

        foreach ($rows as $row) {

            if (!empty($row['program_studi']) && in_array($row['kategori'], $validCategories)) {
                // Hanya buat BenchKurikulum jika kategori valid
                $currentBenchKurikulum = BenchKurikulum::create([
                    'program_studi' => $row['program_studi'],
                    'kategori'      => $row['kategori'],
                    'cpl'           => $row['cpl'],
                    'ppm'           => $row['ppm'],
                    'kurikulum_id'  => $kurikulum->id,
                ]);
            }
        }
    }
}
