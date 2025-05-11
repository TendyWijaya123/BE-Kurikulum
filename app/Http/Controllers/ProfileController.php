<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Get the authenticated user's profile
     */
    public function getProfile()
    {
        try {
            $user = Auth::user();

            if ($user instanceof \App\Models\Dosen) {
                $user->load('jurusan');
                $prodis = $user->prodi()->get()->map(function ($prodi) {
                    return [
                        'id' => $prodi->id,
                        'name' => $prodi->name,
                    ];
                });

                return response()->json([
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->nama,
                        'email' => $user->email,
                        'jurusan' => $user->jurusan ? [
                            'id' => $user->jurusan->id,
                            'nama' => $user->jurusan->nama
                        ] : null,
                        'prodis' => $prodis,
                        'role' => $user->getRoleNames()->first()
                    ]
                ]);
            }

            $user->load('prodi');

            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'prodi' => $user->prodi ? [
                        'id' => $user->prodi->id,
                        'name' => $user->prodi->name
                    ] : null,
                    'role' => $user->getRoleNames()->first()
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in GetProfile Method:', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update user password
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $user = Auth::user();

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['error' => 'Password lama salah'], 422);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json(['message' => 'Berhasil mengubah password']);

        } catch (\Exception $e) {
            Log::error('Error in UpdatePassword Method:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Gagal mengubah password'], 500);
        }
    }
}