<?php

namespace App\Exports\PenyusunanKurikulum;

use App\Models\MataKuliah;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromView;

class OrganisasiMkSheetExport implements FromView
{
    protected $mataKuliahBySemester;
    protected $totalKeseluruhan;
    protected $maxPerKategori;

    public function __construct($kurikulumId)
    {
        $mataKuliah = MataKuliah::where('kurikulum_id', $kurikulumId)
            ->get(['nama', 'sks', 'semester', 'kategori']);

        // Cek apakah kategori "" ada dalam data
        $adaKategoriKosong = $mataKuliah->contains('kategori', '');

        // Urutan kategori (tanpa "" jika tidak ada dalam data)
        $kategoriOrder = ['Prodi', 'Institusi', 'Nasional'];
        if ($adaKategoriKosong) {
            $kategoriOrder[] = ''; // Tambahkan "" hanya jika ada dalam data
        }

        $mataKuliahBySemester = $mataKuliah->groupBy('semester')->map(function ($semesterGroup) use ($kategoriOrder) {
            // Kelompokkan berdasarkan kategori
            $groupedByKategori = $semesterGroup->groupBy('kategori')->map(function ($kategoriGroup) {
                return [
                    'mata_kuliah' => $kategoriGroup->map(function ($mk) {
                        return [
                            'nama' => $mk->nama,
                            'sks' => (int) $mk->sks
                        ];
                    }),
                    'total_sks' => $kategoriGroup->sum('sks'),
                    'jumlah_mata_kuliah' => $kategoriGroup->count()
                ];
            });

            // Urutkan kategori sesuai dengan urutan yang diinginkan
            $sortedByKategori = collect($kategoriOrder)->mapWithKeys(function ($kategori) use ($groupedByKategori) {
                return [$kategori => $groupedByKategori->get($kategori, [])];
            });

            return [
                'kategori' => $sortedByKategori,
                'total_sks' => $semesterGroup->sum('sks'),
                'jumlah_mata_kuliah' => $semesterGroup->count()
            ];
        });

        $maxPerKategori = [];
        foreach ($mataKuliahBySemester as $semester) {
            foreach ($semester['kategori'] as $kategori => $data) {
                if (!isset($maxPerKategori[$kategori]) || (isset($data['jumlah_mata_kuliah']) && $data['jumlah_mata_kuliah'] > $maxPerKategori[$kategori])) {
                    $maxPerKategori[$kategori] = $data['jumlah_mata_kuliah'] ?? 0;
                }
            }
        }

        $totalKeseluruhan = [
            'total_sks' => $mataKuliahBySemester->sum('total_sks'),
            'jumlah_mata_kuliah' => $mataKuliahBySemester->sum('jumlah_mata_kuliah')
        ];

        $this->mataKuliahBySemester = $mataKuliahBySemester;
        $this->maxPerKategori = $maxPerKategori;
        $this->totalKeseluruhan = $totalKeseluruhan;

        Log::info("Mata Kuliah per Semester:\n" . json_encode($this->mataKuliahBySemester, JSON_PRETTY_PRINT));
        Log::info("Jumlah Terbesar per Kategori:\n" . json_encode($this->maxPerKategori, JSON_PRETTY_PRINT));
        Log::info("Total Keseluruhan:\n" . json_encode($this->totalKeseluruhan, JSON_PRETTY_PRINT));
    }

    public function view(): View
    {
        return view('Export.OrganisasiMKViewExport', [
            'mataKuliahBySemester' => $this->mataKuliahBySemester,
            'totalKeseluruhan' => $this->totalKeseluruhan,
            'maxPerKategori' => $this->maxPerKategori,
        ]);
    }
}
