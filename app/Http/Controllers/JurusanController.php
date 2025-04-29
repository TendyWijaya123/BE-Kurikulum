<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJurusanRequest;
use App\Http\Requests\UpdateJurusanRequest;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusans = Jurusan::query()
            ->paginate(10);

        return response()->json($jurusans);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJurusanRequest $request)
    {
        $jurusan = Jurusan::create($request->validated());

        return response()->json([
            'message' => 'Jurusan berhasil dibuat.',
            'data' => $jurusan,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jurusan = Jurusan::with('prodis')->find($id);

        if (!$jurusan) {
            return response()->json([
                'message' => 'Jurusan not found.',
            ], 404);
        }

        return response()->json(['data' => $jurusan], 200);
    }


    public function update(UpdateJurusanRequest $request, $id)
    {
        $jurusan = Jurusan::find($id);

        if (!$jurusan) {
            return response()->json([
                'message' => 'Jurusan tidak ditemukan.',
            ], 404);
        }

        $jurusan->update($request->validated());

        return response()->json([
            'message' => 'Jurusan berhasil diperbarui.',
            'data' => $jurusan,
        ], 200);
    }


    public function destroy($id)
    {
        $jurusan = Jurusan::find($id);

        if (!$jurusan) {
            return response()->json([
                'message' => 'Jurusan not found.',
            ], 404);
        }

        $jurusan->delete();

        return response()->json([
            'message' => 'Jurusan deleted successfully.',
        ], 200);
    }

    public function dropdown()
    {
        $dropdownData = Jurusan::query()
            ->select('id', 'nama')
            ->get();

        return response()->json([
            'data' => $dropdownData,
        ], 200);
    }
}
