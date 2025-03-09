<?php

namespace App\Exports\PenyusunanKurikulum;

use App\Models\Cpl;
use App\Models\Iea;
use App\Models\Kurikulum;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class MatriksCPLIEASheetExport implements FromView
{
    protected $iea;
    protected $cpls;

    public function __construct($kurikulumId)
    {
        $kurikulum = Kurikulum::with('prodi')->find($kurikulumId);
        $this->cpls = Cpl::where('kurikulum_id', $kurikulumId)->with('iea')->get();


        if ($kurikulum && $kurikulum->prodi) {
            $jenjang = $kurikulum->prodi->jenjang;
            $jenjangIea = ($jenjang === 'D3') ? 'Diploma III' : (($jenjang === 'D4') ? 'Sarjana Terapan' : null);
            $this->iea = Iea::where('jenjang', $jenjangIea)->get();

        }
    }

    public function view(): View
    {
        return view('Export.MatriksCPLIEAViewExport', [
            'cpls' => $this->cpls,
            'iea' => $this->iea,
        ]);
    }
}
