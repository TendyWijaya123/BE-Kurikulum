<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProdiRequest;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProdiController extends Controller
{
    public function index()
    {
        $prodis = Prodi::with('jurusan')->paginate(10);

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
            return response()->json(['message' => 'Prodi not found.'], 404);
        }

        return response()->json(['data' => $prodi], 200);
    }

    public function update(Request $request, $id)
    {
        $prodi = Prodi::find($id);

        if (!$prodi) {
            return response()->json(['message' => 'Prodi not found.'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'jenjang' => 'required|in:D3,D4,S1,S2,S3',
            'kode' => 'required|string|size:2|unique:prodis,kode,' . $prodi->id,
            'jurusan_id' => 'required|exists:jurusans,id',
            'is_active' => 'nullable|boolean',
        ]);

        $prodi->update($validated);

        return response()->json([
            'message' => 'Prodi updated successfully.',
            'data' => $prodi,
        ], 200);
    }

    public function destroy($id)
    {
        $prodi = Prodi::find($id);

        if (!$prodi) {
            return response()->json(['message' => 'Prodi not found.'], 404);
        }

        $prodi->delete();

        return response()->json(['message' => 'Prodi deleted successfully.'], 200);
    }

    public function getProdiDropdown()
    {
        $prodis = Prodi::with('jurusan:id,nama')->get(['id', 'name', 'kode', 'jurusan_id']);

        return response()->json($prodis);
    }

    public function getProdiWithKurikulumDropdown()
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ($user->hasRole('P2MPP')) {
            $prodis = Prodi::with('kurikulums')->get(['id', 'name']);
        } else {
            if (!$user->prodi_id) {
                return response()->json(['error' => 'User tidak memiliki Prodi ID'], 400);
            }

            $prodi = Prodi::with('kurikulums')
                ->where('id', $user->prodi_id)
                ->first(['id', 'name']);

            if (!$prodi) {
                return response()->json(['error' => 'Prodi tidak ditemukan'], 404);
            }

            $prodis = collect([$prodi]);
        }

        return response()->json($prodis);
    }


    public function getProdiDropdownByJurusanDosen(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user->hasRole('P2MPP')) {
                // Jika user adalah P2MPP
                if ($request->has('jurusanId')) {
                    $jurusanId = $request->input('jurusanId');
                    $prodis = empty($jurusanId)
                        ? Prodi::get(['id', 'name'])
                        : Prodi::where('jurusan_id', $jurusanId)->get(['id', 'name']);
                } else {
                    $prodis = Prodi::get(['id', 'name']);
                }
            } else {
                // Jika user bukan P2MPP, diasumsikan dosen
                $dosen = Auth::guard('dosen')->user() ?? $user;

                if (!$dosen || !isset($dosen->jurusan_id)) {
                    return response()->json(['message' => 'Dosen tidak ditemukan'], 404);
                }
                $jurusanId = $dosen->jurusan_id;
                $prodis = Prodi::where('jurusan_id', $jurusanId)->get(['id', 'name']);
            }

            return response()->json($prodis);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Terjadi kesalahan.'], 500);
        }
    }
}
