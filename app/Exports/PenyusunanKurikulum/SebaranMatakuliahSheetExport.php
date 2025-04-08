<?php

namespace App\Exports\PenyusunanKurikulum;

use App\Models\MataKuliah;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class SebaranMatakuliahSheetExport implements FromView
{

    protected $mataKuliahBySemester;
    protected $totalKeseluruhan;

    public function __construct($kurikulumId)
    {
        // Biarkan ini tetap seperti aslinya
        $this->mataKuliahBySemester = MataKuliah::where('kurikulum_id', $kurikulumId)
            ->get()
            ->groupBy('semester')
            ->map(function ($items, $semester) {
                $totals = [
                    'teori_bt_semester'     => $items->sum('teori_bt'),
                    'teori_pt_semester'     => $items->sum('teori_pt'),
                    'teori_m_semester'      => $items->sum('teori_m'),
                    'total_teori_semester'  => $items->sum('total_teori'),
                    'praktek_bt_semester'   => $items->sum('praktek_bt'),
                    'praktek_pt_semester'   => $items->sum('praktek_pt'),
                    'praktek_m_semester'    => $items->sum('praktek_m'),
                    'total_praktek_semester' => $items->sum('total_praktek'),
                ];

                return [
                    'semester' => $semester,
                    'mata_kuliah' => $items,
                    'total' => $totals
                ];
            })
            ->values();

        $mataKuliahs = MataKuliah::where('kurikulum_id', $kurikulumId)->get();

        $this->totalKeseluruhan = [
            'total_teori_sks'        => $mataKuliahs->sum('total_teori'),
            'total_praktek_sks'      => $mataKuliahs->sum('total_praktek'),
            'total_teori_menit'      => $mataKuliahs->sum('teori_bt') + $mataKuliahs->sum('teori_pt') + $mataKuliahs->sum('teori_m'),
            'total_praktek_menit'    => $mataKuliahs->sum('praktek_bt') + $mataKuliahs->sum('praktek_pt') + $mataKuliahs->sum('praktek_m'),
        ];

        $this->totalKeseluruhan['total_menit'] = $this->totalKeseluruhan['total_teori_menit'] + $this->totalKeseluruhan['total_praktek_menit'];
        $this->totalKeseluruhan['total_sks'] = $this->totalKeseluruhan['total_teori_sks'] + $this->totalKeseluruhan['total_praktek_sks'];

    }


    public function view(): View
    {
        return view('Export.SebaranMataKuliahViewExport', [
            'mataKuliahBySemester' => $this->mataKuliahBySemester,
            'totalKeseluruhan' => $this->totalKeseluruhan,

        ]);
    }
}
