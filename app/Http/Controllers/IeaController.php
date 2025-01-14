<?php

namespace App\Http\Controllers;

use App\Models\Iea as ModelIea;
use Illuminate\Http\Request;
use App\Models\Prodi;

class IeaController extends Controller
{
    public function index(Request $request)
    {
        $prodiId = $request->query('prodiId');

        // Mendapatkan jenjang berdasarkan prodiId
        $prodi = Prodi::find($prodiId);
        if (!$prodi) {
            return response()->json(['error' => 'Prodi not found'], 404);
        }

        // Kondisi untuk memfilter data berdasarkan jenjang
        $jenjangFilter = $prodi->jenjang === 'D3' ? 'Diploma III' : 'Sarjana Terapan';

        // Mengambil data berdasarkan jenjang
        $data = ModelIea::where('jenjang', $jenjangFilter)->get();

        return response()->json($data);
    }

}
