<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('instansi');

        // 🔐 Filter berdasarkan role:
        // - Super Admin bisa lihat semua user dari semua instansi
        // - Selain Super Admin hanya melihat user di instansinya sendiri
        if (auth()->user()->role !== 'super_admin') {
            $query->where('InstansiID', auth()->user()->InstansiID);
        }

        // 🔍 Filter tambahan untuk Super Admin berdasarkan Instansi
        if (auth()->user()->role === 'super_admin' && $request->filled('instansi')) {
            $query->where('InstansiID', $request->instansi);
        }

        // 🔍 Filter role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // 🔍 Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🔍 Pencarian nama, email, atau nama instansi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhereHas('instansi', function ($instansi) use ($search) {
                        $instansi->where('NamaSekolah', 'LIKE', "%{$search}%");
                    });
            });
        }

        $users = $query->latest()->paginate(10);

        // 📦 Data untuk filter dropdown (khusus Super Admin)
        $instansis = Instansi::where('Status', 'Aktif')->get();

        if (auth()->user()->role === 'super_admin') {
            return view('users.index', compact('users', 'instansis'));
        }

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            // admin_sekolah tidak boleh membuat user super_admin
            'role'     => 'required|in:admin_sekolah,petugas,teknisi',
            'status'   => 'required|in:active,inactive,suspended',
            'phone'    => 'nullable|string|max:20',
        ]);

        User::create([
            'InstansiID' => Auth::user()->InstansiID, // inject otomatis dari login
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
            'status'     => $request->status,
            'phone'      => $request->phone,
        ]);

        return redirect()->route('user.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Pastikan user yang dilihat memang dalam instansi yang sama
        $this->authorizeInstansi($user);

        $user->load('instansi');

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorizeInstansi($user);

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorizeInstansi($user);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'role'     => 'required|in:admin_sekolah,petugas,teknisi',
            'status'   => 'required|in:active,inactive,suspended',
            'phone'    => 'nullable|string|max:20',
        ]);

        $data = [
            'name'       => $request->name,
            'email'      => $request->email,
            'role'       => $request->role,
            'status'     => $request->status,
            'phone'      => $request->phone,
            // InstansiID tidak boleh diubah
            'InstansiID' => Auth::user()->InstansiID,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('user.index')
            ->with('success', 'User berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorizeInstansi($user);

        // Tidak boleh hapus diri sendiri
        if ($user->id === Auth::id()) {
            return redirect()->route('user.index')
                ->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        // Tidak boleh hapus super_admin
        if ($user->role === 'super_admin') {
            return redirect()->route('user.index')
                ->with('error', 'Tidak dapat menghapus akun super admin.');
        }

        $user->delete();

        return redirect()->route('user.index')
            ->with('success', 'User berhasil dihapus');
    }

    // -------------------------------------------------------------------------
    // HELPER — cek apakah user target ada di instansi yang sama
    // -------------------------------------------------------------------------
    private function authorizeInstansi(User $user): void
    {
        if ($user->InstansiID !== Auth::user()->InstansiID) {
            abort(403, 'Anda tidak memiliki akses ke data user ini.');
        }
    }

    /**
     * Update user status (untuk toggle status)
     */
    public function updateStatus(User $user)
    {
        $statuses = ['active', 'inactive', 'suspended'];
        $currentIndex = array_search($user->status, $statuses);
        $nextIndex = ($currentIndex + 1) % count($statuses);
        
        $user->update([
            'status' => $statuses[$nextIndex]
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Status user berhasil diubah');
    }
}