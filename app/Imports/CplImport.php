<?php

namespace App\Imports;

use App\Models\Cpl;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CplImport implements ToCollection, WithHeadingRow
{


    public function collection(Collection $rows)
    {

        $kurikulum = Auth::user()->activeKurikulum();


        foreach ($rows as $row) {
            if (!empty($row['keterangan'])) {
                Cpl::create([
                    'keterangan' => $row['keterangan'],
                    'kurikulum_id'      => $kurikulum->id,
                ]);
            }
        }
    }
}
