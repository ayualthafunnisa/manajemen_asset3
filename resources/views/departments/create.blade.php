@extends('layouts.app')

@section('title', 'Tambah Departemen - Jobie')

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
            Tambah Baru
        </li>
    </ol>
</nav>
@endsection

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Departemen Baru</h1>
        <p class="mt-1 text-gray-600">Isi form berikut untuk menambahkan departemen baru</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('departments.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 active:bg-gray-100 focus:outline-none focus:border-purple-500 focus:ring focus:ring-purple-300 transition duration-150 ease-in-out">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
        <div class="p-6 md:p-8">
            <form action="{{ route('departments.store') }}" method="POST" id="departmentForm" class="space-y-6">
                @csrf
                
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
                               value="{{ old('DepartName') }}"
                               class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 ease-in-out @error('DepartName') border-red-500 @enderror"
                               placeholder="Contoh: Teknologi Informasi, Sumber Daya Manusia"
                               required
                               autofocus>
                    </div>
                    @error('DepartName')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">Masukkan nama departemen yang jelas dan lengkap</p>
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
                               value="{{ old('DepartCode') }}"
                               class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 ease-in-out @error('DepartCode') border-red-500 @enderror"
                               placeholder="Contoh: IT, HRD, FIN"
                               required
                               maxlength="20">
                    </div>
                    @error('DepartCode')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-sm text-gray-500">Kode singkatan maksimal 20 karakter</p>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Simpan Departemen
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Help Section -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Tips Membuat Departemen</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Gunakan nama departemen yang jelas dan sesuai dengan struktur organisasi</li>
                        <li>Kode departemen sebaiknya singkat dan mudah diingat</li>
                        <li>Pastikan kode departemen unik dan tidak duplikat</li>
                        <li>Gunakan format standar kode untuk konsistensi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('departmentForm');
        const departCode = document.getElementById('DepartCode');
        const codeCount = document.getElementById('codeCount');
        const departName = document.getElementById('DepartName');
        
        // Character counter for code
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
        
        // Initialize character counter
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
        }
        
        // Real-time validation for department name
        departName.addEventListener('input', function() {
            const value = this.value.trim();
            if (value.length > 255) {
                this.classList.add('border-red-500');
                if (!document.querySelector('#nameError')) {
                    const errorElement = document.createElement('p');
                    errorElement.id = 'nameError';
                    errorElement.className = 'error-message text-sm text-red-600 mt-1';
                    errorElement.textContent = 'Nama departemen maksimal 255 karakter';
                    this.parentElement.appendChild(errorElement);
                }
            } else {
                this.classList.remove('border-red-500');
                const errorElement = document.getElementById('nameError');
                if (errorElement) errorElement.remove();
            }
        });
        
        // Real-time validation for department code
        departCode.addEventListener('input', function() {
            updateCodeCounter();
            const value = this.value.trim();
            if (value.length > 20) {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
            }
        });
        
        // Show form tips
        const tips = [
            'Departemen membantu mengorganisir struktur perusahaan',
            'Gunakan kode yang konsisten untuk semua departemen',
            'Nama departemen harus mencerminkan fungsi dan tanggung jawabnya'
        ];
        
        let tipIndex = 0;
        const tipElement = document.createElement('div');
        tipElement.className = 'mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-700';
        tipElement.innerHTML = `<strong>Tip:</strong> ${tips[tipIndex]}`;
        form.parentElement.insertBefore(tipElement, form);
        
        // Rotate tips every 10 seconds
        setInterval(() => {
            tipIndex = (tipIndex + 1) % tips.length;
            tipElement.innerHTML = `<strong>Tip:</strong> ${tips[tipIndex]}`;
        }, 10000);
    });
</script>
@endpush

@push('styles')
<style>
    /* Custom styles for create form */
    .breadcrumb {
        display: flex;
        flex-wrap: wrap;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        list-style: none;
        background-color: #ffffff;
        border-radius: 0.375rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    
    .breadcrumb-item {
        display: flex;
        align-items: center;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: "/";
        display: inline-block;
        padding: 0 0.5rem;
        color: #9ca3af;
    }
    
    .breadcrumb-item a {
        color: #6b7280;
        text-decoration: none;
        transition: color 0.15s ease-in-out;
    }
    
    .breadcrumb-item a:hover {
        color: #7c3aed;
    }
    
    .breadcrumb-item.active {
        color: #1f2937;
        font-weight: 500;
    }
    
    /* Form focus styles */
    input:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
    }
    
    /* Code badge styling */
    #DepartCode {
        font-family: 'Consolas', 'Monaco', monospace;
        font-weight: bold;
        letter-spacing: 1px;
    }
</style>
@endpush