<?php

namespace App\Imports;

use App\Models\PeranIndustri;
use App\Models\PeranIndustriDeskripsi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PeranIndustriImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {

        $currentPeranIndustri = null;

        $kurikulum = Auth::user()->activeKurikulum();

        foreach ($rows as $row) {
            if (!empty($row['jabatan'])) {
                $currentPeranIndustri = PeranIndustri::create([
                    'jabatan'       => $row['jabatan'],
                    'kurikulum_id'   => $kurikulum->id,
                ]);
            }

            if (!empty($row['deskripsi']) && $currentPeranIndustri) {
                PeranIndustriDeskripsi::create([
                    'deskripsi_point' => $row['deskripsi'],
                    'peran_industri_id'          => $currentPeranIndustri->id,
                ]);
            }
        }
    }
}
