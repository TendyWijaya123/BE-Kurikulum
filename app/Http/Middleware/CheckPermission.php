<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            // Cek apakah user memiliki permission yang diberikan
            $hasPermission = $user->hasPermission($permission);

            // Log hasil pengecekan permission
            Log::info('Checking permission for: ' . $permission . ' - Has permission: ' . ($hasPermission ? 'Yes' : 'No'));

            // Jika user tidak memiliki permission, kembalikan respon forbidden
            if (!$hasPermission) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is invalid or expired'], 401);
        }

        return $next($request);
    }
}
