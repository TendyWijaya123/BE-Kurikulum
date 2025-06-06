<?php

namespace App\Exports\PenyusunanKurikulum;

use App\Models\Cpl;
use App\Models\MataKuliah;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class MatriksCPLMKSheetExport implements FromView
{

    protected $cpls;
    protected $matakuliahs;


    public function __construct($kurikulumId)
    {
        $this->cpls = Cpl::where('kurikulum_id', $kurikulumId)
            ->with(['mataKuliahs' => function ($query) {
                $query->select('mata_kuliahs.id', 'nama');
            }])
            ->get()
            ->map(function ($cpl) {
                $order = ['I' => 0, 'R' => 1, 'M' => 2, 'A' => 3];

                $cpl->mataKuliahs = $cpl->mataKuliahs->sortBy(function ($mk) use ($order) {
                    $kategori = strtoupper($mk->pivot->kategori);
                    $kategoriLetters = preg_split('/[\s,|]+/', $kategori);

                    $sortedKategori = collect($kategoriLetters)
                        ->map(fn($k) => trim($k))
                        ->sortBy(fn($k) => $order[$k] ?? 999)
                        ->implode(',');

                    $mk->pivot->kategori = $sortedKategori;

                    $minOrder = collect($kategoriLetters)
                        ->map(fn($k) => $order[trim($k)] ?? 999)
                        ->min();

                    return $minOrder ?? 999;
                })->values();

                return $cpl;
            });


        $this->matakuliahs = MataKuliah::where('kurikulum_id', $kurikulumId)
            ->select('id', 'nama')
            ->get();
    }


    public function view(): View
    {
        return view("Export.MatriksCPLMKViewExport", [
            'cpls' => $this->cpls,
            'matakuliahs' => $this->matakuliahs,
        ]);
    }
}
