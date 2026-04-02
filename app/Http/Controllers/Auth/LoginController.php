<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {

            $request->session()->regenerate();

            $user = Auth::user();

            // 🔒 Cek lisensi (khusus admin sekolah)
            if ($user->role === 'admin_sekolah') {
                $license = $user->license;

                if (!$license) {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Lisensi tidak ditemukan.',
                    ]);
                }

                if (!$license->isValid()) {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Lisensi sudah expired / tidak aktif.',
                    ]);
                }
            }

            // Cek status akun
            if ($user->status !== 'active') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak aktif. Hubungi administrator.',
                ])->onlyInput('email');
            }

            return match ($user->role) {
                'super_admin'   => redirect()->route('dashboard.superadmin'),
                'admin_sekolah' => redirect()->route('dashboard.admin'),
                'petugas'       => redirect()->route('dashboard.staf'),   // enum: petugas (bukan staf_asset)
                'teknisi'       => redirect()->route('dashboard.teknisi'),
                default         => redirect('/'),
            };
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login')->with('success', 'Logout berhasil!');
    }
}