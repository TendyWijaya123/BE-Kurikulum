<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kurikulum extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kurikulums';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tahun_awal',
        'tahun_akhir',
        'is_active',
        'prodi_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the Prodi associated with the Kurikulum.
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function sksus()
    {
        return $this->hasMany(Sksu::class);
    }

    public function benchKurikulums()
    {
        return $this->hasMany(BenchKurikulum::class);
    }

    public function vmtJurusans()
    {
        return $this->hasMany(VmtJurusan::class);
    }

    public function vmtPolbans()
    {
        return $this->hasMany(VmtPolban::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->is_active) {
                static::where('prodi_id', $model->prodi_id)
                    ->where('id', '!=', $model->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }
        });
    }
}
