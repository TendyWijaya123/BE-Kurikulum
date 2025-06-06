<?php

namespace App\Imports;

use App\Models\BentukPembelajaran;
use App\Models\FormulasiCpa;
use App\Models\KemampuanAkhir;
use App\Models\MataKuliah;
use App\Models\MetodePembelajaran;
use App\Models\TujuanBelajar;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\DB;


class MataKuliahImport implements WithMultipleSheets
{
    use Importable;

    public function sheets(): array
    {
        return [
            'Template Mata Kuliah' => new MataKuliahSheetImport(),
        ];
    }
}

class MataKuliahSheetImport implements ToCollection, WithHeadingRow
{
    protected $formulasiCpa;
    protected $bentukPembelajaran;
    protected $metodePembelajaran;

    public function __construct()
    {
        $this->formulasiCpa = FormulasiCpa::pluck('id', 'kode')->toArray();
        $this->bentukPembelajaran = BentukPembelajaran::all()->pluck('id', 'nama')->mapWithKeys(function ($id, $nama) {
            return [strtolower(trim($nama)) => $id];
        })->toArray();

        $this->metodePembelajaran = MetodePembelajaran::all()->pluck('id', 'nama')->mapWithKeys(function ($id, $nama) {
            return [strtolower(trim($nama)) => $id];
        })->toArray();
    }



    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
            $mataKuliah = null;

            foreach ($rows as $row) {
                if (!empty($row['kode']) && !empty($row['nama'])) {
                    $kurikulum = Auth::user()->activeKurikulum();

                    $mataKuliah = MataKuliah::where('kode', $row['kode'])
                        ->where('kurikulum_id', $kurikulum->id)
                        ->first();

                    if (!$mataKuliah) {
                        $mataKuliah = MataKuliah::create([
                            'kurikulum_id' => $kurikulum->id,
                            'kode' => $row['kode'],
                            'kategori' => $row['kategori'] ?? "Prodi",
                            'nama' => $row['nama'],
                            'tujuan' => $row['tujuan'],
                            'deskripsi_singkat' => $row['deskripsi_singkat'] ?? null,
                            'semester' => $row['semester'] ?? null,
                            'teori_bt' => $row['teori_bt'] ?? 0,
                            'teori_pt' => $row['teori_pt'] ?? 0,
                            'teori_m' => $row['teori_m'] ?? 0,
                            'praktek_bt' => $row['praktek_bt'] ?? 0,
                            'praktek_pt' => $row['praktek_pt'] ?? 0,
                            'praktek_m' => $row['praktek_m'] ?? 0,
                        ]);
                    }
                }

                if ($mataKuliah) {
                    if (!empty($row['tujuan_belajar'])) {
                        TujuanBelajar::create([
                            'mata_kuliah_id' => $mataKuliah->id,
                            'deskripsi' => $row['tujuan_belajar'],
                        ]);
                    }

                    if (!empty($row['formulasi_cpa'])) {
                        $kodeArray = explode(',', $row['formulasi_cpa']);
                        $kodeArray = array_map('trim', $kodeArray);

                        $formulasiIds = collect($kodeArray)
                            ->map(fn($kode) => $this->formulasiCpa[$kode] ?? null)
                            ->filter()
                            ->values()
                            ->toArray();

                        if (!empty($formulasiIds)) {
                            $mataKuliah->formulasiCpas()->syncWithoutDetaching($formulasiIds);
                        }
                    }

                    if (!empty($row['deskripsi_kemampuan_akhir'])) {
                        $bentukPembelajaranIds = collect(explode(',', $row['bentuk_pembelajaran'] ?? ''))
                            ->map(fn($nama) => strtolower(trim($nama)))
                            ->map(fn($nama) => $this->bentukPembelajaran[$nama] ?? null)
                            ->filter()
                            ->values()
                            ->toArray();

                        Log::info('Bentuk Pembelajaran:', [
                            'input' => $row['bentuk_pembelajaran'] ?? '',
                            'ids' => $bentukPembelajaranIds,
                        ]);


                        $metodePembelajaranIds = collect(explode(',', $row['metode_pembelajaran'] ?? ''))
                            ->map(fn($nama) => strtolower(trim($nama)))
                            ->map(fn($nama) => $this->metodePembelajaran[$nama] ?? null)
                            ->filter()
                            ->values()
                            ->toArray();

                        Log::info('Metode Pembelajaran:', [
                            'input' => $row['metode_pembelajaran'] ?? '',
                            'ids' => $metodePembelajaranIds,
                        ]);

                        Log::info('Mapping bentuk pembelajaran', $this->bentukPembelajaran);
                        Log::info('Mapping metode pembelajaran', $this->metodePembelajaran);




                        $kemampuanAkhir = KemampuanAkhir::create([
                            'mata_kuliah_id' => $mataKuliah->id,
                            'estimasi_beban_belajar' => $row['estimasi_beban_belajar'] ?? null,
                            'deskripsi' => $row['deskripsi_kemampuan_akhir'],
                        ]);

                        if (!empty($bentukPembelajaranIds)) {
                            $kemampuanAkhir->bentukPembelajarans()->syncWithoutDetaching($bentukPembelajaranIds);
                        }

                        if (!empty($metodePembelajaranIds)) {
                            $kemampuanAkhir->metodePembelajarans()->syncWithoutDetaching($metodePembelajaranIds);
                        }
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
