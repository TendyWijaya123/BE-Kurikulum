<?php

namespace App\Imports;

use App\Models\Sksu;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithValidation;

class SksuImport implements ToCollection, WithHeadingRow
{

    public function collection(Collection $rows)
    {
        $currentSksu = null;

        $kurikulum = Auth::user()->activeKurikulum();

        foreach ($rows as $row) {
            if (!empty($row['profil_lulusan'])) {
                $currentSksu = Sksu::create([
                    'profil_lulusan' => $row['profil_lulusan'],
                    'kualifikasi'    => $row['kualifikasi'],
                    'kategori'       => $row['kategori'],
                    'kompetensi_kerja' => $row['kompetensi_kerja'],
                    'kurikulum_id'   => $kurikulum->id,
                ]);
            }
        }
    }
}
