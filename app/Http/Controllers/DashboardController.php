<?php

namespace App\Http\Controllers;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Cpl;
use App\Models\Ppm;
use App\Models\VmtJurusan;
use App\Models\Pengetahuan;
use App\Models\MateriPembelajaran;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getJurusans()
    {
        $jurusans = Jurusan::all();
        return response()->json($jurusans);
    }

    public function getProdis(Request $request)
    {
        $prodis = Prodi::all();
        return response()->json($prodis);
    }

    public function getCurriculumData($id)
    {
        // Get the latest kurikulum for the prodi
        $kurikulum = Prodi::find($id)
            ->kurikulums()
            ->latest()
            ->first();

        if (!$kurikulum) {
            return response()->json([
                'message' => 'No curriculum found for this program'
            ], 404);
        }

        // Get CPLs
        $cpls = Cpl::where('kurikulum_id', $kurikulum->id)
            ->select('id', 'kode', 'keterangan')
            ->get();

            // dd($cpls);

        // Get PPMs
        $ppms = Ppm::where('kurikulum_id', $kurikulum->id)
            ->select('id', 'kode', 'deskripsi')
            ->get();

        // Get Visi Misi
        $visiMisi = VmtJurusan::where('kurikulum_id', $kurikulum->id)
            ->with('misiJurusans')
            ->first();

        // Get Pengetahuan
        $pengetahuan = Pengetahuan::where('kurikulum_id', $kurikulum->id)
            ->select('id', 'kode_pengetahuan', 'deskripsi')
            ->get();

        // Get Materi Pembelajaran
        $materiPembelajaran = MateriPembelajaran::with('knowledgeDimension')->where('kurikulum_id', $kurikulum->id)
            ->select('id', 'code', 'description', 'cognitif_proses')
            ->get();

        return response()->json([
            'cpls' => $cpls,
            'ppms' => $ppms,
            'visi_misi' => $visiMisi,
            'pengetahuan' => $pengetahuan,
            'materi_pembelajaran' => $materiPembelajaran,
            'kurikulum' => [
                'id' => $kurikulum->id,
                'tahunAwal' => $kurikulum->tahun_awal,
                'tahunAkhir' => $kurikulum->tahun_akhir,
            ]
        ]);
    }
}
