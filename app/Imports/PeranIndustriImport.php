<?php

namespace App\Imports;

use App\Models\PeranIndustri;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PeranIndustriImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $kurikulumId = Auth::user()->activeKurikulum()->id;

        $rows->filter(fn($row) => !empty($row['jabatan']))->each(function ($row) use ($kurikulumId) {
            PeranIndustri::create([
                'jabatan' => $row['jabatan'],
                'deskripsi' => $row['deskripsi'] ?? '',
                'kurikulum_id' => $kurikulumId,
            ]);
        });
    }
}
