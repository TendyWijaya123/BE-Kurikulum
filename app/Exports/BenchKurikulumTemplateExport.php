<?php

namespace App\Exports;

use App\Models\BenchKurikulum;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class BenchKurikulumTemplateExport implements WithHeadings, WithEvents
{

    public function headings(): array
    {
        return [
            'program_studi',
            'kategori',
            'cpl',
            'ppm',
        ];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $validation = $sheet->getCell('B2')->getDataValidation();
                $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setFormula1('"Luar Negeri,Dalam Negeri"');

                for ($row = 2; $row <= 100; $row++) {
                    $sheet->getCell("B{$row}")->setDataValidation(clone $validation);
                }
            },
        ];
    }
}
