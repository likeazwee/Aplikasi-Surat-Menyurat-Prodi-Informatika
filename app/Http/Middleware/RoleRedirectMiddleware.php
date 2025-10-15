<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleRedirectMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika pengguna sudah login
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->hasRole('kaprodi')) {
                return redirect()->route('kaprodi.dashboard');
            }
            
            // Jika bukan admin atau kaprodi, biarkan ke dashboard mahasiswa
            // atau tujuan awal mereka
        }
        
        // Lanjutkan permintaan jika tidak ada kondisi yang terpenuhi
        return $next($request);
    }
}