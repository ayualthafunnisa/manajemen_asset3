@extends('layouts.app')

@section('title', 'Edit Kategori - Jobie')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-white shadow-sm border border-gray-200 px-4 py-3 rounded-lg">
        <li class="breadcrumb-item">
            <a href="" class="text-gray-600 hover:text-purple-600 transition duration-150">
                Dashboard
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('kategori.index') }}" class="text-gray-600 hover:text-purple-600 transition duration-150">
                Kategori
            </a>
        </li>
        <li class="breadcrumb-item active text-gray-800" aria-current="page">
            Edit Kategori #{{ $kategori->KategoriID }}
        </li>
    </ol>
</nav>
@endsection

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Kategori</h1>
        <p class="mt-1 text-gray-600">Perbarui informasi kategori #{{ $kategori->KategoriID }}</p>
    </div>
    <div class="mt-4 sm:mt-0 flex space-x-2">
        <a href="{{ route('kategori.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 active:bg-gray-100 focus:outline-none focus:border-purple-500 focus:ring focus:ring-purple-300 transition duration-150 ease-in-out">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
        <form action="{{ route('kategori.destroy', $kategori->KategoriID) }}" method="POST" id="deleteForm">
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
                    <form action="{{ route('kategori.update', $kategori->KategoriID) }}" method="POST" id="editForm" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Kode Kategori Field -->
                        <div class="space-y-2">
                            <label for="KodeKategori" class="block text-sm font-medium text-gray-700">
                                Kode Kategori <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                    name="KodeKategori" 
                                    id="KodeKategori" 
                                    value="{{ old('KodeKategori', $kategori->KodeKategori) }}"
                                    class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 ease-in-out @error('KodeKategori') border-red-500 @enderror"
                                    placeholder="Contoh: KT001, ASSET-001"
                                    required
                                    autofocus>
                            </div>
                            @error('KodeKategori')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Nama Kategori Field -->
                        <div class="space-y-2">
                            <label for="NamaKategori" class="block text-sm font-medium text-gray-700">
                                Nama Kategori <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                    name="NamaKategori" 
                                    id="NamaKategori" 
                                    value="{{ old('NamaKategori', $kategori->NamaKategori) }}"
                                    class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 ease-in-out @error('NamaKategori') border-red-500 @enderror"
                                    placeholder="Contoh: Elektronik, Furniture, Kendaraan"
                                    required>
                            </div>
                            @error('NamaKategori')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Instansi Field -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Instansi
                            </label>
                            <input type="text" 
                                value="{{ auth()->user()->instansi->NamaSekolah ?? 'Tidak ada' }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100"
                                readonly>
                        </div>
                        
                        <!-- Description Field -->
                        <div class="space-y-2">
                            <label for="Description" class="block text-sm font-medium text-gray-700">
                                Deskripsi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute top-3 left-3 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <textarea name="Deskripsi" 
                                          id="Description" 
                                          rows="6"
                                          class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 ease-in-out @error('Description') border-red-500 @enderror"
                                          placeholder="Jelaskan detail tentang kategori ini..."
                                          required>{{ old('Deskripsi', $kategori->Deskripsi) }}</textarea>
                            </div>
                            @error('Description')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-sm text-gray-500">Deskripsi maksimal 500 karakter</p>
                                <span id="charCount" class="text-sm text-gray-500">0/500</span>
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
                                <a href="{{ route('kategori.index') }}" 
                                   class="px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    Batal
                                </a>
                                <button type="submit" 
                                        class="px-6 py-3 bg-purple-600 border border-transparent rounded-lg font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150 ease-in-out">
                                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Perbarui Kategori
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Info Sidebar -->
        <div class="space-y-6">
            <!-- Category Info Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-purple-50 px-6 py-4 border-b border-purple-100">
                    <h3 class="text-lg font-semibold text-purple-900">Informasi Kategori</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">ID Kategori</p>
                        <p class="text-lg font-bold text-gray-900 mt-1">#{{ $kategori->KategoriID }}</p>
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
                        <p class="text-sm text-gray-900 mt-1">{{ \Carbon\Carbon::parse($kategori->created_at)->format('d F Y, H:i') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Terakhir Diupdate</p>
                        <p class="text-sm text-gray-900 mt-1">{{ \Carbon\Carbon::parse($kategori->updated_at)->format('d F Y, H:i') }}</p>
                    </div>
            </div>
            
            <!-- Recent Changes Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Kategori dibuat</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($kategori->created_at)->diffForHumans() }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Terakhir diupdate</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($kategori->created_at)->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Catatan Perubahan</h4>
                        <textarea id="changeNotes" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                  rows="3"
                                  placeholder="Tambahkan catatan untuk perubahan ini..."></textarea>
                        <p class="text-xs text-gray-500 mt-1">Catatan ini akan disimpan dalam riwayat perubahan</p>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('kategori.create') }}" 
                       class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Tambah Kategori Baru</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    
                    <a href="{{ route('kategori.index') }}" 
                       class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Lihat Semua Kategori</span>
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
        const description = document.getElementById('Description');
        const charCount = document.getElementById('charCount');
        const categoryName = document.getElementById('CategoryName');
        const deleteForm = document.getElementById('deleteForm');
        
        // Initialize character counter
        function updateCharCounter() {
            const count = description.value.length;
            charCount.textContent = `${count}/500`;
            
            if (count > 500) {
                charCount.classList.remove('text-gray-500');
                charCount.classList.add('text-red-500');
                description.classList.add('border-red-500');
            } else {
                charCount.classList.remove('text-red-500');
                charCount.classList.add('text-gray-500');
                description.classList.remove('border-red-500');
            }
        }
        
        // Initial update
        updateCharCounter();
        
        // Update character counter on input
        description.addEventListener('input', updateCharCounter);
        
        // Form validation
        form.addEventListener('submit', function(e) {
            const nameValue = categoryName.value.trim();
            const descValue = description.value.trim();
            let isValid = true;
            
            // Reset previous error states
            document.querySelectorAll('.border-red-500').forEach(el => {
                el.classList.remove('border-red-500');
            });
            document.querySelectorAll('.error-message').forEach(el => {
                el.remove();
            });
            
            // Validate category name
            if (!nameValue) {
                isValid = false;
                categoryName.classList.add('border-red-500');
                showError(categoryName, 'Nama kategori wajib diisi');
            } else if (nameValue.length > 100) {
                isValid = false;
                categoryName.classList.add('border-red-500');
                showError(categoryName, 'Nama kategori maksimal 100 karakter');
            }
            
            // Validate description
            if (!descValue) {
                isValid = false;
                description.classList.add('border-red-500');
                showError(description, 'Deskripsi wajib diisi');
            } else if (descValue.length > 500) {
                isValid = false;
                description.classList.add('border-red-500');
                showError(description, 'Deskripsi maksimal 500 karakter');
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
            } else {
                // Show loading
                const loading = document.getElementById('loading');
                if (loading) loading.classList.remove('hidden');
            }
        });
        
        // Delete confirmation
        window.confirmDelete = function() {
            if (confirm('Apakah Anda yakin ingin menghapus kategori ini?\n\nTindakan ini tidak dapat dibatalkan.')) {
                // Show loading
                const loading = document.getElementById('loading');
                if (loading) loading.classList.remove('hidden');
                
                // Submit delete form
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
        
        // Auto-focus on category name field
        if (categoryName) {
            categoryName.focus();
            categoryName.select();
        }
        
        // Real-time validation for category name
        categoryName.addEventListener('input', function() {
            const value = this.value.trim();
            if (value.length > 100) {
                this.classList.add('border-red-500');
                if (!document.querySelector('#nameError')) {
                    const errorElement = document.createElement('p');
                    errorElement.id = 'nameError';
                    errorElement.className = 'error-message text-sm text-red-600 mt-1';
                    errorElement.textContent = 'Nama kategori maksimal 100 karakter';
                    this.parentElement.appendChild(errorElement);
                }
            } else {
                this.classList.remove('border-red-500');
                const errorElement = document.getElementById('nameError');
                if (errorElement) errorElement.remove();
            }
        });
        
        // Track changes for confirmation
        let originalName = categoryName.value;
        let originalDesc = description.value;
        
        form.addEventListener('submit', function(e) {
            if (categoryName.value === originalName && description.value === originalDesc) {
                if (!confirm('Anda belum melakukan perubahan apapun. Lanjutkan?')) {
                    e.preventDefault();
                    return;
                }
            }
        });
        
        // Auto-save draft (local storage)
        const saveDraft = () => {
            const draft = {
                name: categoryName.value,
                description: description.value,
                timestamp: new Date().toISOString()
            };
            localStorage.setItem(`category_draft_${getCategoryId()}`, JSON.stringify(draft));
        };
        
        // Load draft from local storage
        const loadDraft = () => {
            const draft = localStorage.getItem(`category_draft_${getCategoryId()}`);
            if (draft) {
                const data = JSON.parse(draft);
                const shouldLoad = confirm('Ada draft yang belum tersimpan. Muat draft tersebut?');
                if (shouldLoad) {
                    categoryName.value = data.name;
                    description.value = data.description;
                    updateCharCounter();
                }
            }
        };
        
        // Get category ID from URL
        function getCategoryId() {
            const path = window.location.pathname;
            const matches = path.match(/kategori\/(\d+)/);
            return matches ? matches[1] : 'unknown';
        }
        
        // Save draft periodically
        categoryName.addEventListener('input', saveDraft);
        description.addEventListener('input', saveDraft);
        
        // Clear draft on successful submit
        form.addEventListener('submit', function() {
            localStorage.removeItem(`category_draft_${getCategoryId()}`);
        });
        
        // Load draft on page load (optional)
        // loadDraft();
        
        // Add change notes to form data
        form.addEventListener('submit', function(e) {
            const changeNotes = document.getElementById('changeNotes').value;
            if (changeNotes.trim()) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'change_notes';
                input.value = changeNotes;
                form.appendChild(input);
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
                    window.location.href = '{{ route("kategori.index") }}';
                }
            }
        });
        
        // Show unsaved changes warning
        window.addEventListener('beforeunload', function(e) {
            if (categoryName.value !== originalName || description.value !== originalDesc) {
                e.preventDefault();
                e.returnValue = 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?';
                return e.returnValue;
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    /* Custom styles for edit form */
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
    input:focus, textarea:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
    }
    
    /* Transition animations */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }
    
    /* Info card styling */
    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    /* Status badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 9999px;
    }
    
    /* Quick action hover effect */
    .quick-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    /* Custom scrollbar */
    textarea {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f1f5f9;
    }
    
    textarea::-webkit-scrollbar {
        width: 8px;
    }
    
    textarea::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    
    textarea::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 4px;
    }
    
    textarea::-webkit-scrollbar-thumb:hover {
        background-color: #94a3b8;
    }
    
    /* Grid gap animation */
    .grid > * {
        transition: transform 0.2s ease;
    }
    
    /* Card hover effects */
    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Loading skeleton */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
</style>
@endpush