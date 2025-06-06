<?php

namespace App\Imports;

use App\Models\Cpl;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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

                $validator = Validator::make($row->toArray(), [
                    'keterangan' => 'required|string',
                ]);

                if ($validator->fails()) {
                    throw new \Exception("Error validasi di baris ke " . ($index + 2) . ": " . implode(", ", $validator->errors()->all()));
                }

                Cpl::create([
                    'keterangan' => $row['keterangan'],
                    'kurikulum_id'      => $kurikulum->id,
                ]);
            }
        }
    }
}
