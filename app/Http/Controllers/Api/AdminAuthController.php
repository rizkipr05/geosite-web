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
            'password' => ['required','string'],
        ]);

        /** @var User|null $user */
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password) || $user->role !== 'admin') {
            return response()->json(['message' => 'Email/password salah atau bukan admin'], 401);
        }

        // jti disimpan ke DB untuk kontrol revoke
        $jti = (string) Str::uuid();

        // bikin token JWT + custom claim jti
        $token = JWTAuth::customClaims(['jti' => $jti, 'role' => 'admin'])->fromUser($user);

        // ambil TTL dari config (menit)
        $ttlMinutes = config('jwt.ttl'); // default 60
        $expiresAt = now()->addMinutes($ttlMinutes);

        AdminToken::create([
            'user_id' => $user->id,
            'jti' => $jti,
            'token_hash' => hash('sha256', $token),
            'expires_at' => $expiresAt,
        ]);

        // simpan JWT di HttpOnly cookie
        return response()->json(['message' => 'OK'])
            ->cookie(
                cookie('admin_token', $token, $ttlMinutes, '/', null, false, true, false, 'Lax')
            );
    }

    public function me()
    {
        $user = auth()->user();
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]);
    }

    public function logout(Request $request)
    {
        // token dari cookie
        $token = $request->cookie('admin_token');
        if (!$token) {
            return response()->json(['message' => 'No token'], 400);
        }

        // decode untuk ambil jti
        try {
            $payload = JWTAuth::setToken($token)->getPayload();
            $jti = $payload->get('jti');
            
            AdminToken::where('jti', $jti)->update(['revoked_at' => now()]);
        } catch (\Exception $e) {
            // ignore if token invalid
        }

        return response()->json(['message' => 'Logged out'])
            ->withoutCookie('admin_token');
    }
}
