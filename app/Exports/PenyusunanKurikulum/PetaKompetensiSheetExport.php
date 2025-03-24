<?php

namespace App\Exports\PenyusunanKurikulum;

use App\Models\PetaKompetensi;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PetaKompetensiSheetExport implements FromCollection, WithTitle, WithDrawings
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
        return 'Peta Kompetensi';
    }

    public function drawings()
    {
        $petaKompetensi = PetaKompetensi::where('kurikulum_id', $this->kurikulumId)->first();

        if (!$petaKompetensi || !$petaKompetensi->gambar_url) {
            return [];
        }

        $drawing = new Drawing();
        $drawing->setName('Peta Kompetensi');
        $drawing->setDescription('Peta Kompetensi Image');
        $drawing->setPath(storage_path('app/public/' . $petaKompetensi->gambar_url));
        $drawing->setHeight(300);
        $drawing->setCoordinates('A1');

        return [$drawing];
    }
}
