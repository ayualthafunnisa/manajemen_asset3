@extends('layouts.app')

@section('title', 'Edit Departemen - Jobie')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-white shadow-sm border border-gray-200 px-4 py-3 rounded-lg">
        <li class="breadcrumb-item">
            <a href="" class="text-gray-600 hover:text-purple-600 transition duration-150">
                Dashboard
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('departments.index') }}" class="text-gray-600 hover:text-purple-600 transition duration-150">
                Departemen
            </a>
        </li>
        <li class="breadcrumb-item active text-gray-800" aria-current="page">
            Edit Departemen #{{ $department->DepartmentID }}
        </li>
    </ol>
</nav>
@endsection

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Departemen</h1>
        <p class="mt-1 text-gray-600">Perbarui informasi departemen #{{ $department->DepartmentID }}</p>
    </div>
    <div class="mt-4 sm:mt-0 flex space-x-2">
        <a href="{{ route('departments.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 active:bg-gray-100 focus:outline-none focus:border-purple-500 focus:ring focus:ring-purple-300 transition duration-150 ease-in-out">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
        <form action="{{ route('departments.destroy', $department->DepartmentID) }}" method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            <button type="button" 
                    onclick="confirmDelete()"
                    class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest hover:bg-red-50 active:bg-red-100 focus:outline-none focus:border-red-500 focus:ring focus:ring-red-300 transition duration-150 ease-in-out">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Hapus
            </button>
        </form>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Section -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="p-6 md:p-8">
                    <form action="{{ route('departments.update', $department->DepartmentID) }}" method="POST" id="editForm" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Department Name Field -->
                        <div class="space-y-2">
                            <label for="DepartName" class="block text-sm font-medium text-gray-700">
                                Nama Departemen <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="DepartName" 
                                       id="DepartName" 
                                       value="{{ old('DepartName', $department->DepartName) }}"
                                       class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 ease-in-out @error('DepartName') border-red-500 @enderror"
                                       placeholder="Contoh: Teknologi Informasi, Sumber Daya Manusia"
                                       required
                                       autofocus>
                            </div>
                            @error('DepartName')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 mt-1">Nama departemen saat ini: {{ $department->DepartName }}</p>
                        </div>
                        
                        <!-- Department Code Field -->
                        <div class="space-y-2">
                            <label for="DepartCode" class="block text-sm font-medium text-gray-700">
                                Kode Departemen <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="DepartCode" 
                                       id="DepartCode" 
                                       value="{{ old('DepartCode', $department->DepartCode) }}"
                                       class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 ease-in-out @error('DepartCode') border-red-500 @enderror"
                                       placeholder="Contoh: IT, HRD, FIN"
                                       required
                                       maxlength="20">
                            </div>
                            @error('DepartCode')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-sm text-gray-500">Kode departemen saat ini: <span class="font-bold">{{ $department->DepartCode }}</span></p>
                                <span id="codeCount" class="text-sm text-gray-500">0/20</span>
                            </div>
                        </div>
                        
                        <!-- Form Status Messages -->
                        @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if(session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Form Actions -->
                        <div class="pt-6 border-t border-gray-200">
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('departments.index') }}" 
                                   class="px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    Batal
                                </a>
                                <button type="submit" 
                                        class="px-6 py-3 bg-purple-600 border border-transparent rounded-lg font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150 ease-in-out">
                                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Perbarui Departemen
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Info Sidebar -->
        <div class="space-y-6">
            <!-- Department Info Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-purple-50 px-6 py-4 border-b border-purple-100">
                    <h3 class="text-lg font-semibold text-purple-900">Informasi Departemen</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">ID Departemen</p>
                        <p class="text-lg font-bold text-gray-900 mt-1">#{{ $department->DepartmentID }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Status</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                            <svg class="mr-1.5 h-2 w-2 text-green-800" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Aktif
                        </span>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Tanggal Dibuat</p>
                        <p class="text-sm text-gray-900 mt-1">{{ \Carbon\Carbon::parse($department->created_at)->format('d F Y, H:i') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Terakhir Diupdate</p>
                        <p class="text-sm text-gray-900 mt-1">{{ \Carbon\Carbon::parse($department->updated_at)->format('d F Y, H:i') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('departments.create') }}" 
                       class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Tambah Departemen Baru</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    
                    <a href="{{ route('departments.index') }}" 
                       class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Lihat Semua Departemen</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    
                    <button type="button" 
                            onclick="window.location.reload()"
                            class="w-full flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Refresh Data</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('editForm');
        const departCode = document.getElementById('DepartCode');
        const codeCount = document.getElementById('codeCount');
        const departName = document.getElementById('DepartName');
        const deleteForm = document.getElementById('deleteForm');
        
        // Initialize character counter
        function updateCodeCounter() {
            const count = departCode.value.length;
            codeCount.textContent = `${count}/20`;
            
            if (count > 20) {
                codeCount.classList.remove('text-gray-500');
                codeCount.classList.add('text-red-500');
                departCode.classList.add('border-red-500');
            } else {
                codeCount.classList.remove('text-red-500');
                codeCount.classList.add('text-gray-500');
                departCode.classList.remove('border-red-500');
            }
        }
        
        // Initial update
        updateCodeCounter();
        
        // Update character counter on input
        departCode.addEventListener('input', function() {
            // Convert to uppercase
            this.value = this.value.toUpperCase();
            updateCodeCounter();
        });
        
        // Form validation
        form.addEventListener('submit', function(e) {
            const nameValue = departName.value.trim();
            const codeValue = departCode.value.trim();
            let isValid = true;
            
            // Reset previous error states
            document.querySelectorAll('.border-red-500').forEach(el => {
                el.classList.remove('border-red-500');
            });
            document.querySelectorAll('.error-message').forEach(el => {
                el.remove();
            });
            
            // Validate department name
            if (!nameValue) {
                isValid = false;
                departName.classList.add('border-red-500');
                showError(departName, 'Nama departemen wajib diisi');
            } else if (nameValue.length > 255) {
                isValid = false;
                departName.classList.add('border-red-500');
                showError(departName, 'Nama departemen maksimal 255 karakter');
            }
            
            // Validate department code
            if (!codeValue) {
                isValid = false;
                departCode.classList.add('border-red-500');
                showError(departCode, 'Kode departemen wajib diisi');
            } else if (codeValue.length > 20) {
                isValid = false;
                departCode.classList.add('border-red-500');
                showError(departCode, 'Kode departemen maksimal 20 karakter');
            }
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = document.querySelector('.border-red-500');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
                
                // Show notification
                if (typeof showNotification === 'function') {
                    showNotification('Harap perbaiki kesalahan pada form', 'error');
                }
            }
        });
        
        // Delete confirmation
        window.confirmDelete = function() {
            if (confirm('Apakah Anda yakin ingin menghapus departemen ini?\n\nTindakan ini tidak dapat dibatalkan.')) {
                deleteForm.submit();
            }
        };
        
        // Helper function to show error messages
        function showError(inputElement, message) {
            const errorElement = document.createElement('p');
            errorElement.className = 'error-message text-sm text-red-600 mt-1';
            errorElement.textContent = message;
            
            const parent = inputElement.parentElement;
            if (parent) {
                parent.appendChild(errorElement);
            }
        }
        
        // Auto-focus on department name field
        if (departName) {
            departName.focus();
            departName.select();
        }
        
        // Track changes for confirmation
        let originalName = departName.value;
        let originalCode = departCode.value;
        
        form.addEventListener('submit', function(e) {
            if (departName.value === originalName && departCode.value === originalCode) {
                if (!confirm('Anda belum melakukan perubahan apapun. Lanjutkan?')) {
                    e.preventDefault();
                    return;
                }
            }
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + S to save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                form.dispatchEvent(new Event('submit'));
            }
            
            // Escape to cancel
            if (e.key === 'Escape') {
                if (confirm('Batalkan perubahan?')) {
                    window.location.href = '{{ route("departments.index") }}';
                }
            }
        });
        
        // Show unsaved changes warning
        window.addEventListener('beforeunload', function(e) {
            if (departName.value !== originalName || departCode.value !== originalCode) {
                e.preventDefault();
                e.returnValue = 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?';
                return e.returnValue;
            }
        });
    });
</script>
@endpush