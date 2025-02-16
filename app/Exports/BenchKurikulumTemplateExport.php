<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BenchKurikulumTemplateExport implements WithHeadings, WithStyles, ShouldAutoSize
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

    public function styles(Worksheet $sheet)
    {
        // Mendapatkan huruf kolom terakhir
        $lastColumn = $sheet->getHighestColumn();

        // Style untuk header
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'E2EFDA',
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Border untuk seluruh area template (100 baris)
        $sheet->getStyle('A1:' . $lastColumn . '100')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Tambahkan dropdown untuk kolom kategori
        $validation = $sheet->getCell('B2')->getDataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
            ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION)
            ->setAllowBlank(false)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setFormula1('"Luar Negeri,Dalam Negeri"');

        // Copy validasi ke 100 baris
        for ($row = 2; $row <= 100; $row++) {
            $sheet->getCell("B{$row}")->setDataValidation(clone $validation);
        }

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
