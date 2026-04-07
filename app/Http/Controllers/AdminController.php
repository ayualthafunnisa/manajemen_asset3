<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display a listing of admins for a specific school
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Super admin can see all admins, admin_sekolah only sees their school's admins
        if ($user->role === 'super_admin') {
            $instansiId = $request->get('instansi_id');
            $query = User::where('role', 'admin_sekolah');
            
            if ($instansiId) {
                $query->where('InstansiID', $instansiId);
            }
            
            $admins = $query->with('instansi')->paginate(10);
            $instansis = Instansi::all();
            
            return view('admin.index', compact('admins', 'instansis'));
        } else {
            // Admin sekolah can only see admins from their school
            $admins = User::where('InstansiID', $user->InstansiID)
                ->where('role', 'admin_sekolah')
                ->get();
            
            return view('admin.school-admins', compact('admins'));
        }
    }
    
    /**
     * Store a newly created admin
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Validate based on role
        if ($user->role === 'super_admin') {
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'role' => 'required|in:admin_sekolah,petugas,teknisi',
                'InstansiID' => 'required|exists:instansis,InstansiID',
                'phone' => 'nullable|string|max:20',
                'status' => 'required|in:active,pending,suspended',
            ];
        } else {
            // Admin sekolah can only add admin_sekolah role
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'phone' => 'nullable|string|max:20',
                'status' => 'required|in:active,pending,suspended',
            ];
        }
        
        $validated = $request->validate($rules);
        
        // Set InstansiID for admin_sekolah
        if ($user->role === 'admin_sekolah') {
            $validated['InstansiID'] = $user->InstansiID;
            $validated['role'] = 'admin_sekolah';
        }
        
        // Hash password
        $validated['password'] = Hash::make($validated['password']);
        
        // Generate activation token if status is pending
        if ($validated['status'] === 'pending') {
            $validated['activation_token'] = Str::random(60);
            $validated['activation_token_expires_at'] = now()->addDays(7);
        }
        
        $admin = User::create($validated);
        
        // Send welcome email with activation link if pending
        if ($validated['status'] === 'pending') {
            $this->sendActivationEmail($admin);
        }
        
        return redirect()->back()->with('success', 'Admin berhasil ditambahkan!');
    }
    
    /**
     * Show the form for editing the specified admin
     */
    public function edit($id)
    {
        $admin = User::findOrFail($id);
        $currentUser = Auth::user();
        
        // Check permission
        if ($currentUser->role === 'admin_sekolah' && $admin->InstansiID !== $currentUser->InstansiID) {
            abort(403, 'Unauthorized action.');
        }
        
        $instansis = Instansi::all();
        
        return view('admin.edit', compact('admin', 'instansis'));
    }
    
    /**
     * Update the specified admin
     */
    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);
        $currentUser = Auth::user();
        
        // Check permission
        if ($currentUser->role === 'admin_sekolah' && $admin->InstansiID !== $currentUser->InstansiID) {
            abort(403, 'Unauthorized action.');
        }
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($admin->id),
            ],
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,pending,suspended',
        ];
        
        // Super admin can change role and instansi
        if ($currentUser->role === 'super_admin') {
            $rules['role'] = 'required|in:admin_sekolah,petugas,teknisi';
            $rules['InstansiID'] = 'required|exists:instansis,InstansiID';
        }
        
        // Only super admin can change password from here
        if ($request->filled('password') && $currentUser->role === 'super_admin') {
            $rules['password'] = 'string|min:8|confirmed';
        }
        
        $validated = $request->validate($rules);
        
        // Hash password if provided
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }
        
        // Handle activation token if status changed to pending
        if ($validated['status'] === 'pending' && $admin->status !== 'pending') {
            $validated['activation_token'] = Str::random(60);
            $validated['activation_token_expires_at'] = now()->addDays(7);
            $this->sendActivationEmail($admin, $validated['activation_token']);
        }
        
        $admin->update($validated);
        
        return redirect()->route('admin.index')->with('success', 'Admin berhasil diperbarui!');
    }
    
    /**
     * Remove the specified admin
     */
    public function destroy($id)
    {
        $admin = User::findOrFail($id);
        $currentUser = Auth::user();
        
        // Prevent deleting yourself
        if ($currentUser->id === $admin->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }
        
        // Check permission
        if ($currentUser->role === 'admin_sekolah' && $admin->InstansiID !== $currentUser->InstansiID) {
            abort(403, 'Unauthorized action.');
        }
        
        // Delete avatar if exists
        if ($admin->avatar && Storage::disk('public')->exists($admin->avatar)) {
            Storage::disk('public')->delete($admin->avatar);
        }
        
        $admin->delete();
        
        return redirect()->back()->with('success', 'Admin berhasil dihapus!');
    }
    
    /**
     * Activate admin account
     */
    public function activate($token)
    {
        $admin = User::where('activation_token', $token)
            ->where('activation_token_expires_at', '>', now())
            ->first();
        
        if (!$admin) {
            return redirect()->route('login')
                ->with('error', 'Token aktivasi tidak valid atau sudah kadaluarsa!');
        }
        
        $admin->status = 'active';
        $admin->activation_token = null;
        $admin->activation_token_expires_at = null;
        $admin->email_verified_at = now();
        $admin->save();
        
        return redirect()->route('login')
            ->with('success', 'Akun berhasil diaktifkan! Silakan login.');
    }
    
    /**
     * Resend activation email
     */
    public function resendActivation($id)
    {
        $admin = User::findOrFail($id);
        
        if ($admin->status !== 'pending') {
            return back()->with('error', 'Akun ini sudah aktif!');
        }
        
        $admin->activation_token = Str::random(60);
        $admin->activation_token_expires_at = now()->addDays(7);
        $admin->save();
        
        $this->sendActivationEmail($admin);
        
        return back()->with('success', 'Email aktivasi telah dikirim ulang!');
    }
    
    /**
     * Send activation email to admin
     */
    private function sendActivationEmail($admin, $token = null)
    {
        $token = $token ?? $admin->activation_token;
        $activationLink = route('admin.activate', $token);
        
        // You can implement email sending here
        // Mail::to($admin->email)->send(new AdminActivationMail($admin, $activationLink));
        
        // For now, just log or store the link
        \Log::info('Activation link for ' . $admin->email . ': ' . $activationLink);
    }
    
    /**
     * Get admins for a specific school (AJAX)
     */
    public function getSchoolAdmins($instansiId)
    {
        $currentUser = Auth::user();
        
        if ($currentUser->role === 'admin_sekolah' && $currentUser->InstansiID != $instansiId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $admins = User::where('InstansiID', $instansiId)
            ->where('role', 'admin_sekolah')
            ->select('id', 'name', 'email', 'phone', 'status')
            ->get();
        
        return response()->json($admins);
    }
}