<?php

namespace App\Http\Controllers;

use App\Models\BentukPembelajaran;
use Illuminate\Http\Request;

class BentukPembelajaranController extends Controller
{
    //
    public function dropdown()
    {
        $data = BentukPembelajaran::select('id', 'nama')->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
