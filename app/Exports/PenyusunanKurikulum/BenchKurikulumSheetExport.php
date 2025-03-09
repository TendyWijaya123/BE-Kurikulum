<?php

namespace App\Exports\PenyusunanKurikulum;

use App\Models\BenchKurikulum;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;

class BenchKurikulumSheetExport implements FromView
{
    protected $benchKurikulumLuarNegeri;
    protected $benchKurikulumDalamNegeri;

    public function __construct($kurikulumId)
    {
        $this->benchKurikulumDalamNegeri = BenchKurikulum::where('kurikulum_id', $kurikulumId)
            ->where('kategori', 'Dalam Negeri')
            ->get();

        $this->benchKurikulumLuarNegeri = BenchKurikulum::where('kurikulum_id', $kurikulumId)
            ->where('kategori', 'Luar Negeri')
            ->get();
    }

    public function view(): View
    {
        return view('Export.BenchKurikulumExportView', [
            'benchKurikulumLuarNegeri' => $this->benchKurikulumLuarNegeri,
            'benchKurikulumDalamNegeri' => $this->benchKurikulumDalamNegeri,
        ]);
    }
}
