<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IpteksTemplateExport implements WithHeadings
{
    public function headings(): array
    {
        return ['seni', 'teknologi', 'pengetahuan'];
    }
}
