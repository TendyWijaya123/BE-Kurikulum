<?php

namespace App\Exports\PenyusunanKurikulum;

use App\Models\VmtJurusan;
use App\Models\VmtPolban;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class VMTSheetExport implements FromView
{
    protected $vmtPolban;
    protected $misiPolban;
    protected $tujuanPolban;
    protected $vmtJurusan;
    protected $misiJurusan;


    public function __construct($kurikulumId)
    {
        $this->vmtPolban = VmtPolban::where('kurikulum_id', $kurikulumId)->first();
        $this->vmtJurusan = VmtJurusan::where('kurikulum_id', $kurikulumId)->first();
        $this->tujuanPolban = $this->vmtPolban->tujuanPolbans;
        $this->misiPolban = $this->vmtPolban->misiPolbans;
        $this->misiJurusan = $this->vmtJurusan->misiJurusans;
    }

    public function view(): View
    {
        return view('Export.VmtExportView', [
            'vmtPolban' => $this->vmtPolban,
            'tujuanPolban' => $this->tujuanPolban,
            'misiPolban' => $this->misiPolban,
            'vmtJurusan' => $this->vmtJurusan,
            'misiJurusan' => $this->misiJurusan
        ]);
    }
}
