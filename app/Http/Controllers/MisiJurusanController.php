<?php

namespace App\Http\Controllers;

use App\Models\MisiJurusan;
use Illuminate\Http\Request;

class MisiJurusanController extends Controller
{
    public function upsert(Request $request)
    {
        try {
            $validated = $request->validate([
                'misi_jurusans' => 'required|array',
                'misi_jurusans.*.id' => 'nullable',
                'misi_jurusans.*.misi_jurusan' => 'required|string|max:255',
                'misi_jurusans.*.vmt_jurusan_id' => 'required|exists:vmt_jurusans,id',
            ]);

            // Memperbaiki data untuk menghilangkan id kosong yang tidak diinginkan
            $dataToUpsert = array_map(function ($item) {
                // Jika id tidak ada (misalnya data baru), set id ke null
                if (!isset($item['id'])) {
                    $item['id'] = null; // Atau bisa dibiarkan saja jika `id` auto-increment
                }
                return $item;
            }, $validated['misi_jurusans']);

            // Lakukan upsert
            MisiJurusan::upsert($dataToUpsert, ['id'], ['misi_jurusan', 'vmt_jurusan_id']);

            return response()->json(['message' => 'Data misi jurusan berhasil disimpan atau diperbarui'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validasi gagal', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
        }
    }


    public function delete($id)
    {
        try {
            $misiJurusan = MisiJurusan::find($id);

            if (!$misiJurusan) {
                return response()->json(['error' => 'Misi jurusan tidak ditemukan'], 404);
            }
            $misiJurusan->delete();
            return response()->json(['message' => 'Data misi jurusan berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
        }
    }
}
