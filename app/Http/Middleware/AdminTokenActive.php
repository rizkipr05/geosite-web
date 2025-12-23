<?php

namespace App\Http\Middleware;

use App\Models\AdminToken;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminTokenActive
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie('admin_token');
        if (!$token) return response()->json(['message' => 'Unauthenticated'], 401);

        try {
            $payload = JWTAuth::setToken($token)->getPayload();
            $jti = $payload->get('jti');

            $row = AdminToken::where('jti', $jti)
                ->whereNull('revoked_at')
                ->where('expires_at', '>', now())
                ->first();

            if (!$row) return response()->json(['message' => 'Token revoked/expired'], 401);

            // (opsional) validasi hash token
            if ($row->token_hash !== hash('sha256', $token)) {
                return response()->json(['message' => 'Token mismatch'], 401);
            }

            // set user auth dari JWT
            JWTAuth::setToken($token)->authenticate();
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        return $next($request);
    }
}
