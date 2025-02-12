<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;

class RpsController extends Controller
{
    public function dropdownMatkul($id) {
        return Dosen::find($id)?->matkul()
            ->select('mata_kuliahs.id as mk_id', 'mata_kuliahs.nama')
            ->get();
    }
        
}
