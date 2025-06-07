<?php

namespace App\Imports;

use App\Models\Cpl;
use App\Models\KemampuanAkhir;
use App\Models\RpsMatakuliah;
use App\Models\TujuanBelajar;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RpsImport implements WithMultipleSheets
{
    use  Importable;
    protected $mataKuliahId;

    public function __construct($mataKuliahId)
    {

        $this->mataKuliahId = $mataKuliahId;
    }


    public function sheets(): array
    {
        return [
            'Template RPS' => new RpsSheetImport($this->mataKuliahId),
        ];
    }
}


class RpsSheetImport implements ToCollection, WithHeadingRow
{
    protected $tujuanBelajar;
    protected $cpl;
    protected $kemampuanAkhir;
    protected $mataKuliahId;


    public function __construct($mataKuliahId)
    {
        $this->kemampuanAkhir = KemampuanAkhir::where('mata_kuliah_id', $mataKuliahId)
            ->pluck('id', 'deskripsi')->toArray();

        $this->tujuanBelajar = TujuanBelajar::where('mata_kuliah_id', $mataKuliahId)
            ->pluck('id', 'deskripsi')->toArray();

        $this->cpl = Cpl::whereHas('mataKuliahs', function ($query) use ($mataKuliahId) {
            $query->where('mk_id', $mataKuliahId);
        })->pluck('id', 'kode')->toArray();
    }



    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        Log::info($rows);
        try {

            foreach ($rows as $row) {
                if (!empty($row['minggu'])) {
                    $kemampuanAkhirId = $this->kemampuanAkhir[$row['kemampuan_akhir']] ?? null;
                    $tujuanBelajarId = $this->tujuanBelajar[$row['tujuan_belajar']] ?? null;
                    $cplId = $this->cpl[$row['capaian_pembelajaran_lulusan']] ?? null;



                    $rps = RpsMatakuliah::updateOrCreate(
                        [
                            'mata_kuliah_id' => $this->mataKuliahId,
                            'minggu' => $row['minggu'], // Kunci unik untuk update
                        ],
                        [
                            'kemampuan_akhir_id' => $kemampuanAkhirId,
                            'pokok_bahasan' => $row['pokok_bahasan'],
                            'modalitas_bentuk_strategi_metodepembelajaran' => $row['modalitas_bentuk_strategi_dan_metode_pembelajaran_media_dan_sumber_belajar'],
                            'instrumen_penilaian' => $row['instrumen_penilaian'],
                            'hasil_belajar' => $row['hasil_belajar'],
                            // 'tujuan_belajar_id' => $tujuanBelajarId ?: null,
                            // 'cpl_id' => $cplId,
                            // 'bobot_penilaian' => $row['bobot_penilaian'],
                        ]
                    );
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
