<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  mixed ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Jika user belum login, redirect ke halaman login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Jika role user ada dalam list role yang diperbolehkan, lanjutkan request
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika role tidak sesuai, hentikan dengan error 403 Forbidden
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
