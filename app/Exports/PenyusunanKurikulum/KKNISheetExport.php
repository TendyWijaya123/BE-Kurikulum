<?php

namespace App\Exports\PenyusunanKurikulum;

use App\Models\CplKkni;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;


class KKNISheetExport implements FromView
{

    protected $cplKkni;

    public function __construct($kurikulumId)
    {
        $this->cplKkni = CplKkni::where("kurikulum_id", $kurikulumId)->get();
    }

    public function view(): View
    {
        return view('Export.CPLKKNIExportView', [
            'cplKkni' => $this->cplKkni,
        ]);
    }
}
