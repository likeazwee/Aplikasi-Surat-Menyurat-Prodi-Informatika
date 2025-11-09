<?php

namespace App\Http\Middleware; // <-- PERBAIKI BARIS INI

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) { // Jika pengguna belum login
            return redirect('login');
        }

        $user = Auth::user();

        // Jika peran pengguna ada di dalam daftar peran yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika tidak diizinkan, kembalikan ke halaman sebelumnya dengan error
        return back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}

