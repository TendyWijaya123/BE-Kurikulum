<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpsertMisiPolbanRequest;
use App\Models\MisiPolban;
use Illuminate\Http\Request;

class MisiPolbanController extends Controller
{
    public function upsert(UpsertMisiPolbanRequest $request)
    {
        try {
            $validated = $request->validated();
            $dataToUpsert = array_map(function ($item) {
                if (!isset($item['id'])) {
                    $item['id'] = null;
                }
                return $item;
            }, $validated['misi_polbans']);
            MisiPolban::upsert($dataToUpsert, ['id'], ['misi_polban', 'vmt_polban_id']);
            return response()->json(['message' => 'Data misi polban berhasil disimpan atau diperbarui'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validasi gagal', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
        }
    }



    public function delete($id)
    {
        try {
            $misiPolban = MisiPolban::find($id);

            if (!$misiPolban) {
                return response()->json(['error' => 'Misi polban tidak ditemukan'], 404);
            }

            $misiPolban->delete();

            return response()->json(['message' => 'Data misi polban berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
        }
    }
}
