<?php

namespace App\Exports;

use App\Models\Cpl;
use App\Models\KemampuanAkhir;
use App\Models\MataKuliah;
use App\Models\TujuanBelajar;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RpsTemplateExport implements WithMultipleSheets
{

    protected $tujuanBelajar;
    protected $cpl;
    protected $kemampuanAkhir;


    public function __construct($mataKuliahId)
    {
        $this->kemampuanAkhir = KemampuanAkhir::where('mata_kuliah_id', $mataKuliahId)
            ->get(['id', 'deskripsi']);
        $this->tujuanBelajar = TujuanBelajar::where('mata_kuliah_id', $mataKuliahId)
            ->get(['id', 'kode', 'deskripsi']);
        $this->cpl = Cpl::whereHas('mataKuliahs', function ($query) use ($mataKuliahId) {
            $query->where('mk_id', $mataKuliahId);
        })->get(['id', 'kode', 'keterangan']);
    }


    public  function  sheets(): array
    {
        return [
            new TemplateRPSSheet(),
            new KemampuanAkhirSheet($this->kemampuanAkhir),
            new TujuanBelajarSheet($this->tujuanBelajar),
            new CplSheet($this->cpl),
        ];
    }
}

class TemplateRPSSheet implements WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    public function headings(): array
    {
        return [
            'Minggu',
            'Kemampuan Akhir',
            'Pokok Bahasan',
            'Modalitas, Bentuk, Strategi, dan Metode Pembelajaran (Media dan Sumber Belajar)',
            'Instrumen Penilaian',
            'Hasil Belajar',
            'Capaian Pembelajaran Lulusan',
            'Tujuan Belajar',
            'Bobot Penilaian',
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
        return 'Template RPS';
    }
}

class KemampuanAkhirSheet implements WithHeadings, ShouldAutoSize, WithStyles, FromCollection, WithTitle
{
    protected $kemampuanAkhir;

    public function __construct($kemampuanAkhir)
    {
        $this->kemampuanAkhir = $kemampuanAkhir;
    }

    public function headings(): array
    {
        return ['Kemampuan Akhir yang Direncanakan'];
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
        return $this->kemampuanAkhir->map(fn($item) => [$item->deskripsi]);
    }

    public function title(): string
    {
        return 'Kemampuan Akhir yang Direncanakan';
    }
}

class TujuanBelajarSheet implements WithHeadings, ShouldAutoSize, WithStyles, FromCollection, WithTitle
{
    protected $tujuanBelajar;

    public function __construct($tujuanBelajar)
    {
        $this->tujuanBelajar = $tujuanBelajar;
    }

    public function headings(): array
    {
        return ['Kode', "Deskripsi"];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'D3D3D3'], // Light gray color
                ],
            ],
        ];
    }


    public function collection()
    {
        return $this->tujuanBelajar->map(fn($item) => [$item->kode, $item->deskripsi]);
    }

    public function title(): string
    {
        return 'Tujuan Belajar';
    }
}

class CplSheet implements WithHeadings, ShouldAutoSize, WithStyles, FromCollection, WithTitle
{
    protected $cpl;

    public function __construct($cpl)
    {
        $this->cpl = $cpl;
    }

    public function headings(): array
    {
        return ['Kode', "Keterangan"];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'D3D3D3'], // Light gray color
                ],
            ],
        ];
    }


    public function collection()
    {
        return $this->cpl->map(fn($item) => [$item->kode, $item->keterangan]);
    }

    public function title(): string
    {
        return 'Capaian Pembelajaran Lulusan';
    }
}
