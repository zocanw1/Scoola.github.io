<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        // Belum login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Role tidak sesuai
        if (Auth::user()->role !== $role) {
            abort(403, 'Akses ditolak');
        }

        return $next($request);
    }
}
