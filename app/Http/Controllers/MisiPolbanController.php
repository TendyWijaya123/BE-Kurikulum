<?php

namespace App\Http\Controllers;

use App\Models\MisiPolban;
use Illuminate\Http\Request;

class MisiPolbanController extends Controller
{
    /**
     * Upsert data Misi Polban
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upsert(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'misi_polbans' => 'required|array',
                'misi_polbans.*.id' => 'nullable',
                'misi_polbans.*.misi_polban' => 'required|string|max:255',
                'misi_polbans.*.vmt_polban_id' => 'required|exists:vmt_polbans,id',
            ]);

            // Persiapan data untuk upsert
            $dataToUpsert = array_map(function ($item) {
                if (!isset($item['id'])) {
                    $item['id'] = null; // Atur null jika tidak ada ID
                }
                return $item;
            }, $validated['misi_polbans']);

            // Upsert data
            MisiPolban::upsert($dataToUpsert, ['id'], ['misi_polban', 'vmt_polban_id']);

            return response()->json(['message' => 'Data misi polban berhasil disimpan atau diperbarui'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validasi gagal', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete data Misi Polban by ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try {
            // Cari data berdasarkan ID
            $misiPolban = MisiPolban::find($id);

            if (!$misiPolban) {
                return response()->json(['error' => 'Misi polban tidak ditemukan'], 404);
            }

            // Hapus data
            $misiPolban->delete();

            return response()->json(['message' => 'Data misi polban berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
        }
    }
}
