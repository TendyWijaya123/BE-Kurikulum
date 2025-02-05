<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PpmTemplateExport implements WithHeadings
{
    public function headings(): array
    {
        return ['deskripsi'];
    }
}
