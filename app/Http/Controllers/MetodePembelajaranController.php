<?php

namespace App\Http\Controllers;

use App\Models\MetodePembelajaran;
use Illuminate\Http\Request;

class MetodePembelajaranController extends Controller
{
    //

    public function dropdown()
    {
        $data = MetodePembelajaran::select(['id', 'nama'])->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
