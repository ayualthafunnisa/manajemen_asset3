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
                return back()->withErrors(['email' => 'Akun ini belum memiliki lisensi.']);
            }
 
            if ($license->payment_status === 'pending') {
                Auth::logout();
                return back()
                    ->withErrors(['email' => 'Pembayaran Anda masih pending. Selesaikan pembayaran untuk masuk.'])
                    ->with('pending_order', $license->kode_lisensi);
            }
 
            if (!$license->isValid()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Lisensi Anda telah expired. Silakan perbarui lisensi.']);
            }
        }
 
        return redirect()->intended(route('dashboard'));
    }
 
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}