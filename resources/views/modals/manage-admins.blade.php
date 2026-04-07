<!-- resources/views/modals/manage-admins.blade.php -->
<div x-show="showAdminModal" 
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
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showAdminModal = false"></div>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            @php
                $instansiId = isset($instansi) && $instansi ? $instansi->InstansiID : null;
                $admins = [];
                if($instansiId) {
                    $admins = App\Models\User::where('InstansiID', $instansiId)
                        ->where('role', 'admin_sekolah')
                        ->get();
                }
            @endphp
            
            <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Kelola Admin Sekolah</h3>
                <p class="text-green-100 text-sm mt-1">Tambah, edit, atau hapus admin sekolah</p>
            </div>
            
            <div class="bg-white px-6 pt-5 pb-4">
                @if($instansiId)
                    <!-- Daftar Admin -->
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-900 mb-3">Daftar Admin</h4>
                        <div class="space-y-3 max-h-80 overflow-y-auto">
                            @forelse($admins as $admin)
                                <div class="border rounded-lg p-3 hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-green-400 to-teal-500 flex items-center justify-center text-white font-semibold">
                                                {{ substr($admin->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $admin->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $admin->email }}</p>
                                                <p class="text-xs text-gray-500">Telp: {{ $admin->phone ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex px-2 py-1 text-xs rounded-full
                                                {{ $admin->status == 'active' ? 'bg-green-100 text-green-800' : 
                                                   ($admin->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($admin->status) }}
                                            </span>
                                            <div class="mt-2 flex space-x-2">
                                                <button onclick="editAdmin({{ $admin->id }})" 
                                                        class="text-blue-600 hover:text-blue-800 text-sm">
                                                    Edit
                                                </button>
                                                <form action="{{ route('admin.delete', $admin->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus admin ini?')" 
                                                            class="text-red-600 hover:text-red-800 text-sm">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-4">Belum ada admin sekolah</p>
                            @endforelse
                        </div>
                    </div>
                    
                    <!-- Form Tambah Admin -->
                    <div class="border-t pt-4">
                        <h4 class="font-medium text-gray-900 mb-3">Tambah Admin Baru</h4>
                        <form action="{{ route('admin.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="InstansiID" value="{{ $instansiId }}">
                            <input type="hidden" name="role" value="admin_sekolah">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                                    <input type="text" name="name" required 
                                           class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="email" required 
                                           class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                                    <input type="password" name="password" required 
                                           class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                                    <input type="text" name="phone" 
                                           class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                        <option value="active">Active</option>
                                        <option value="pending">Pending</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="w-full md:w-auto px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    + Tambah Admin
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-red-500 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Data Sekolah Belum Tersedia</h3>
                        <p class="text-gray-600">Anda belum memiliki data sekolah. Silakan hubungi administrator untuk menambahkan admin.</p>
                    </div>
                @endif
            </div>
            
            <div class="bg-gray-50 px-6 py-4 flex justify-end">
                <button type="button" @click="showAdminModal = false" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function editAdmin(userId) {
    window.location.href = '/admin/' + userId + '/edit';
}
</script>