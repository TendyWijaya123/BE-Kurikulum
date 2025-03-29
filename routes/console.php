<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\ProcessProdiJob;
use App\Models\Kurikulum;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Bus;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    $kurikulums = Kurikulum::active()->with('prodi')->get();

        // Jalankan batch baru
        $batch = Bus::batch([])->dispatch();

        foreach ($kurikulums as $kurikulum) {
            $batch->add(new ProcessProdiJob($kurikulum));
        }

        // Simpan Batch ID ke cache selama 1 jam
        Cache::put('current_batch_id', $batch->id, now()->addHour());
})->hourly(); 
