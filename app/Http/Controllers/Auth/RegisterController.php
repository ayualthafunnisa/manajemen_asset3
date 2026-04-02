<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Instansi;
use App\Models\License;
use Laravolt\Indonesia\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $provinsis = Province::all();
        return view('auth.register', compact('provinsis'));
    }

    public function register(Request $request)
    {
        $role = $request->input('role');

        if ($role === 'teknisi') {
            // Validasi khusus teknisi (tanpa data sekolah)
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users',
                'phone'    => 'nullable|string|max:15',
                'password' => 'required|string|min:8|confirmed',
                'terms'    => 'required',
                'role'     => 'required|in:teknisi',
            ]);

            User::create([
                'InstansiID' => null, // teknisi tidak terikat instansi
                'name'       => $request->name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'password'   => Hash::make($request->password),
                'role'       => 'teknisi',
                'status'     => 'active',
            ]);

            return redirect()->route('login')
                ->with('success', 'Akun teknisi berhasil dibuat! Silakan login.');
        }
        $request->validate([
            // DATA INSTANSI
            'nama_instansi' => 'required|string|max:255',
            'npsn' => 'required|string|unique:instansis,NPSN',
            'jenjang_sekolah' => 'required|in:SD,SMP,SMA,SMK',
            'email_instansi' => 'required|email|unique:instansis,EmailSekolah',
            'provinsi_code' => 'required|string|max:10',
            'kota_code' => 'required|string|max:10',
            'kecamatan_code' => 'required|string|max:10',
            'kelurahan_code' => 'required|string|max:10',
            'kode_pos' => 'required|string|max:10',
            'alamat' => 'required|string',
            'nama_kepsek' => 'required|string|max:255',
            'nip_kepsek' => 'required|string|max:50',
            'tanggal_berdiri' => 'required|date',
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',

            // DATA ADMIN
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:15',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required'
        ]);

        DB::beginTransaction();

        try {
            // Upload logo
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoFile = $request->file('logo');
                $logoName = 'logo_' . time() . '_' . Str::slug($request->nama_instansi) . '.' . $logoFile->getClientOriginalExtension();
                $logoPath = $logoFile->storeAs('logos', $logoName, 'public');
            }

            // Generate KodeInstansi
            $kodeInstansi = $this->generateKodeInstansi($request->jenjang_sekolah);

            // 1️⃣ Buat Instansi
            $instansi = Instansi::create([
                'KodeInstansi' => $kodeInstansi,
                'NPSN' => $request->npsn,
                'NamaSekolah' => $request->nama_instansi,
                'JenjangSekolah' => $request->jenjang_sekolah,
                'provinsi_code' => $request->provinsi_code,
                'kota_code' => $request->kota_code,
                'kecamatan_code' => $request->kecamatan_code,
                'kelurahan_code' => $request->kelurahan_code,
                'KodePos' => $request->kode_pos,
                'EmailSekolah' => $request->email_instansi,
                'Logo' => $logoPath,
                'NamaKepalaSekolah' => $request->nama_kepsek,
                'NIPKepalaSekolah' => $request->nip_kepsek,
                'TanggalBerdiri' => $request->tanggal_berdiri,
                'Status' => 'Aktif',
            ]);

            // 2️⃣ Buat Admin Sekolah
            $user = User::create([
                'InstansiID' => $instansi->InstansiID,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'admin_sekolah',
                'status' => 'active',
                'email_verified_at' => null,
                'kode_lisensi' => $this->generateKodeLisensi(),
            ]);

            // 3️⃣ Generate License
            License::create([   
                'user_id' => $user->id,
                'license_key' => $this->generateLicenseKey(),
                'start_date' => now(),
                'expired_date' => now()->addYear(),
                'is_active' => true
            ]);

            DB::commit();

            return redirect()->route('login')
            ->with('success', 'Registrasi berhasil!')
            ->with('kode_lisensi', $instansi->kode_lisensi);

        } catch (\Exception $e) {
            DB::rollback();
            
            // Hapus file logo jika sudah terupload tapi gagal
            if (isset($logoPath) && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }
            
            Log::error('Registration error: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat registrasi: ' . $e->getMessage());
        }
    }

    /**
     * Generate kode instansi unik
     */
    private function generateKodeInstansi($jenjang)
    {
        $prefix = match($jenjang) {
            'SD' => 'SD',
            'SMP' => 'SMP',
            'SMA' => 'SMA',
            'SMK' => 'SMK',
            default => 'SEK'
        };
        
        $year = date('Y');
        $random = strtoupper(Str::random(5));
        
        return $prefix . $year . $random;
    }

    function generateKodeLisensi()
    {
        return 'LIC-' . strtoupper(uniqid());
    }

    private function generateLicenseKey()
    {
        return strtoupper(
            Str::random(4) . '-' .
            Str::random(4) . '-' .
            Str::random(4) . '-' .
            Str::random(4)
        );
    }
}