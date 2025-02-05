<?php

namespace App\Imports;

use App\Models\Ppm;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PpmImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $kurikulum = Auth::user()->activeKurikulum();

        foreach ($rows as $row) {
            if (!empty($row['deskripsi'])) {
                Ppm::create([
                    'deskripsi' => $row['deskripsi'],
                    'kurikulum_id'      => $kurikulum->id,
                ]);
            }
        }
    }
}
