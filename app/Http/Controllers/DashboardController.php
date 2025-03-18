<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Kurikulum;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use App\Jobs\ProcessProdiJob;

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
        $kurikulums = Kurikulum::active()->get();

        foreach ($kurikulums as $kurikulum) {
            $cacheKey = "processed_kurikulum_{$kurikulum->id}";
            Cache::forget($cacheKey);
        }

        return response()->json([
            'message' => 'Semua cache kurikulum telah direset!'
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
}
