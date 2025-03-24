<?php

namespace App\Exports\PenyusunanKurikulum;

use App\Models\MataKuliah;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class MKBebanSKSSheetExport implements FromView
{
    protected $mataKuliah;

    public function __construct($kurikulumId)
    {

        $this->mataKuliah = MataKuliah::with('formulasiCpas')->withSum('kemampuanAkhirs', 'estimasi_beban_belajar')
            ->where('kurikulum_id', $kurikulumId)
            ->orderByRaw("FIELD(kategori, 'Nasional', 'Institusi', 'Prodi')")
            ->get();
    }

    public function view(): View
    {
        return view('Export.MKBebanSKSViewExport', [
            'mataKuliah' => $this->mataKuliah,
        ]);
    }
}
