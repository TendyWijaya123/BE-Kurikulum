<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Kurikulum;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use App\Jobs\ProcessProdiJob;
use App\Models\MataKuliah;

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
    public function getCurriculumData()
    {
        $kurikulums = Kurikulum::active()->with('prodi')->get();

        // Jalankan batch baru
        $batch = Bus::batch([])->dispatch();

        foreach ($kurikulums as $kurikulum) {
            $batch->add(new ProcessProdiJob($kurikulum));
        }

        // Simpan Batch ID ke cache selama 1 jam
        Cache::put('current_batch_id', $batch->id, now()->addHour());

        return response()->json([
            'message' => 'Batch processing started!',
            'batch_id' => $batch->id
        ]);
    }

    public function getProcessedData()
    {
        $kurikulums = Kurikulum::active()->get();
        $results = [];
    
        foreach ($kurikulums as $kurikulum) {
            $cacheKey = "processed_kurikulum_{$kurikulum->id}";
            $cachedData = Cache::get($cacheKey);
    
            if ($cachedData) {
                $results[$kurikulum->id] = $cachedData;
            }
        }
    
        if (empty($results)) {
            return response()->json([], 404);
        }
    
        return response()->json($results);
    }

    public function refreshCache()
    {
        // Hapus semua cache yang berhubungan dengan kurikulum
        $kurikulums = Kurikulum::active()->get();

        foreach ($kurikulums as $kurikulum) {
            $cacheKeys = [
                "processed_kurikulum_{$kurikulum->id}",
                "matakuliah_dashboard_count_all",
                "matakuliah_detail_prodi_{$kurikulum->prodi->id}",
                "matakuliah_dashboard_count_{$kurikulum->prodi->jurusan_id}",
            ];

            foreach ($cacheKeys as $cacheKey) {
                Cache::forget($cacheKey);
            }
        }

        // Hapus cache batch processing
        Cache::forget('current_batch_id');

        return response()->json([
            'message' => 'Semua cache kurikulum dan mata kuliah telah direset!'
        ]);
    }


    public function getBatchStatus()
    {
        // Ambil Batch ID dari cache
        $batchId = Cache::get('current_batch_id');

        if (!$batchId) {
            return response()->json(['status' => 'Tidak ada proses berjalan'], 404);
        }

        // Cek status batch di Laravel
        $batch = Bus::findBatch($batchId);

        if (!$batch) {
            return response()->json(['status' => 'Batch tidak ditemukan atau sudah selesai'], 404);
        }

        return response()->json([
            'status' => $batch->finished() ? 'Selesai' : 'Sedang diproses',
            'progress' => $batch->progress() ,
            'pending_jobs' => $batch->pendingJobs,
            'failed_jobs' => $batch->failedJobs
        ]);
    }

    // public function getMatakuliah(){
    //     $kurikulums = Kurikulum::active()->with('prodi')->get();
    //     $data = [];
    //     foreach($kurikulums as $kurikulum){
    //         $prodiName = $kurikulum->prodi->name;
    //         $mataKuliah = MataKuliah::where('kurikulum_id', $kurikulum->id)->get();

    //         $data[$prodiName] = $mataKuliah;
    //     }

    //     return response()->json(
    //         [
    //             'data'=> $data
    //         ]
    //         );
    // }

    public function getMatakuliah(Request $request)
    {
        $jurusanId = $request->query('jurusan_id'); // Ambil filter jurusan dari request
        $cacheKey = 'matakuliah_dashboard_count_' . ($jurusanId ?? 'all'); // Cache unik per jurusan

        $data = Cache::remember($cacheKey, 600, function () use ($jurusanId) {
            return Kurikulum::active()
                ->when($jurusanId, function ($query) use ($jurusanId) {
                    $query->whereHas('prodi', function ($q) use ($jurusanId) {
                        $q->where('jurusan_id', $jurusanId);
                    });
                })
                ->with(['prodi', 'mataKuliahs' => function ($query) {
                    $query->selectRaw('kurikulum_id, kategori, COUNT(*) as total')
                        ->groupBy('kurikulum_id', 'kategori'); // Hitung jumlah per kategori
                }])
                ->get()
                ->groupBy(function ($kurikulum) {
                    return $kurikulum->prodi->name; // Kelompokkan berdasarkan nama prodi
                })
                ->map(function ($kurikulums) {
                    return $kurikulums->map(function ($kurikulum) {
                        $countByCategory = $kurikulum->mataKuliahs->groupBy('kategori')->map->sum('total');
                        
                        return [
                            'Nasional' => $countByCategory['Nasional'] ?? 0,
                            'Institusi' => $countByCategory['Institusi'] ?? 0,
                            'Prodi' => $countByCategory['Prodi'] ?? 0
                        ];
                    })->reduce(function ($carry, $item) {
                        return [
                            'Nasional' => ($carry['Nasional'] ?? 0) + $item['Nasional'],
                            'Institusi' => ($carry['Institusi'] ?? 0) + $item['Institusi'],
                            'Prodi' => ($carry['Prodi'] ?? 0) + $item['Prodi']
                        ];
                    }, []);
                });
        });

        return response()->json(['data' => $data]);
    }

    public function getMatakuliahDetail($prodi_id){
        $cacheKey = 'matakuliah_detail_prodi_' . $prodi_id;

        $data = Cache::remember($cacheKey, 600, function () use ($prodi_id) {
            return Kurikulum::active()
                ->whereHas('prodi', function ($query) use ($prodi_id) {
                    $query->where('id', $prodi_id);
                })
                ->with(['prodi', 'mataKuliahs']) // Ambil semua mata kuliah
                ->get();
        });

        return response()->json(['data' => $data]);
    }
}
