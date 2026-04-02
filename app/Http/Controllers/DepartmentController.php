<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::latest()->paginate(10);
        
        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'DepartName' => 'required|string|max:255',
            'DepartCode' => 'required|string|max:20|unique:departments,DepartCode',
        ], [
            'DepartName.required' => 'Nama departemen wajib diisi',
            'DepartCode.required' => 'Kode departemen wajib diisi',
            'DepartCode.unique' => 'Kode departemen sudah digunakan',
            'DepartCode.max' => 'Kode departemen maksimal 20 karakter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        try {
            DB::beginTransaction();
            
            Department::create($request->all());
            
            DB::commit();
            
            return redirect()->route('departments.index')
                ->with('success', 'Departemen berhasil ditambahkan');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan departemen: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $department = Department::findOrFail($id);
        
        return view('departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $department = Department::findOrFail($id);
        
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'DepartName' => 'required|string|max:255',
            'DepartCode' => 'required|string|max:20|unique:departments,DepartCode,' . $id . ',DepartmentID',
        ], [
            'DepartName.required' => 'Nama departemen wajib diisi',
            'DepartCode.required' => 'Kode departemen wajib diisi',
            'DepartCode.unique' => 'Kode departemen sudah digunakan',
            'DepartCode.max' => 'Kode departemen maksimal 20 karakter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        try {
            DB::beginTransaction();
            
            $department->update($request->all());
            
            DB::commit();
            
            return redirect()->route('departments.index')
                ->with('success', 'Departemen berhasil diperbarui');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui departemen: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            $department->delete();
            
            DB::commit();
            
            return redirect()->route('departments.index')
                ->with('success', 'Departemen berhasil dihapus');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('departments.index')
                ->with('error', 'Gagal menghapus departemen: ' . $e->getMessage());
        }
    }
}