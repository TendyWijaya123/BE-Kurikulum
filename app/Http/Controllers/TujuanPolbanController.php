<?php

namespace App\Http\Controllers;

use App\Models\TujuanPolban;
use Illuminate\Http\Request;

class TujuanPolbanController extends Controller
{
    /**
     * Upsert data Tujuan Polban
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upsert(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'tujuan_polbans' => 'required|array',
                'tujuan_polbans.*.id' => 'nullable',
                'tujuan_polbans.*.tujuan_polban' => 'required|string|max:255',
                'tujuan_polbans.*.vmt_polban_id' => 'required|exists:vmt_polbans,id',
            ]);

            // Persiapan data untuk upsert
            $dataToUpsert = array_map(function ($item) {
                if (!isset($item['id'])) {
                    $item['id'] = null; // Atur null jika ID tidak ada
                }
                return $item;
            }, $validated['tujuan_polbans']);

            // Upsert data
            TujuanPolban::upsert($dataToUpsert, ['id'], ['tujuan_polban', 'vmt_polban_id']);

            return response()->json(['message' => 'Data tujuan polban berhasil disimpan atau diperbarui'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validasi gagal', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete data Tujuan Polban by ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try {
            // Cari data berdasarkan ID
            $tujuanPolban = TujuanPolban::find($id);

            if (!$tujuanPolban) {
                return response()->json(['error' => 'Tujuan polban tidak ditemukan'], 404);
            }

            // Hapus data
            $tujuanPolban->delete();

            return response()->json(['message' => 'Data tujuan polban berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
        }
    }
}
