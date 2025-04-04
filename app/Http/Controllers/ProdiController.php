<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProdiRequest;
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

    public function store(StoreProdiRequest $request)
    {
        $prodi = Prodi::create($request->validated());

        return response()->json([
            'message' => 'Prodi created successfully.',
            'data' => $prodi,
        ], 201);
    }



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

    public function update(Request $request, $id)
    {
        $prodi = Prodi::find($id);

        if (!$prodi) {
            return response()->json([
                'message' => 'Prodi not found.',
            ], 404);
        }

        // Menambahkan pengecualian pada validasi unique untuk kode
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'jenjang' => 'required|in:D3,D4,S1,S2,S3',
            'kode' => 'required|string|max:50|unique:prodis,kode,' . $prodi->id, // Menambahkan pengecualian untuk kode
            'jurusan_id' => 'required|exists:jurusans,id',
            'is_active' => 'nullable|boolean',
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

    public function getProdiWithKurikulumDropdown()
    {
        $prodis = Prodi::with('kurikulums')->get(['id', 'name']);
        return response()->json($prodis);
    }
}
