<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Dosen extends Authenticatable implements JWTSubject
{
    use HasFactory;
    
    protected $fillable = [
        'nip',
        'nama',
        'email',
        'password',
        'role_id',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'id' => $this->id,
            'name' => $this->nama,
            'permissions' => $this->permissions()->pluck('name')->toArray(),
            'prodi' => $this->prodi()->select('prodi_id')->get()->toArray(),
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permissions()
    {
        return $this->role->permissions();
    }

    public function prodi()
    {
        return $this->belongsToMany(Prodi::class, 'dosen_has_prodi');
    }

    public function matkul() {
        return $this->belongsToMany(MataKuliah::class, 'dosen_has_matkul', 'dosen_id', 'mk_id');
    }    
}
