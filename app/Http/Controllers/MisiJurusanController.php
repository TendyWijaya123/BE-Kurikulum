<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpsertMisiJurusanRequest;
use App\Models\MisiJurusan;
use Illuminate\Http\Request;

class MisiJurusanController extends Controller
{
    public function upsert(UpsertMisiJurusanRequest $request)
    {
        try {
            $validated = $request->validated();

            $dataToUpsert = array_map(function ($item) {
                if (!isset($item['id'])) {
                    $item['id'] = null;
                }
                return $item;
            }, $validated['misi_jurusans']);

            MisiJurusan::upsert($dataToUpsert, ['id'], ['misi_jurusan', 'vmt_jurusan_id']);

            return response()->json(['message' => 'Data misi jurusan berhasil disimpan atau diperbarui'], 200);
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
