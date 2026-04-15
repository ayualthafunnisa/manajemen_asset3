<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Kalau sudah login, langsung ke dashboard — tidak bisa back ke login
        if (Auth::check()) {
            return $this->redirectToDashboard(Auth::user());
        }

        return response()->view('auth.login')->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma'        => 'no-cache',
            'Expires'       => '0',
        ]);
    }
 
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
        }
 
        $request->session()->regenerate();
        $user = Auth::user();
 
        /* Cek lisensi untuk admin_sekolah */
        if ($user->role === 'admin_sekolah') {
            $license = $user->license;
 
            if (!$license) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['email' => 'Akun ini belum memiliki lisensi.']);
            }
 
            if ($license->payment_status === 'pending') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()
                    ->withErrors(['email' => 'Pembayaran Anda masih pending. Selesaikan pembayaran untuk masuk.'])
                    ->with('pending_order', $license->kode_lisensi);
            }
 
            if (!$license->isValid()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['email' => 'Lisensi Anda telah expired. Silakan perbarui lisensi.']);
            }
        }
 
        return $this->redirectToDashboard($user);
    }
 
    private function redirectToDashboard($user)
    {
        return match ($user->role) {
            'super_admin'    => redirect()->route('dashboard.superadmin'),
            'admin_sekolah'  => redirect()->route('dashboard.admin'),
            'petugas'        => redirect()->route('dashboard.staf'),
            'teknisi'        => redirect()->route('dashboard.teknisi'),
            default          => redirect('/'),
        };
    }
 
    public function logout(Request $request)
    {
        // Hancurkan session dengan lebih ketat
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Set cache control headers untuk mencegah back after logout
        return redirect()->route('login')->withHeaders([
            'Cache-Control' => 'no-cache, no-store, must-revalidate, private',
            'Pragma' => 'no-cache',
            'Expires' => 'Thu, 01 Jan 1970 00:00:00 GMT'
        ]);
    }
}