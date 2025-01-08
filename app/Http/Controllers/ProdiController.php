<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index()
    {
        $prodis = Prodi::query()
            ->with('jurusan')
            ->paginate(10);

        return response()->json($prodis);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'jenjang' => 'required|in:D3,D4,S1,S2,S3',
            'kode' => 'required|string|max:50',
            'jurusan_id' => 'required|exists:jurusans,id',
        ]);

        $prodi = Prodi::create($validated);

        return response()->json([
            'message' => 'Prodi created successfully.',
            'data' => $prodi,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $prodi = Prodi::with('jurusan')->find($id);

        if (!$prodi) {
            return response()->json([
                'message' => 'Prodi not found.',
            ], 404);
        }

        return response()->json(['data' => $prodi], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $prodi = Prodi::find($id);

        if (!$prodi) {
            return response()->json([
                'message' => 'Prodi not found.',
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'jenjang' => 'required|in:D3,D4,S1,S2,S3',
            'kode' => 'required|string|max:50',
            'jurusan_id' => 'required|exists:jurusans,id',
        ]);

        $prodi->update($validated);

        return response()->json([
            'message' => 'Prodi updated successfully.',
            'data' => $prodi,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $prodi = Prodi::find($id);

        if (!$prodi) {
            return response()->json([
                'message' => 'Prodi not found.',
            ], 404);
        }

        $prodi->delete();

        return response()->json([
            'message' => 'Prodi deleted successfully.',
        ], 200);
    }

    public function getProdiDropdown()
    {
        $prodis = Prodi::with('jurusan:id,name')
            ->get(['id', 'name']);

        return response()->json($prodis);
    }
}
