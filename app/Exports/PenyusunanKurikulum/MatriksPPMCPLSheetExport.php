<?php

namespace App\Exports\PenyusunanKurikulum;

use App\Models\Cpl;
use App\Models\Ppm;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class MatriksPPMCPLSheetExport implements FromView
{
    protected $cpls;
    protected $ppms;

    public function __construct($kurikulumId)
    {
        $this->cpls = Cpl::where('kurikulum_id', $kurikulumId)->with('ppms')->get();
        $this->ppms = Ppm::where('kurikulum_id', $kurikulumId)->get();
    }

    public function view(): View
    {
        return view('Export.MatriksCPLPPMViewExport', [
            'cpls' => $this->cpls,
            'ppms' => $this->ppms,
        ]);
    }
}
