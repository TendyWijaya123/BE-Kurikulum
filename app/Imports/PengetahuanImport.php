<?php

namespace App\Imports;

use App\Models\Pengetahuan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PengetahuanImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $kurikulum = Auth::user()->activeKurikulum();

        foreach ($rows as $row) {
            if (!empty($row['deskripsi'])) {
                Pengetahuan::create([
                    'deskripsi' => $row['deskripsi'],
                    'kurikulum_id'      => $kurikulum->id,
                ]);
            }
        }
    }
}
