<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class ProfileController extends Controller
{
    /**
     * Show profile page
     */
    public function index()
    {
        $user = Auth::user();
        $instansi = $user->instansi;
        
        // Load provinces dari database
        $provinces = Province::orderBy('name')->get();
        
        return view('profile.index', compact('user', 'instansi', 'provinces'));
    }
    
    /**
     * Update user profile (admin)
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:20',
        ]);
        
        $user->update($validated);
        
        return back()->with('success', 'Data admin berhasil diperbarui!');
    }
    
    /**
     * Update or create school data with logo upload
     */
    public function updateSchool(Request $request)
    {
        try {
            $user = Auth::user();
            $instansi = $user->instansi;
            
            // Log untuk debugging
            \Log::info('Update School - User ID: ' . $user->id);
            \Log::info('Update School - Current InstansiID: ' . ($user->InstansiID ?? 'NULL'));
            
            $validated = $request->validate([
                'NamaSekolah' => 'required|string|max:255',
                'NPSN' => 'required|string|max:20',
                'JenjangSekolah' => 'required|in:SD,SMP,SMA,SMK',
                'EmailSekolah' => 'required|email',
                'TeleponSekolah' => 'nullable|string|max:20',
                'NamaKepalaSekolah' => 'nullable|string|max:255',
                'NIPKepalaSekolah' => 'nullable|string|max:50',
                'TanggalBerdiri' => 'nullable|date',
                'Provinsi' => 'nullable|string|max:100',
                'KotaKabupaten' => 'nullable|string|max:100',
                'Kecamatan' => 'nullable|string|max:100',
                'Kelurahan' => 'nullable|string|max:100',
                'KodePos' => 'nullable|string|max:10',
                'AlamatLengkap' => 'nullable|string',
                'provinsi_code' => 'nullable|string|max:10',
                'kota_code' => 'nullable|string|max:10',
                'kecamatan_code' => 'nullable|string|max:10',
                'kelurahan_code' => 'nullable|string|max:10',
                'Logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Hidden input Provinsi, KotaKabupaten dll sudah berisi nama teks dari JS
            $data = [
                'NamaSekolah'       => $request->NamaSekolah,
                'NPSN'              => $request->NPSN,
                'JenjangSekolah'    => $request->JenjangSekolah,
                'EmailSekolah'      => $request->EmailSekolah,
                'TeleponSekolah'    => $request->TeleponSekolah,
                'NamaKepalaSekolah' => $request->NamaKepalaSekolah,
                'NIPKepalaSekolah'  => $request->NIPKepalaSekolah,
                'TanggalBerdiri'    => $request->TanggalBerdiri,
                'KodePos'           => $request->KodePos,
                'AlamatLengkap'     => $request->AlamatLengkap,
                
                // Simpan nama wilayah langsung (dari hidden input yang diisi JS)
                'Provinsi'          => $request->Provinsi ?: null,
                'KotaKabupaten'     => $request->KotaKabupaten ?: null,
                'Kecamatan'         => $request->Kecamatan ?: null,
                'Kelurahan'         => $request->Kelurahan ?: null,
                
                // Simpan kode untuk keperluan re-load dropdown saat edit
                'provinsi_code'     => $request->provinsi_code,
                'kota_code'         => $request->kota_code,
                'kecamatan_code'    => $request->kecamatan_code,
                'kelurahan_code'    => $request->kelurahan_code,
            ];
            
            // Handle logo upload
            if ($request->hasFile('Logo')) {
                if ($instansi && $instansi->Logo) {
                    Storage::disk('public')->delete($instansi->Logo);
                }
                
                $logo = $request->file('Logo');
                $logoName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $logo->getClientOriginalName());
                $logoPath = $logo->storeAs('logos', $logoName, 'public');
                $validated['Logo'] = $logoPath;
            }
            
            if ($instansi) {
                // Update existing instansi
                $instansi->update($validated);
                \Log::info('Update School - Updated existing instansi ID: ' . $instansi->InstansiID);
            } else {
                // Create new instansi
                $validated['KodeInstansi'] = 'INS-' . strtoupper(uniqid());
                $validated['Status'] = 'Aktif';
                $instansi = Instansi::create($validated);
                
                \Log::info('Update School - Created new instansi ID: ' . $instansi->InstansiID);
                
                // Update user dengan InstansiID
                // Perhatikan: field di tabel users adalah 'InstansiID' (dengan huruf I besar)
                $user->InstansiID = $instansi->InstansiID;
                $user->save();
                
                \Log::info('Update School - Updated user InstansiID to: ' . $user->InstansiID);
                
                // Refresh user data
                $user->refresh();
            }
            
            return back()->with('success', 'Data sekolah berhasil disimpan!');
            
        } catch (\Exception $e) {
            \Log::error('Update School Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Get all provinces (AJAX)
     */
    public function getProvinces()
    {
        try {
            $provinces = Province::orderBy('name')->get();
            return response()->json($provinces);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load provinces'], 500);
        }
    }
    
    /**
     * Get cities by province code (AJAX)
     */
    public function getCities($provinceCode)
    {
        try {
            $cities = City::where('province_code', $provinceCode)
                ->orderBy('name')
                ->get(['code', 'name']);
            return response()->json($cities);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load cities'], 500);
        }
    }
    
    /**
     * Get districts by city code (AJAX)
     */
    public function getDistricts($cityCode)
    {
        try {
            $districts = District::where('city_code', $cityCode)
                ->orderBy('name')
                ->get(['code', 'name']);
            return response()->json($districts);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load districts'], 500);
        }
    }
    
    /**
     * Get villages by district code (AJAX)
     */
    public function getVillages($districtCode)
    {
        try {
            $villages = Village::where('district_code', $districtCode)
                ->orderBy('name')
                ->get(['code', 'name']);
            return response()->json($villages);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load villages'], 500);
        }
    }
}