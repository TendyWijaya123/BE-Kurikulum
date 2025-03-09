<?php

namespace App\Exports\PenyusunanKurikulum;

use App\Models\Sksu;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class SKSUSheetExport implements FromView, WithTitle
{
    protected $siapKerja;
    protected $siapUsaha;

    public function __construct($kurikulumId)
    {
        $this->siapKerja = Sksu::where('kurikulum_id', $kurikulumId)
            ->where('kategori', 'Siap Kerja')
            ->get();

        $this->siapUsaha = Sksu::where('kurikulum_id', $kurikulumId)
            ->where('kategori', 'Siap Usaha')
            ->get();
    }

    public function view(): View
    {
        return view('Export.SksuExport', [
            'siapKerja' => $this->siapKerja,
            'siapUsaha' => $this->siapUsaha,
        ]);
    }

    public function title(): string
    {
        return '4a_AK_SKSU';
    }
}
