<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Ppm extends Model
{
    use HasFactory;

    protected $fillable = [
        'deskripsi',
        'kurikulum_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!$model->kode) {
                // Get the highest kode for the given kurikulum_id
                $lastPpm = self::where('kurikulum_id', $model->kurikulum_id)
                    ->orderBy('id', 'desc')
                    ->first();

                $nextNumber = $lastPpm ? ((int) str_replace('PPM-', '', $lastPpm->kode) + 1) : 1;

                // Set the kode
                $model->kode = 'PPM-' . $nextNumber;
            }
        });

        static::deleted(function ($model) {
            self::reindexKode($model->kurikulum_id);
        });
    }

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class, 'kurikulum_id');
    }

    public function cpls()
    {
        return $this->belongsToMany(Cpl::class, 'cpl_ppm', 'ppm_id', 'cpl_id')
            ->withTimestamps();
    }

    /**
     * Reindex the kode for PPMs in a specific kurikulum_id using a transaction.
     *
     * @param int $kurikulumId
     */
    public static function reindexKode(int $kurikulumId)
    {
        DB::beginTransaction();
        try {
            // Get all PPMs for the given kurikulum_id, ordered by their ID
            $ppms = self::where('kurikulum_id', $kurikulumId)
                ->orderBy('id', 'asc')
                ->get();

            // Update each PPM's kode with the new sequence
            foreach ($ppms as $index => $ppm) {
                $ppm->kode = 'PPM-' . ($index + 1);
                $ppm->save(); // Save the changes
            }

            // Commit the transaction if everything goes well
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction if there is any error
            DB::rollback();

            // Log the error and throw it for further handling
            Log::error('Error while reindexing PPM codes: ' . $e->getMessage());
            throw $e; // Rethrow the exception to let it be handled by the calling code
        }
    }
}
