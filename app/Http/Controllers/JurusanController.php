<?php

namespace App\Http\Controllers;

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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:Rekayasa,Non Rekayasa',
        ]);

        $jurusan = Jurusan::create($validated);

        return response()->json([
            'message' => 'Jurusan created successfully.',
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jurusan = Jurusan::find($id);

        if (!$jurusan) {
            return response()->json([
                'message' => 'Jurusan not found.',
            ], 404);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:Rekayasa,Non Rekayasa',
        ]);

        $jurusan->update($validated);

        return response()->json([
            'message' => 'Jurusan updated successfully.',
            'data' => $jurusan,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
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
}
