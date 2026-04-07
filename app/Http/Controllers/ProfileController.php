<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show profile settings page
     */
    public function settings()
    {
        $user = Auth::user();
        $instansi = $user->instansi;
        
        return view('profile.settings', compact('user', 'instansi'));
    }
    
    /**
     * Show account settings page
     */
    public function accountSettings()
    {
        $user = Auth::user();
        
        return view('profile.account', compact('user'));
    }
    
    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);
        
        $user->update($validated);
        
        return back()->with('success', 'Profil berhasil diperbarui!');
    }
    
    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah!']);
        }
        
        // Update password
        $user->password = Hash::make($request->password);
        $user->save();
        
        return back()->with('success', 'Password berhasil diubah!');
    }
    
    /**
     * Update user avatar/photo
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user = Auth::user();
        
        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        // Upload new avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
        $user->save();
        
        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }
    
    /**
     * Update instansi data (for admin_sekolah)
     */
    public function updateInstansi(Request $request, $id)
    {
        $instansi = Instansi::findOrFail($id);
        
        // Check permission
        $user = Auth::user();
        if ($user->role !== 'super_admin' && $user->instansi_id != $id) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'NamaSekolah' => 'required|string|max:255',
            'NPSN' => 'required|string|max:20|unique:instansis,NPSN,' . $id . ',InstansiID',
            'JenjangSekolah' => 'required|in:SD,SMP,SMA,SMK',
            'EmailSekolah' => 'required|email|unique:instansis,EmailSekolah,' . $id . ',InstansiID',
            'NamaKepalaSekolah' => 'nullable|string|max:255',
            'NIPKepalaSekolah' => 'nullable|string|max:50',
            'KodePos' => 'nullable|string|max:10',
            'Status' => 'required|in:Aktif,Inactive',
            'Logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Handle logo upload
        if ($request->hasFile('Logo')) {
            // Delete old logo
            if ($instansi->Logo && Storage::disk('public')->exists($instansi->Logo)) {
                Storage::disk('public')->delete($instansi->Logo);
            }
            
            // Upload new logo
            $path = $request->file('Logo')->store('logos', 'public');
            $validated['Logo'] = $path;
        }
        
        $instansi->update($validated);
        
        return redirect()->back()->with('success', 'Data sekolah berhasil diperbarui!');
    }
}