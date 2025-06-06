<?php

namespace App\Exports\PenyusunanKurikulum;

use App\Models\MateriPembelajaran;
use App\Models\Pengetahuan;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class MatriksPMPMKSheetExport implements FromView
{
    protected $pengetahuans;
    protected $materiPembelajarans;


    public function __construct($kurikulumId)
    {
        $this->pengetahuans = Pengetahuan::where('kurikulum_id', $kurikulumId)
            ->with([
                'mps.mataKuliahs'
            ])
            ->get();
        $this->materiPembelajarans = MateriPembelajaran::where('kurikulum_id', $kurikulumId)->get();

    }
    public function view(): View
    {
        return view("Export.MatriksPMPMKViewExport", [
            'pengetahuans' => $this->pengetahuans,
            'materiPembelajarans' => $this->materiPembelajarans,
        ]);
    }
}
