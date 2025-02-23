<?php

namespace App\Imports;

use App\Models\BukuReferensi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BukuReferensiImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $user = Auth::guard('dosen')->user();

        if (!$user) {
            throw new \Exception('User tidak terautentikasi');
        }

        foreach ($rows as $row) {
            if (!empty($row['judul']) && !empty($row['penulis'])) {
                BukuReferensi::create([
                    'judul' => $row['judul'],
                    'penulis' => $row['penulis'],
                    'penerbit' => $row['penerbit'] ?? null,
                    'tahun_terbit' => $row['tahun_terbit'] ?? null,
                    'jurusan_id' => $user->jurusan_id,
                ]);
            }
        }
    }
}