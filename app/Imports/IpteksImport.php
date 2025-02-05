<?php

namespace App\Imports;

use App\Models\IpteksPengetahuan;
use App\Models\IpteksSeni;
use App\Models\IpteksTeknologi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class IpteksImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {

        $kurikulum = Auth::user()->activeKurikulum();


        foreach ($rows as $row) {
            if (isset($row['seni']) && !empty($row['seni'])) {
                IpteksSeni::create([
                    'seni' => $row['seni'],
                    'kurikulum_id'      => $kurikulum->id,

                ]);
            }

            if (isset($row['teknologi']) && !empty($row['teknologi'])) {
                IpteksTeknologi::create([
                    'teknologi' => $row['teknologi'],
                    'kurikulum_id'      => $kurikulum->id,

                ]);
            }

            if (isset($row['pengetahuan']) && !empty($row['pengetahuan'])) {
                IpteksPengetahuan::create([
                    'ilmu_pengetahuan' => $row['pengetahuan'],
                    'kurikulum_id'      => $kurikulum->id,

                ]);
            }
        }
    }
}
