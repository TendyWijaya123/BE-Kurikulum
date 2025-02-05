<?php

namespace App\Imports;

use App\Models\BenchKurikulum;
use App\Models\BkCpl;
use App\Models\BkPpm;
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
                    'kurikulum_id'  => $kurikulum->id,
                ]);
            }

            // Simpan data CPL jika ada
            if (!empty($row['cpl']) && $currentBenchKurikulum) {
                BkCpl::create([
                    'cpl' => $row['cpl'],
                    'bk_id' => $currentBenchKurikulum->id,
                ]);
            }

            // Simpan data PPM jika ada
            if (!empty($row['ppm']) && $currentBenchKurikulum) {
                BkPpm::create([
                    'ppm' => $row['ppm'],
                    'bk_id' => $currentBenchKurikulum->id,
                ]);
            }
        }
    }
}
