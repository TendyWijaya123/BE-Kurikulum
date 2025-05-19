<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = JWTAuth::user();
        $customClaims = [
            'id' => $user->id,
            'name' => $user->name,
            'roles' => $user->getRoleNames(),
        ];

        if ($user->prodi) {
            $customClaims += [
                'prodiId' => $user->prodi_id,
                'isActiveProdi' => $user->prodi->is_active,
            ];
        }

        $token = JWTAuth::customClaims($customClaims)->attempt($credentials);

        return response()->json(['token' => $token]);
    }


    public function logout()
    {
        try {
            // Batalkan token JWT yang aktif
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to log out, please try again'], 500);
        }
    }


    public function me()
    {
        return response()->json(Auth::user());
    }
}
