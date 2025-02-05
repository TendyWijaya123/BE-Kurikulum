<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PeranIndustriTemplateExport implements WithHeadings
{
    public function headings(): array
    {
        return [
            'jabatan',
            'deskripsi',
        ];
    }
}
