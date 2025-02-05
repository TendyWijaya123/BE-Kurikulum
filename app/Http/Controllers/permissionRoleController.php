<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class permissionRoleController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua Role dan Permission
        $roles = Role::all();
        $permissions = Permission::all();

        // Bangun matriks Role-Permission
        $matrix = [];
        foreach ($roles as $role) {
            $row = [];
            foreach ($permissions as $permission) {
                // Cek apakah ada relasi antara Role dan Permission di tabel pivot
                $hasRelation = $role->permissions->contains($permission);
                $row[] = $hasRelation; // true jika ada, false jika tidak
            }
            $matrix[] = $row;
        }

        // Return data matriks, Role, dan Permission ke response JSON
        return response()->json([
            'roles' => $roles,
            'permissions' => $permissions,
            'matrix' => $matrix,
        ]);
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'updates' => 'required|array',
                'updates.*.role_id' => 'required|exists:roles,id',
                'updates.*.permission_id' => 'required|exists:permissions,id',
                'updates.*.has_relation' => 'required|boolean',
            ]);

            $updates = $validated['updates'];

            foreach ($updates as $update) {
                $roleId = $update['role_id'];
                $permissionId = $update['permission_id'];
                $hasRelation = $update['has_relation'];

                // Ambil Role berdasarkan ID
                $role = Role::find($roleId);

                if (!$role) {
                    return response()->json(['error' => 'Role not found'], 404);
                }

                // Tambahkan atau hapus hubungan di tabel pivot
                if ($hasRelation) {
                    // Tambahkan relasi jika belum ada
                    $role->permissions()->syncWithoutDetaching([$permissionId]);
                } else {
                    // Hapus relasi jika ada
                    $role->permissions()->detach($permissionId);
                }
            }

            DB::commit();

            return response()->json(['message' => 'Matrix updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to update matrix.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
