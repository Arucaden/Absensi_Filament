<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login user dan menghasilkan token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Verifikasi password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah.',
            ], 401);
        }

        // Buat token untuk autentikasi
        $token = $user->createToken('auth_token')->plainTextToken;

        // Response dengan token
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    /**
     * Logout user (menghapus token).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Hapus token yang aktif
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil.',
        ], 200);
    }
}
