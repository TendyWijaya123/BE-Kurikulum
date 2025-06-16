<?php

namespace App\Exports\PenyusunanKurikulum;

use App\Models\JejaringMKDiagram;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class JejaringMKSheetExport implements FromView, WithTitle, WithEvents
{
    protected $kurikulumId;

    public function __construct($kurikulumId)
    {
        $this->kurikulumId = $kurikulumId;
    }

    public function view(): View
    {
        return view('Export.JejaringMKViewExport', [
            'kurikulumId' => $this->kurikulumId,
        ]);
    }

    public function title(): string
    {
        return '13B_JejaringMK';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $jejaringMK = JejaringMKDiagram::where('kurikulum_id', $this->kurikulumId)->first();

                if (!$jejaringMK || !$jejaringMK->gambar_url) {
                    return;
                }

                $drawing = new Drawing();
                $drawing->setName('Jejaring Mata Kuliah');
                $drawing->setDescription('Jejaring MataKuliah Image');
                $drawing->setPath(storage_path('app/public/' . $jejaringMK->gambar_url));
                $drawing->setHeight(500);
                $drawing->setCoordinates('B8');
                $drawing->setWorksheet($event->sheet->getDelegate());
            },
        ];
    }
}
