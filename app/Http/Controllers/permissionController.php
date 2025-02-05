<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class permissionController extends Controller
{
    public function index(){
        $roles = Permission::all();

        return response()->json($roles);
    }

    public function store(Request $request){
        try {
            $dataList = $request->all();

            foreach ($dataList as $data){
                Permission::updateOrCreate(
                    ['id' => $data['_id'] ?? null],
                    [
                        'name' => $data['name'],
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
        $roles = Permission::select('id', 'name')->get();

        return response()->json($roles);
    }

    public function destroy($id){
        $sksu = Permission::find($id);
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
            $permissions = Permission::whereIn('id', $ids)->get();

            if ($permissions->isEmpty()) {
                return response()->json([
                    'data' => $ids,
                    'message' => 'Data tidak ditemukan untuk ID yang diberikan',
                ], 404);
            }

            // Loop untuk menghapus data terkait, jika ada
            foreach ($permissions as $permission){
                // Hapus data SKSU
                $permission->delete();
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
