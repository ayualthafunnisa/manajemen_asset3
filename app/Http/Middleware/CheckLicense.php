<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckLicense
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->role === 'admin_sekolah') {
            $license = $user->license;

            if (!$license || !$license->isValid()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Lisensi Anda sudah expired atau tidak aktif. Hubungi administrator.']);
            }

            // Peringatan sisa ≤ 30 hari (tidak logout, hanya flash)
            if ($license->daysRemaining() <= 30) {
                session()->flash(
                    'license_warning',
                    'Lisensi Anda akan berakhir dalam ' . $license->daysRemaining() . ' hari lagi!'
                );
            }
        }

        return $next($request);
    }
}