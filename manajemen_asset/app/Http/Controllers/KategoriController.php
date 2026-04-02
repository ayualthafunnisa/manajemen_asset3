<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = Kategori::latest()->paginate(10);
        
        return view('kategoris.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $instansis = Instansi::all();
        return view('kategoris.create', compact('instansis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'KodeKategori' => 'required|string|max:50|unique:kategori_assets,KodeKategori',
            'NamaKategori' => 'required|string|max:50',
            'Deskripsi' => 'required|string|max:500',
            'InstansiID' => 'required|exists:instansis,InstansiID'
        ], [
            'KodeKategori.required' => 'Kode kategori wajib diisi',
            'KodeKategori.unique' => 'Kode kategori sudah digunakan',
            'NamaKategori.required' => 'Nama kategori wajib diisi',
            'Deskripsi.required' => 'Deskripsi wajib diisi',
            'InstansiID.required' => 'Instansi wajib dipilih',
            'InstansiID.exists' => 'Instansi tidak valid'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        try {
            DB::beginTransaction();
            
            Kategori::create([
                'KodeKategori' => $request->KodeKategori,
                'NamaKategori' => $request->NamaKategori,
                'Deskripsi' => $request->Deskripsi,
                'InstansiID' => $request->InstansiID
            ]);
            
            DB::commit();
            
            return redirect()->route('kategori.index')
                ->with('success', 'Kategori berhasil ditambahkan');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan kategori: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        $instansis = Instansi::all();
        return view('kategoris.edit', compact('kategori', 'instansis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'KodeKategori' => 'required|string|max:50|unique:kategori_assets,KodeKategori,' . $id . ',KategoriID',
            'NamaKategori' => 'required|string|max:50',
            'Deskripsi' => 'required|string|max:500',
            'InstansiID' => 'required|exists:instansis,InstansiID'
        ], [
            'KodeKategori.required' => 'Kode kategori wajib diisi',
            'KodeKategori.unique' => 'Kode kategori sudah digunakan',
            'NamaKategori.required' => 'Nama kategori wajib diisi',
            'Deskripsi.required' => 'Deskripsi wajib diisi',
            'InstansiID.required' => 'Instansi wajib dipilih',
            'InstansiID.exists' => 'Instansi tidak valid'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        try {
            DB::beginTransaction();
            
            $kategori->update([
                'KodeKategori' => $request->KodeKategori,
                'NamaKategori' => $request->NamaKategori,
                'Deskripsi' => $request->Deskripsi,
                'InstansiID' => $request->InstansiID
            ]);
            
            DB::commit();
            
            return redirect()->route('kategori.index')
                ->with('success', 'Kategori berhasil diperbarui');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui kategori: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            $kategori->delete();
            
            DB::commit();
            
            return redirect()->route('kategori.index')
                ->with('success', 'Kategori berhasil dihapus');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('kategori.index')
                ->with('error', 'Gagal menghapus kategori: ' . $e->getMessage());
        }
    }
}