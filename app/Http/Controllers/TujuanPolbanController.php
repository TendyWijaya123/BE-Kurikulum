<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpsertTujuanPolbanRequest;
use App\Models\TujuanPolban;
use Illuminate\Http\Request;

class TujuanPolbanController extends Controller
{
    public function upsert(UpsertTujuanPolbanRequest $request)
    {
        try {
            $validated = $request->validated();

            $dataToUpsert = array_map(function ($item) {
                if (!isset($item['id'])) {
                    $item['id'] = null;
                }
                return $item;
            }, $validated['tujuan_polbans']);

            TujuanPolban::upsert($dataToUpsert, ['id'], ['tujuan_polban', 'vmt_polban_id']);

            return response()->json(['message' => 'Data tujuan polban berhasil disimpan atau diperbarui'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
        }
    }



    public function delete($id)
    {
        try {
            $tujuanPolban = TujuanPolban::find($id);

            if (!$tujuanPolban) {
                return response()->json(['error' => 'Tujuan polban tidak ditemukan'], 404);
            }

            $tujuanPolban->delete();

            return response()->json(['message' => 'Data tujuan polban berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()], 500);
        }
    }
}
