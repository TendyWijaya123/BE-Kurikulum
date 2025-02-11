<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    public function index()
    {
        $dosen = Dosen::with('role')->get();

        return response()->json($dosen);
    }


    public function store(Request $request){
        try {
            $dataList = $request->all();

            foreach ($dataList as $data){
                Dosen::updateOrCreate(
                    ['id' => $data['_id'] ?? null],
                    [
                        'nip' => $data['nip'],
                        'nama' => $data['nama'],
                        'email' => $data['email'],
                        'password' => Hash::make('password123'),
                        'role_id' => $data['role_id']
                    ]
                );
            }

            return response()->json([
                'success' => 'Data berhasil disimpan',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat menyimpan data',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getRoleDropdown()
    {
        $roles = Dosen::select('id', 'name')->get();

        return response()->json($roles);
    }

    public function destroy($id){
        $sksu = Dosen::find($id);
        if (!$sksu) {
            return response()->json([
                'message' => 'role not found.',
            ], 404);
        }
        $sksu->delete();

        return response()->json([
            'message' => `role berhasil dihapus`
        ], 200);
    }

    public function destroyPermissions(Request $request)
    {
        try {
            // Ambil daftar ID dari request
            $data = $request->all();

            if (empty($data) || !is_array($data)) {
                return response()->json([
                    'data' => $data,
                    'message' => 'Harap sertakan daftar ID yang valid untuk dihapus',
                ], 400);
            }

            $ids = array_column($data, '_id');

            // Cari SKSU berdasarkan ID
            $dosens = Dosen::whereIn('id', $ids)->get();

            if ($dosens->isEmpty()) {
                return response()->json([
                    'data' => $ids,
                    'message' => 'Data tidak ditemukan untuk ID yang diberikan',
                ], 404);
            }

            // Loop untuk menghapus data terkait, jika ada
            foreach ($dosens as $dosen){
                // Hapus data SKSU
                $dosen->delete();
            }

            return response()->json([
                'message' => 'Data berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => $ids,
                'message' => 'Terjadi kesalahan saat menghapus data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
