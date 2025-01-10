<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateKurikulumRequest;
use App\Http\Requests\UpdateKurikulumRequest;
use App\Models\Kurikulum;

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
}
