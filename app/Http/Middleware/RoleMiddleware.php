<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Pecah setiap role yang mungkin mengandung koma
        $allowedRoles = [];
        foreach ($roles as $role) {
            foreach (explode(',', $role) as $r) {
                $allowedRoles[] = trim($r);
            }
        }

        if (!in_array(auth()->user()->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        return $next($request);
    }
}