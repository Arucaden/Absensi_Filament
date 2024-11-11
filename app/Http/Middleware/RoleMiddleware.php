<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Pastikan user terautentikasi dan memiliki role yang sesuai
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        // Redirect jika user tidak memiliki role yang sesuai
        return redirect()->route('unauthorized'); // Ganti dengan route yang sesuai
    }
}
