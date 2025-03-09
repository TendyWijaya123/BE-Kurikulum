<?php

namespace App\Http\Controllers;

use App\Exports\PenyusunanKurikulum\PenyusunanKurikulumExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PenyusunanKurikulumController extends Controller
{
    public function export($kurikulumId)
    {
        
        return Excel::download(new PenyusunanKurikulumExport($kurikulumId), 'penyusunan_kurikulum.xlsx');
    }
}
