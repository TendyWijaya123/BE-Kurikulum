<?php
namespace App\Imports;

use App\Models\MateriPembelajaran;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MateriPembelajaranImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        Log::info('=== Mulai Import MateriPembelajaran ===');

        $kurikulum = Auth::user()->activeKurikulum();

        foreach ($rows as $index => $row) {
            Log::info('Processing row ' . ($index + 1), [
                'raw_row' => $row->toArray()
            ]);

            if (empty($row['deskripsi'])) {
                continue;
            }

            try {
                $materi = MateriPembelajaran::create([
                    'description' => $row['deskripsi'],
                    'cognitif_proses' => $row['cognitif_proses'],
                    'kurikulum_id' => $kurikulum->id,
                ]);

                Log::info('Berhasil membuat materi:', [
                    'id' => $materi->id,
                    'description' => $materi->description,
                    'cognitif_proses' => $materi->cognitif_proses
                ]);
            } catch (\Exception $e) {
                Log::error('Error saat membuat materi:', [
                    'message' => $e->getMessage(),
                    'row' => $row->toArray()
                ]);
            }
        }

        Log::info('=== Selesai Import MateriPembelajaran ===');
    }
}