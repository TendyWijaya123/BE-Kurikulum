<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Pengetahuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pengetahuan',
        'deskripsi',
        'kurikulum_id',
    ];

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }

    public function mp()
    {
        return $this->belongsToMany(MateriPembelajaran::class, 'p_mp', 'p_id', 'mp_id')
            ->withTimestamps();
    }

    public function mps()
    {
        return $this->hasMany(MatriksPMp::class, 'p_id', 'id');
    }


    public function cpls(): BelongsToMany
    {
        return $this->belongsToMany(Cpl::class, 'cpl_p', 'p_id', 'cpl_id')
            ->withTimestamps();
    }

    public function mataKuliahs()
    {
        return $this->hasManyThrough(
            MataKuliah::class,
            MatriksPMp::class,
            'p_id',
            'id',
            'id',
            'mp_id'
        )->join('mp_p_mk', 'mp_p_mk.mp_p_id', '=', 'p_mp.mp_id')->select('mata_kuliahs.*');
    }
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!$model->kode_pengetahuan) {
                $lastPengetahuan = self::where('kurikulum_id', $model->kurikulum_id)
                    ->orderBy('id', 'desc')
                    ->first();

                $nextNumber = $lastPengetahuan ? ((int) str_replace('P-', '', $lastPengetahuan->kode_pengetahuan) + 1) : 1;

                $model->kode_pengetahuan = 'P-' . $nextNumber;
            }
        });



        static::deleted(function ($pengetahuan) {
            self::reorderKodePengetahuan($pengetahuan->kurikulum_id);
        });
    }

    public static function reorderKodePengetahuan($kurikulumId)
    {
        DB::beginTransaction();
        try {
            $pengetahuans = self::where('kurikulum_id', $kurikulumId)
                ->orderBy('kode_pengetahuan', 'asc')
                ->get();

            $number = 1;
            foreach ($pengetahuans as $pengetahuan) {
                $newCode = 'P-' . $number;
                if ($pengetahuan->kode_pengetahuan !== $newCode) {
                    $pengetahuan->kode_pengetahuan = $newCode;
                    $pengetahuan->save();
                }
                $number++;
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
