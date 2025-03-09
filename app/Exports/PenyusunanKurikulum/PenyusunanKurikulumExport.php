<?php

namespace App\Exports\PenyusunanKurikulum;

use App\Models\Kurikulum;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PenyusunanKurikulumExport implements WithMultipleSheets
{

    protected $kurikulum;
    protected $kurikulumId;


    public function __construct($kurikulumId)
    {
        Log::info("Test" . $kurikulumId);
        $this->kurikulum = Kurikulum::find($kurikulumId);
        $this->kurikulumId = $kurikulumId;
    }

    public function sheets(): array
    {
        if (!$this->kurikulum) {
            return [];
        }

        return [
            new SKSUSheetExport($this->kurikulum->id),
            new BenchKurikulumSheetExport($this->kurikulum->id),
            new IpteksSheetExport($this->kurikulum->id),
            new KKNISheetExport($this->kurikulum->id),
            new VMTSheetExport($this->kurikulum->id),
            new CPLPPMVMSheetExport($this->kurikulum->id),
            new MatriksPPMCPLSheetExport($this->kurikulum->id),
            new MatriksCPLIEASheetExport($this->kurikulum->id),
            new MatriksPCPLSheetExport($this->kurikulum->id),

        ];
    }
}
