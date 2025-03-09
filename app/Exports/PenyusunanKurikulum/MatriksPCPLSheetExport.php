<?php

namespace App\Exports\PenyusunanKurikulum;

use App\Models\Cpl;
use App\Models\Pengetahuan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class MatriksPCPLSheetExport implements FromView
{
    protected $pengetahuans;
    protected $cpls;

    public function __construct($kurikulumId)
    {
        $this->cpls = Cpl::where('kurikulum_id', $kurikulumId)->get();
        $this->pengetahuans = Pengetahuan::where('kurikulum_id', $kurikulumId)->with('cpls')->get();
    }


    public function view(): View
    {
        return view('Export.MatriksPCPLViewExport', [
            'cpls' => $this->cpls,
            'pengetahuans' => $this->pengetahuans,
        ]);
    }
}
