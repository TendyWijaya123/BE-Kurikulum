<?php

namespace App\Http\Controllers;

use App\Models\FormulasiCpa;
use Illuminate\Http\Request;

class FormulasiCpaController extends Controller
{
    //
    public function  dropdown()
    {
        $data = FormulasiCpa::select(['id', 'kode'])->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
