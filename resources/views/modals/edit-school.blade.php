<!-- resources/views/modals/edit-school.blade.php -->
<div x-show="showSchoolModal" 
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showSchoolModal = false"></div>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            @php
                $instansiId = isset($instansi) && $instansi ? $instansi->InstansiID : null;
            @endphp
            
            @if($instansiId)
                <form action="{{ route('instansi.update', $instansiId) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Edit Data Sekolah</h3>
                        <p class="text-blue-100 text-sm mt-1">Perbarui informasi sekolah Anda</p>
                    </div>
                    
                    <div class="bg-white px-6 pt-5 pb-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Sekolah *</label>
                                <input type="text" name="NamaSekolah" value="{{ $instansi->NamaSekolah ?? '' }}" required
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NPSN *</label>
                                <input type="text" name="NPSN" value="{{ $instansi->NPSN ?? '' }}" required
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenjang Sekolah *</label>
                                <select name="JenjangSekolah" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="SD" {{ (isset($instansi) && $instansi->JenjangSekolah == 'SD') ? 'selected' : '' }}>SD</option>
                                    <option value="SMP" {{ (isset($instansi) && $instansi->JenjangSekolah == 'SMP') ? 'selected' : '' }}>SMP</option>
                                    <option value="SMA" {{ (isset($instansi) && $instansi->JenjangSekolah == 'SMA') ? 'selected' : '' }}>SMA</option>
                                    <option value="SMK" {{ (isset($instansi) && $instansi->JenjangSekolah == 'SMK') ? 'selected' : '' }}>SMK</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Sekolah *</label>
                                <input type="email" name="EmailSekolah" value="{{ $instansi->EmailSekolah ?? '' }}" required
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kepala Sekolah</label>
                                <input type="text" name="NamaKepalaSekolah" value="{{ $instansi->NamaKepalaSekolah ?? '' }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIP Kepala Sekolah</label>
                                <input type="text" name="NIPKepalaSekolah" value="{{ $instansi->NIPKepalaSekolah ?? '' }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                                <input type="text" name="KodePos" value="{{ $instansi->KodePos ?? '' }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Logo Sekolah</label>
                                <input type="file" name="Logo" accept="image/*" 
                                       class="w-full text-sm text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @if($instansi && $instansi->Logo)
                                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah logo</p>
                                @endif
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="Status" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="Aktif" {{ (isset($instansi) && $instansi->Status == 'Aktif') ? 'selected' : '' }}>Aktif</option>
                                    <option value="Inactive" {{ (isset($instansi) && $instansi->Status == 'Inactive') ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                        <button type="button" @click="showSchoolModal = false" 
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            @else
                <div class="p-6 text-center">
                    <div class="text-red-500 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Data Sekolah Belum Tersedia</h3>
                    <p class="text-gray-600">Anda belum memiliki data sekolah. Silakan hubungi administrator.</p>
                    <button type="button" @click="showSchoolModal = false" 
                            class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Tutup
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>