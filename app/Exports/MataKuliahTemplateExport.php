<?php

namespace App\Exports;

use App\Models\BentukPembelajaran;
use App\Models\FormulasiCpa;
use App\Models\MetodePembelajaran;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MataKuliahTemplateExport implements WithMultipleSheets
{
    protected $formulasiCpa;
    protected $bentukPembelajaran;
    protected $metodePembelajaran;

    public function __construct()
    {
        $this->formulasiCpa = FormulasiCpa::select('kode', 'deskripsi')->get();
        $this->bentukPembelajaran = BentukPembelajaran::select('nama')->get();
        $this->metodePembelajaran = MetodePembelajaran::select('nama')->get();
    }

    public function sheets(): array
    {
        return [
            new TemplateMataKuliahSheet(),
            new DropDownCpaSheet($this->formulasiCpa),
            new MetodePemebelajaranSheet($this->metodePembelajaran),
            new BentukPembelajaranSheet($this->bentukPembelajaran),
        ];
    }
}

class TemplateMataKuliahSheet implements WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    public function headings(): array
    {
        return [
            'Kode',
            'Nama',
            'Tujuan',
            'Semester',
            'Teori BT',
            'Teori PT',
            'Teori M',
            'Praktek BT',
            'Praktek PT',
            'Praktek M',
            'Formulasi CPA',
            'Tujuan Belajar',
            'Deskripsi Kemampuan Akhir',
            'Estimasi Beban Belajar',
            'Bentuk Pembelajaran',
            'Metode Pembelajaran',

        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'D3D3D3'],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Template Mata Kuliah';
    }
}

class DropDownCpaSheet implements WithHeadings, ShouldAutoSize, , FromCollection, WithTitle
{
    protected $formulasiCpa;

    public function __construct($formulasiCpa)
    {
        $this->formulasiCpa = $formulasiCpa;
    }

    public function headings(): array
    {
        return ['Kode Formulasi CPA', 'Deskripsi'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'D3D3D3'], // Warna abu-abu (light gray
                ],
            ],
        ];
    }

    public function collection()
    {
        return $this->formulasiCpa->map(fn($item) => [$item->kode, $item->deskripsi]);
    }

    public function title(): string
    {
        return 'Formulasi CPA';
    }
}



class MetodePemebelajaranSheet implements WithHeadings, ShouldAutoSize, WithStyles, FromCollection, WithTitle
{
    protected $metodePembelajaran;

    public function __construct($metodePembelajaran)
    {
        $this->metodePembelajaran = $metodePembelajaran;
    }

    public function headings(): array
    {
        return ['Metode Pembelajaran'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'D3D3D3'], // Warna abu-abu (light gray)
                ],
            ],
        ];
    }

    public function collection()
    {
        return $this->metodePembelajaran->map(fn($item) => [$item->nama]);
    }

    public function title(): string
    {
        return 'Metode Pembelajaran';
    }
}


class BentukPembelajaranSheet implements WithHeadings, ShouldAutoSize, WithStyles, FromCollection, WithTitle
{
    protected $bentukPembelajaran;

    public function __construct($bentukPembelajaran)
    {
        $this->bentukPembelajaran = $bentukPembelajaran;
    }

    public function headings(): array
    {
        return ['Bentuk Pembelajaran'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'D3D3D3'],
                ],
            ],
        ];
    }

    public function collection()
    {
        return $this->bentukPembelajaran->map(fn($item) => [$item->nama]);
    }

    public function title(): string
    {
        return 'Bentuk Pembelajaran';
    }
}
