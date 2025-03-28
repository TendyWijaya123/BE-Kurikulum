<?php

namespace App\Imports;

use App\Models\BenchKurikulum;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BenchKurikulumImport implements ToCollection, WithHeadingRow
{
    private $currentBenchKurikulum = null;
    private $cplList = [];
    private $ppmList = [];
    public $errors = [];

    public function collection(Collection $rows)
    {
        $kurikulum = Auth::user()->activeKurikulum();
        Log::info('BenchKurikulumImport mulai dijalankan.');

        foreach ($rows as $index => $row) {
            $validator = Validator::make($row->toArray(), [
                'program_studi' => 'required|string|max:255',
                'kategori'      => 'required|string|max:255',
                'cpl'           => 'required|string|max:1000',
                'ppm'           => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                $this->errors[] = [
                    'row' => $index + 2,
                    'errors' => $validator->errors()->all(),
                ];
                continue;
            }

            if (!empty($row['program_studi'])) {
                $this->saveAndReset($kurikulum);
                $this->currentBenchKurikulum = collect($row)->only(['program_studi', 'kategori'])->toArray();
            }

            $this->cplList[] = $row['cpl'];
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
