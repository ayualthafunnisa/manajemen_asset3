<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use Laravolt\Indonesia\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class InstansiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instansis = Instansi::latest()->paginate(10);
        
        return view('instansis.index', compact('instansis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenjangOptions = ['SD', 'SMP', 'SMA', 'SMK'];
        $statusOptions = ['Aktif', 'Inactive'];
        $provinsis = Province::all();
        
        return view('instansis.create', compact('jenjangOptions', 'statusOptions', 'provinsis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'KodeInstansi' => 'required|string|max:20|unique:instansis,KodeInstansi',
            'NPSN' => 'required|string|unique:instansis,NPSN',
            'NamaSekolah' => 'required|string|max:255',
            'JenjangSekolah' => 'required|in:SD,SMP,SMA,SMK',
            'provinsi_code' => 'nullable',     
            'kota_code' => 'nullable',         
            'kecamatan_code' => 'nullable',     
            'kelurahan_code' => 'nullable',
            'KodePos' => 'required|string|max:10',
            'Email' => 'required|email|unique:instansis,Email',
            'Logo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'NamaKepalaSekolah' => 'required|string|max:255',
            'NIPKepalaSekolah' => 'required|string|max:50',
            'TanggalBerdiri' => 'required|date',
            'Status' => 'required|in:Aktif,Inactive',
        ], [
            'KodeInstansi.required' => 'Kode instansi wajib diisi',
            'KodeInstansi.unique' => 'Kode instansi sudah digunakan',
            'NPSN.required' => 'NPSN wajib diisi',
            'NPSN.unique' => 'NPSN sudah terdaftar',
            'Email.unique' => 'Email sudah terdaftar',
            'Logo.image' => 'File harus berupa gambar',
            'Logo.max' => 'Ukuran logo maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        try {
            DB::beginTransaction();
            
            $data = $request->all();
            
            // Handle logo upload
            if ($request->hasFile('Logo')) {
                $logoPath = $request->file('Logo')->store('logos', 'public');
                $data['Logo'] = $logoPath;
            } else {
                $data['Logo'] = 'default-logo.png';
            }
            
            Instansi::create($data);
            
            DB::commit();
            
            return redirect()->route('instansi.index')
                ->with('success', 'Instansi berhasil ditambahkan');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan instansi: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $instansi = Instansi::findOrFail($id);
        $jenjangOptions = ['SD', 'SMP', 'SMA', 'SMK'];
        $statusOptions = ['Aktif', 'Inactive'];
        
        return view('instansis.edit', compact('instansi', 'jenjangOptions', 'statusOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $instansi = Instansi::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'KodeInstansi' => 'required|string|max:20|unique:instansis,KodeInstansi,' . $id . ',InstansiID',
            'NPSN' => 'required|string|unique:instansis,NPSN,' . $id . ',InstansiID',
            'NamaSekolah' => 'required|string|max:255',
            'JenjangSekolah' => 'required|in:SD,SMP,SMA,SMK',
            'provinsi_code' => 'nullable',    
            'kota_code' => 'nullable',        
            'kecamatan_code' => 'nullable',    
            'kelurahan_code' => 'nullable', 
            'KodePos' => 'required|string|max:10',
            'Email' => 'required|email|unique:instansis,Email,' . $id . ',InstansiID',
            'Logo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'NamaKepalaSekolah' => 'required|string|max:255',
            'NIPKepalaSekolah' => 'required|string|max:50',
            'TanggalBerdiri' => 'required|date',
            'Status' => 'required|in:Aktif,Inactive',
        ], [
            'KodeInstansi.required' => 'Kode instansi wajib diisi',
            'KodeInstansi.unique' => 'Kode instansi sudah digunakan',
            'NPSN.required' => 'NPSN wajib diisi',
            'NPSN.unique' => 'NPSN sudah terdaftar',
            'Email.unique' => 'Email sudah terdaftar',
            'Logo.image' => 'File harus berupa gambar',
            'Logo.max' => 'Ukuran logo maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        try {
            DB::beginTransaction();
            
            $data = $request->all();
            
            // Handle logo upload
            if ($request->hasFile('Logo')) {
                // Delete old logo if exists
                if ($instansi->Logo && $instansi->Logo !== 'default-logo.png' && Storage::disk('public')->exists($instansi->Logo)) {
                    Storage::disk('public')->delete($instansi->Logo);
                }
                
                $logoPath = $request->file('Logo')->store('logos', 'public');
                $data['Logo'] = $logoPath;
            } else {
                // Keep existing logo if no new logo uploaded
                unset($data['Logo']);
            }
            
            $instansi->update($data);
            
            DB::commit();
            
            return redirect()->route('instansi.index')
                ->with('success', 'Instansi berhasil diperbarui');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui instansi: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $instansi = Instansi::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Delete logo if exists
            if ($instansi->Logo && $instansi->Logo !== 'default-logo.png' && Storage::disk('public')->exists($instansi->Logo)) {
                Storage::disk('public')->delete($instansi->Logo);
            }
            
            $instansi->delete();
            
            DB::commit();
            
            return redirect()->route('instansi.index')
                ->with('success', 'Instansi berhasil dihapus');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('instansi.index')
                ->with('error', 'Gagal menghapus instansi: ' . $e->getMessage());
        }
    }
}