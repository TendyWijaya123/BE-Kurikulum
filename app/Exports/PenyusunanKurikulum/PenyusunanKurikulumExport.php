<?php

namespace App\Exports\PenyusunanKurikulum;

use App\Models\Kurikulum;
use App\Models\PetaKompetensi;
use App\Models\Prodi;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PenyusunanKurikulumExport implements WithMultipleSheets
{

    protected $kurikulum;
    protected $kurikulumId;
    protected $prodi;


    public function __construct($kurikulumId)
    {
        $this->kurikulum = Kurikulum::find($kurikulumId);
        $this->kurikulumId = $kurikulumId;
        $this->prodi = Prodi::find($this->kurikulum->prodi_id);
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
            new PetaKompetensiSheetExport($this->kurikulum->id),
            new MatriksPMPSheetExport($this->kurikulum->id),
            new MatriksPMPMKSheetExport($this->kurikulum->id),
            new StrukturMKSheetExport($this->kurikulum->id),
            new MKBebanSKSSheetExport($this->kurikulum->id),
            new OrganisasiMkSheetExport($this->kurikulum->id, $this->prodi),
            new JejaringMKSheetExport($this->kurikulum->id),
            new MatriksCPLMKSheetExport($this->kurikulum->id),
            new SebaranMatakuliahSheetExport($this->kurikulum->id),
            new PemilahanMataKuliahSheetExport($this->kurikulum->id),
        ];
    }
}
