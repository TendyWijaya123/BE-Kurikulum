<?php
namespace App\Imports;

use App\Models\IpteksSeni;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SeniImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $kurikulum = Auth::user()->activeKurikulum();

        foreach ($rows as $row) {
            if (!empty($row['deskripsi'])) {
                IpteksSeni::create([
                    'kategori' => 'seni',
                    'deskripsi' => $row['deskripsi'],
                    'link_sumber' => $row['link_sumber'] ?? null,
                    'kurikulum_id' => $kurikulum->id,
                ]);
            }
        }
    }
}