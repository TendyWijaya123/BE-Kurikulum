<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateKurikulumRequest;
use App\Http\Requests\UpdateKurikulumRequest;
use App\Models\Kurikulum;
use Illuminate\Http\Request;
use App\Models\Prodi;
use Tymon\JWTAuth\Facades\JWTAuth;

class KurikulumController extends Controller
{

    public function index()
    {
        $kurikulums = Kurikulum::query()->with('prodi')->paginate(10);

        return response()->json([
            'message' => 'Daftar kurikulum berhasil diambil.',
            'data' => $kurikulums
        ], 200);
    }

    public function store(CreateKurikulumRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = false;

        $kurikulum = Kurikulum::create($data);

        return response()->json([
            'message' => 'Kurikulum berhasil dibuat.',
            'data' => $kurikulum
        ], 201);
    }

    /**
     * Memperbarui data kurikulum.
     */
    public function update(UpdateKurikulumRequest $request, $id)
    {
        $kurikulum = Kurikulum::findOrFail($id);

        $data = $request->validated();
        $kurikulum->update($data);

        return response()->json([
            'message' => 'Kurikulum berhasil diperbarui.',
            'data' => $kurikulum
        ], 200);
    }

    /**
     * Update status progress field for the active kurikulum of the current user.
     */
    public function updateStatusProgress(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $request->validate([
            'nameStatus' => 'required|string',
            'status' => 'required|in:belum,progres,selesai',
        ]);

        $kurikulum = $user->activeKurikulum();

        if (!$kurikulum) {
            return response()->json(['message' => 'Tidak ada kurikulum aktif untuk user ini'], 404);
        }

        $field = $request->input('nameStatus');
        $status = $request->input('status');

        $kurikulum->$field = $status;
        $kurikulum->save();

        return response()->json([
            'message' => 'Status progres berhasil diperbarui.',
            'data' => [
                'nameStatus' => $field,
                'status' => $kurikulum->$field,
            ]
        ], 200);
    }

    public function getCurrentKurikulum(){
        $user = JWTAuth::parseToken()->authenticate();

        if ($user->hasRole('P2MPP')) {
            return response()->json(['message' => 'User ini adalah P2MPP, tidak ada kurikulum yang aktif'], 404);
        }

        $kurikulum = $user->activeKurikulum();

        if (!$kurikulum) {
            return response()->json(['message' => 'Tidak ada kurikulum aktif untuk user ini'], 404);
        }

        return response()->json([
            'message' => 'Kurikulum aktif berhasil diambil.',
            'data' => $kurikulum
        ], 200);
    }
}
