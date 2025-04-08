<?php

namespace App\Exports\PenyusunanKurikulum;

use App\Models\MataKuliah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;


class PemilahanMataKuliahSheetExport implements FromView
{
    protected $mataKuliahByKategori;
    protected $totalKeseluruhan;

    public function __construct($kurikulumId)
    {

        $mataKuliah = MataKuliah::where('kurikulum_id', $kurikulumId)->get();

        $this->totalKeseluruhan = [
            'total_teori'    => $mataKuliah->sum('total_teori'),
            'total_praktek'  => $mataKuliah->sum('total_praktek'),
            'total_kurikulum' => $mataKuliah->sum('total_teori') + $mataKuliah->sum('total_praktek'),
        ];

        $this->mataKuliahByKategori = MataKuliah::where('kurikulum_id', $kurikulumId)
            ->get()
            ->groupBy('kategori')
            ->map(function ($items, $kategori) {
                $totalTeori = $items->sum('total_teori');
                $totalPraktek = $items->sum('total_praktek');

                $totals = [
                    'total_teori_kategori'   => $totalTeori,
                    'total_praktek_kategori' => $totalPraktek,
                    'total_kategori'         => $totalTeori + $totalPraktek, // Ini tambahan total keseluruhan
                ];

                return [
                    'kategori'    => $kategori,
                    'mata_kuliah' => $items,
                    'total'       => $totals,
                ];
            })
            ->values();


        Log::info("=== Mata Kuliah By Kategori ===");
        Log::info(json_encode($this->mataKuliahByKategori, JSON_PRETTY_PRINT));

        Log::info("=== Total Keseluruhan ===");
        Log::info(json_encode($this->totalKeseluruhan, JSON_PRETTY_PRINT));
    }

    public function view(): View
    {
        return view('Export.PemilahanMataKuliahViewExport', [
            'mataKuliahByKategori' => $this->mataKuliahByKategori,
            'totalKeseluruhan' => $this->totalKeseluruhan,

        ]);
    }
}
