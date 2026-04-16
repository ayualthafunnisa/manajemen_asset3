<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckCrudPermission
{
    /**
     * Role yang hanya boleh VIEW (index & show).
     * Role lain (admin_sekolah, petugas) boleh full CRUD.
     */
    protected array $viewOnlyRoles = ['super_admin'];

    /**
     * Method HTTP yang termasuk aksi write/mutasi.
     */
    protected array $writeMethods = ['POST', 'PUT', 'PATCH', 'DELETE'];

    /**
     * Named-route suffix yang termasuk aksi write.
     * Contoh: asset.create, asset.store, asset.edit, asset.update, asset.destroy
     */
    protected array $writeRouteActions = ['create', 'store', 'edit', 'update', 'destroy'];

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $role = $user->role;

        // Kalau bukan view-only role, langsung lanjut
        if (!in_array($role, $this->viewOnlyRoles)) {
            return $next($request);
        }

        // Cek apakah request ini adalah aksi write berdasarkan HTTP method
        if (in_array($request->method(), $this->writeMethods)) {
            return $this->denyAccess($request);
        }

        // Cek berdasarkan nama route (untuk GET ke /create dan /edit)
        $routeName = $request->route()?->getName() ?? '';
        foreach ($this->writeRouteActions as $action) {
            if (str_ends_with($routeName, '.' . $action)) {
                return $this->denyAccess($request);
            }
        }

        return $next($request);
    }

    protected function denyAccess(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Anda tidak memiliki izin untuk melakukan aksi ini.',
            ], 403);
        }

        return redirect()->back()->with('error', 'Anda hanya memiliki akses untuk melihat data. Aksi tambah, edit, dan hapus tidak diizinkan untuk role Anda.');
    }
}