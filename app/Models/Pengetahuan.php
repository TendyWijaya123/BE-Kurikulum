<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pengetahuan) {
            $kurikulumId = $pengetahuan->kurikulum_id;

            $lastNumber = self::where('kurikulum_id', $kurikulumId)
                ->orderBy('kode_pengetahuan', 'desc')
                ->value('kode_pengetahuan');

            if ($lastNumber) {
                $number = (int) substr($lastNumber, 2) + 1;
            } else {
                $number = 1;
            }

            $pengetahuan->kode_pengetahuan = 'P-' . str_pad($number, 3, '0', STR_PAD_LEFT);
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
                $newCode = 'P-' . str_pad($number, 3, '0', STR_PAD_LEFT);
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