<?php

namespace App\Imports;

use App\Models\Ipteks;
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
            if (!empty($row['kategori'])) {
                $kategori = $this->formatKategori($row['kategori']);

                Ipteks::create([
                    'kategori' => $kategori,
                    'deskripsi' => $row['deskripsi'],
                    'link_sumber' => $row['link_sumber'],
                    'kurikulum_id' => $kurikulum->id,
                ]);
            }
        }
    }

    private function formatKategori($kategori)
    {
        $mapping = [
            'Ilmu Pengetahuan' => 'ilmu_pengetahuan',
            'Teknologi' => 'teknologi',
            'Seni' => 'seni',
        ];

        return $mapping[$kategori] ?? null;
    }

}
