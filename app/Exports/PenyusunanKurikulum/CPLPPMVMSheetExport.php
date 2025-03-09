<?php

namespace App\Exports\PenyusunanKurikulum;

use App\Models\Cpl;
use App\Models\PeranIndustri;
use App\Models\Ppm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;


class CPLPPMVMSheetExport implements FromView
{

    protected $cpl;
    protected $peranIndustri;
    protected $ppm;

    public function __construct($kurikulumId)
    {
        $this->cpl = Cpl::where('kurikulum_id', $kurikulumId)
            ->orderBy('kode', 'asc') // Urutkan berdasarkan kode secara ascending (CPL-1, CPL-2, ...)
            ->get();

        $this->peranIndustri = PeranIndustri::where('kurikulum_id', $kurikulumId)->get();

        $this->ppm = Ppm::where('kurikulum_id', $kurikulumId)
            ->orderBy('kode', 'asc') // Urutkan berdasarkan kode secara ascending
            ->get();
    }


    public function view(): View
    {
        return view('Export.CplPpmVmExportView', [
            'cpl' => $this->cpl,
            'peranIndustri' => $this->peranIndustri,
            'ppm' => $this->ppm,
        ]);
    }
}
