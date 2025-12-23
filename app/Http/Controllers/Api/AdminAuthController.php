<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password) || $user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $jti = (string) Str::uuid();
        $token = JWTAuth::customClaims(['jti' => $jti])->fromUser($user);

        AdminToken::create([
            'user_id' => $user->id,
            'jti' => $jti,
            'token_hash' => hash('sha256', $token),
            'expires_at' => now()->addMinutes(config('jwt.ttl')),
        ]);

        return response()->json(['message' => 'OK'])
            ->cookie('admin_token', $token, config('jwt.ttl'), '/', null, false, true);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout(Request $request)
    {
        $token = $request->cookie('admin_token');
        if (!$token) return response()->json(['message' => 'No token'], 400);

        $payload = JWTAuth::setToken($token)->getPayload();
        $jti = $payload->get('jti');

        AdminToken::where('jti', $jti)->update(['revoked_at' => now()]);

        return response()->json(['message' => 'Logged out'])
            ->withoutCookie('admin_token');
    }
}
