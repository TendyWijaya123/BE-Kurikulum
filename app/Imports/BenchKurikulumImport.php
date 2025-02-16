<?php

namespace App\Imports;

use App\Models\BenchKurikulum;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BenchKurikulumImport implements ToCollection, WithHeadingRow
{
    private $currentBenchKurikulum = null;
    private $cplList = [];
    private $ppmList = [];

    public function collection(Collection $rows)
    {
        $kurikulum = Auth::user()->activeKurikulum();

        foreach ($rows as $row) {
            if (!empty($row['program_studi'])) {
                $this->saveAndReset($kurikulum);
                $this->currentBenchKurikulum = $row->only(['program_studi', 'kategori'])->toArray();
            }
            $this->cplList[] = $row['cpl'] ?? null;
            $this->ppmList[] = $row['ppm'] ?? null;
        }

        $this->saveAndReset($kurikulum);
    }

    private function saveAndReset($kurikulum)
    {
        if ($this->currentBenchKurikulum) {
            BenchKurikulum::create([
                'program_studi' => $this->currentBenchKurikulum['program_studi'],
                'kategori' => $this->currentBenchKurikulum['kategori'],
                'cpl' => implode("\n", array_filter($this->cplList)),
                'ppm' => implode("\n", array_filter($this->ppmList)),
                'kurikulum_id' => $kurikulum->id,
            ]);
        }

        $this->cplList = [];
        $this->ppmList = [];
    }
}
