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
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Cek kredensial
        if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = JWTAuth::user();

        $permissions = $user->permissions()->pluck('name')->toArray();

        $customClaims = [
            'permissions' => $permissions,
        ];

        $token = JWTAuth::customClaims($customClaims)->attempt($request->only('email', 'password'));

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
