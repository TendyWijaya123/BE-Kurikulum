<?php

namespace App\Exports\PenyusunanKurikulum;

use App\Models\IpteksPengetahuan;
use App\Models\IpteksSeni;
use App\Models\IpteksTeknologi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;


class IpteksSheetExport implements FromView
{

    protected $ipteksPengetahuan;
    protected $ipteksTeknologi;
    protected $ipteksSeni;

    public function __construct($kurikulumId)
    {

        $this->ipteksPengetahuan = IpteksPengetahuan::where('kurikulum_id', $kurikulumId)->get();
        $this->ipteksTeknologi = IpteksTeknologi::where('kurikulum_id', $kurikulumId)->get();
        $this->ipteksSeni = IpteksSeni::where('kurikulum_id', $kurikulumId)->get();
    }

    public function view(): View
    {
        return view('Export.IpteksExportView', [
            'ipteksPengetahuan' => $this->ipteksPengetahuan,
            'ipteksTeknologi' => $this->ipteksTeknologi,
            'ipteksSeni' => $this->ipteksSeni,
        ]);
    }
}
