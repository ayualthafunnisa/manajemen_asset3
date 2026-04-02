{{-- resources/views/assets/partials/additional-fields.blade.php --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    {{-- Identitas --}}
    <div>
        <label for="merk" class="block text-sm font-medium text-gray-700 mb-1">Merk</label>
        <input type="text" name="merk" id="merk" value="{{ old('merk', $asset->merk) }}"
               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
               placeholder="Contoh: Dell, Canon">
    </div>
    
    <div>
        <label for="tipe_model" class="block text-sm font-medium text-gray-700 mb-1">Tipe/Model</label>
        <input type="text" name="tipe_model" id="tipe_model" value="{{ old('tipe_model', $asset->tipe_model) }}"
               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
               placeholder="Contoh: XPS 13, EOS 90D">
    </div>
    
    <div>
        <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-1">Serial Number</label>
        <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $asset->serial_number) }}"
               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 font-mono"
               placeholder="SN-XXXX-XXXX">
    </div>
    
    {{-- Registrasi --}}
    <div>
        <label for="nomor_registrasi" class="block text-sm font-medium text-gray-700 mb-1">Nomor Registrasi</label>
        <input type="text" name="nomor_registrasi" id="nomor_registrasi" value="{{ old('nomor_registrasi', $asset->nomor_registrasi) }}"
               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
               placeholder="REG-YYYY-XXX">
    </div>
    
    <div>
        <label for="sumber_perolehan" class="block text-sm font-medium text-gray-700 mb-1">Sumber Perolehan</label>
        <select name="sumber_perolehan" id="sumber_perolehan" 
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            <option value="">Pilih Sumber</option>
            <option value="pembelian" {{ old('sumber_perolehan', $asset->sumber_perolehan) == 'pembelian' ? 'selected' : '' }}>Pembelian</option>
            <option value="hibah" {{ old('sumber_perolehan', $asset->sumber_perolehan) == 'hibah' ? 'selected' : '' }}>Hibah</option>
            <option value="transfer" {{ old('sumber_perolehan', $asset->sumber_perolehan) == 'transfer' ? 'selected' : '' }}>Transfer</option>
            <option value="sewa" {{ old('sumber_perolehan', $asset->sumber_perolehan) == 'sewa' ? 'selected' : '' }}>Sewa</option>
            <option value="lainnya" {{ old('sumber_perolehan', $asset->sumber_perolehan) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
        </select>
    </div>
    
    <div>
        <label for="tanggal_perolehan" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Perolehan</label>
        <input type="date" name="tanggal_perolehan" id="tanggal_perolehan" 
               value="{{ old('tanggal_perolehan', $asset->tanggal_perolehan ? \Carbon\Carbon::parse($asset->tanggal_perolehan)->format('Y-m-d') : '') }}"
               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
    </div>
    
    {{-- Keuangan --}}
    <div>
        <label for="nilai_perolehan" class="block text-sm font-medium text-gray-700 mb-1">Nilai Perolehan (Rp)</label>
        <div class="relative">
            <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
            <input type="number" name="nilai_perolehan" id="nilai_perolehan" 
                   value="{{ old('nilai_perolehan', $asset->nilai_perolehan) }}"
                   step="1000" min="0"
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                   placeholder="0">
        </div>
    </div>
    
    <div>
        <label for="nilai_residu" class="block text-sm font-medium text-gray-700 mb-1">Nilai Residu (Rp)</label>
        <div class="relative">
            <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
            <input type="number" name="nilai_residu" id="nilai_residu" 
                   value="{{ old('nilai_residu', $asset->nilai_residu) }}"
                   step="1000" min="0"
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                   placeholder="0">
        </div>
    </div>
    
    <div>
        <label for="umur_ekonomis" class="block text-sm font-medium text-gray-700 mb-1">Umur Ekonomis</label>
        <div class="relative">
            <input type="number" name="umur_ekonomis" id="umur_ekonomis" 
                   value="{{ old('umur_ekonomis', $asset->umur_ekonomis) }}"
                   min="0" step="1"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                   placeholder="5">
            <span class="absolute right-3 top-2.5 text-sm text-gray-500">tahun</span>
        </div>
    </div>
    
    {{-- Spesifikasi Fisik --}}
    <div>
        <label for="satuan" class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
        <input type="text" name="satuan" id="satuan" value="{{ old('satuan', $asset->satuan ?? 'unit') }}"
               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
               placeholder="unit, buah, set">
    </div>
    
    <div>
        <label for="warna" class="block text-sm font-medium text-gray-700 mb-1">Warna</label>
        <input type="text" name="warna" id="warna" value="{{ old('warna', $asset->warna) }}"
               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
               placeholder="Contoh: Silver, Hitam">
    </div>
    
    <div>
        <label for="bahan" class="block text-sm font-medium text-gray-700 mb-1">Bahan</label>
        <input type="text" name="bahan" id="bahan" value="{{ old('bahan', $asset->bahan) }}"
               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
               placeholder="Contoh: Aluminium, Kayu">
    </div>
    
    <div>
        <label for="dimensi" class="block text-sm font-medium text-gray-700 mb-1">Dimensi</label>
        <input type="text" name="dimensi" id="dimensi" value="{{ old('dimensi', $asset->dimensi) }}"
               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
               placeholder="P x L x T (cm)">
    </div>
    
    <div>
        <label for="berat" class="block text-sm font-medium text-gray-700 mb-1">Berat</label>
        <div class="relative">
            <input type="text" name="berat" id="berat" value="{{ old('berat', $asset->berat) }}"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                   placeholder="1.5">
            <span class="absolute right-3 top-2.5 text-sm text-gray-500">kg</span>
        </div>
    </div>
    
    {{-- Vendor & Faktur --}}
    <div>
        <label for="vendor" class="block text-sm font-medium text-gray-700 mb-1">Vendor/Supplier</label>
        <input type="text" name="vendor" id="vendor" value="{{ old('vendor', $asset->vendor) }}"
               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
               placeholder="Nama vendor">
    </div>
    
    <div>
        <label for="no_faktur" class="block text-sm font-medium text-gray-700 mb-1">No. Faktur</label>
        <input type="text" name="no_faktur" id="no_faktur" value="{{ old('no_faktur', $asset->no_faktur) }}"
               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
               placeholder="FAKTUR-XXXX">
    </div>
    
    <div>
        <label for="tanggal_faktur" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Faktur</label>
        <input type="date" name="tanggal_faktur" id="tanggal_faktur" 
               value="{{ old('tanggal_faktur', $asset->tanggal_faktur ? \Carbon\Carbon::parse($asset->tanggal_faktur)->format('Y-m-d') : '') }}"
               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
    </div>
    
    <div>
        <label for="tanggal_penggunaan" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Penggunaan</label>
        <input type="date" name="tanggal_penggunaan" id="tanggal_penggunaan" 
               value="{{ old('tanggal_penggunaan', $asset->tanggal_penggunaan ? \Carbon\Carbon::parse($asset->tanggal_penggunaan)->format('Y-m-d') : '') }}"
               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
    </div>
    
    {{-- Spesifikasi Lengkap (Full Width) --}}
    <div class="md:col-span-2 lg:col-span-3">
        <label for="spesifikasi" class="block text-sm font-medium text-gray-700 mb-1">Spesifikasi Lengkap</label>
        <textarea name="spesifikasi" 
                  id="spesifikasi" 
                  rows="4"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 resize-y"
                  placeholder="Masukkan spesifikasi lengkap asset...">{{ old('spesifikasi', $asset->spesifikasi) }}</textarea>
    </div>
</div>