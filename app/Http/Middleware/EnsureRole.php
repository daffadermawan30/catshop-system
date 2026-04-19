<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRole
{
    /**
     * Periksa apakah user yang login memiliki role yang dibutuhkan.
     * Jika tidak, redirect ke halaman yang sesuai.
     */
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        // Jika belum login, redirect ke halaman login
        if (! $request->user()) {
            return redirect('/login');
        }

        $userRole = $request->user()->role?->name;

        // Jika role user tidak termasuk dalam role yang diizinkan
        if (! in_array($userRole, $roles)) {
            abort(403, 'Akses ditolak. Kamu tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}
