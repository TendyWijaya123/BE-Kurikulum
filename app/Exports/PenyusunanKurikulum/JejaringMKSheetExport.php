<?php

namespace App\Exports\PenyusunanKurikulum;

use App\Models\JejaringMkDiagram;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class JejaringMKSheetExport implements FromCollection,  WithTitle, WithDrawings
{
    protected $kurikulumId;

    public function __construct($kurikulumId)
    {
        $this->kurikulumId = $kurikulumId;
    }

    public function collection()
    {
        return collect([]);
    }

    public function title(): string
    {
        return '13B_JejaringMK';
    }

    public function drawings()
    {
        $jejaringMK = JejaringMkDiagram::where('kurikulum_id', $this->kurikulumId)->first();
        Log::info($jejaringMK);
        if (!$jejaringMK || !$jejaringMK->gambar_url) {
            return [];
        }

        $drawing = new Drawing();
        $drawing->setName('Jejaring Mata Kuliah');
        $drawing->setDescription('Jejaring MataKuliah Image');
        $drawing->setPath(storage_path('app/public/' . $jejaringMK->gambar_url));
        $drawing->setHeight(500);
        $drawing->setCoordinates('A1');

        return [$drawing];
    }
}
