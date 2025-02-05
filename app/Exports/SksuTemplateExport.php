<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SksuTemplateExport implements WithHeadings, WithEvents
{
    public function headings(): array
    {
        return [
            'profil_lulusan',
            'kualifikasi',
            'kategori',
            'kompetensi_kerja',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $validation = $sheet->getCell('C2')->getDataValidation();
                $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setFormula1('"Siap Kerja,Siap Usaha"'); // Pilihan valid

                for ($row = 2; $row <= 100; $row++) {
                    $sheet->getCell("C{$row}")->setDataValidation(clone $validation);
                }
            },
        ];
    }
}
