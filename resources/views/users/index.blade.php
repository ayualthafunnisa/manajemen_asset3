@extends('layouts.app')

@section('title', 'Manajemen User - Jobie')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Manajemen User</h1>
        <p class="mt-1 text-neutral-600">Kelola data pengguna aplikasi</p>
    </div>
    <div class="mt-2 sm:mt-0">
        <a href="{{ route('user.create') }}"
           class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700 transition">
            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah User
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-4">

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg flex items-start">
        <svg class="h-5 w-5 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <p class="text-sm text-green-700">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg flex items-start">
        <svg class="h-5 w-5 text-red-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <p class="text-sm text-red-700">{{ session('error') }}</p>
    </div>
    @endif

    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">

        {{-- Filter --}}
        <div class="p-4 border-b border-neutral-200 bg-gray-50 space-y-3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <input type="text" id="search" placeholder="Cari nama, email, atau instansi..."
                       class="pl-9 pr-4 py-2.5 w-full text-sm border border-neutral-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>
            <div class="grid grid-cols-2 gap-2">
                <select id="roleFilter" class="w-full px-3 py-2 text-sm border border-neutral-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">Semua Role</option>
                    <option value="super_admin">Super Admin</option>
                    <option value="admin_sekolah">Admin Sekolah</option>
                    <option value="petugas">Petugas</option>
                    <option value="teknisi">Teknisi</option>
                </select>
                <select id="statusFilter" class="w-full px-3 py-2 text-sm border border-neutral-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">Semua Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>
        </div>

        {{-- DESKTOP TABLE --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">No</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">User</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Instansi</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Kontak</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Role</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200" id="tableBody">
                    @forelse ($users as $index => $user)
                    @php
                        $roleColor = [
                            'super_admin'   => 'bg-purple-100 text-purple-800',
                            'admin_sekolah' => 'bg-blue-100 text-blue-800',
                            'petugas'       => 'bg-green-100 text-green-800',
                            'teknisi'       => 'bg-orange-100 text-orange-800',
                        ];
                        $statusColor = [
                            'active'    => 'bg-green-100 text-green-800',
                            'inactive'  => 'bg-yellow-100 text-yellow-800',
                            'suspended' => 'bg-red-100 text-red-800',
                        ];
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors duration-150 user-row"
                        data-role="{{ $user->role }}"
                        data-status="{{ $user->status }}">
                        <td class="px-5 py-4 whitespace-nowrap text-sm text-neutral-500">{{ $users->firstItem() + $index }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                                    <span class="text-purple-700 font-semibold text-sm">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-neutral-900 truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-neutral-500 truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            @if($user->instansi)
                                <p class="text-sm font-medium text-neutral-900 max-w-[160px] truncate">{{ $user->instansi->NamaSekolah ?? '-' }}</p>
                                <p class="text-xs text-neutral-400">ID: {{ $user->InstansiID }}</p>
                            @else
                                <span class="text-sm text-neutral-400">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-sm text-neutral-600">{{ $user->phone ?? '-' }}</td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full {{ $roleColor[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ str_replace('_', ' ', ucfirst($user->role)) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full {{ $statusColor[$user->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('user.edit', $user->id) }}" title="Edit" class="text-purple-600 hover:text-purple-900 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Hapus" class="text-red-400 hover:text-red-700 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-10 w-10 text-neutral-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <p class="text-sm text-neutral-500">Tidak ada user</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE CARD LIST --}}
        <div class="md:hidden divide-y divide-gray-100" id="userCardList">
            @forelse ($users as $user)
            @php
                $roleColor = [
                    'super_admin'   => 'bg-purple-100 text-purple-800',
                    'admin_sekolah' => 'bg-blue-100 text-blue-800',
                    'petugas'       => 'bg-green-100 text-green-800',
                    'teknisi'       => 'bg-orange-100 text-orange-800',
                ];
                $statusColor = [
                    'active'    => 'bg-green-100 text-green-800',
                    'inactive'  => 'bg-yellow-100 text-yellow-800',
                    'suspended' => 'bg-red-100 text-red-800',
                ];
            @endphp
            <div class="user-row p-4" data-role="{{ $user->role }}" data-status="{{ $user->status }}">
                <div class="flex items-start gap-3">
                    {{-- Avatar --}}
                    <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-purple-700 font-semibold text-sm">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                    </div>
                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-neutral-900 truncate">{{ $user->name }}</p>
                                <p class="text-xs text-neutral-500 truncate">{{ $user->email }}</p>
                            </div>
                            <span class="flex-shrink-0 px-2 py-0.5 text-xs font-medium rounded-full {{ $statusColor[$user->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>

                        <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-xs text-neutral-600">
                            <div>
                                <span class="inline-flex px-2 py-0.5 rounded-full {{ $roleColor[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ str_replace('_', ' ', ucfirst($user->role)) }}
                                </span>
                            </div>
                            @if($user->instansi)
                            <div class="truncate max-w-[180px]"><span class="text-gray-400">Instansi:</span> {{ $user->instansi->NamaSekolah ?? '-' }}</div>
                            @endif
                            @if($user->phone)
                            <div><span class="text-gray-400">Telp:</span> {{ $user->phone }}</div>
                            @endif
                        </div>

                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('user.edit', $user->id) }}"
                               class="flex-1 text-center text-xs px-3 py-1.5 rounded-lg border border-purple-200 text-purple-600 hover:bg-purple-50 transition font-medium">
                                Edit
                            </a>
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="flex-1"
                                  onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-full text-xs px-3 py-1.5 rounded-lg border border-red-200 text-red-500 hover:bg-red-50 transition font-medium">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <svg class="mx-auto h-10 w-10 text-neutral-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <p class="text-sm text-neutral-500">Tidak ada user</p>
                <a href="{{ route('user.create') }}" class="mt-1 inline-flex text-sm text-purple-600">Tambah user baru</a>
            </div>
            @endforelse
        </div>

        @if ($users->hasPages())
        <div class="px-4 py-4 border-t border-neutral-200 bg-gray-50">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput  = document.getElementById('search');
    const roleFilter   = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');

    function applyFilters() {
        const s  = searchInput.value.toLowerCase();
        const r  = roleFilter.value;
        const st = statusFilter.value;

        document.querySelectorAll('.user-row').forEach(row => {
            const text = row.textContent.toLowerCase();
            const match = (!s  || text.includes(s))
                       && (!r  || row.dataset.role   === r)
                       && (!st || row.dataset.status === st);
            row.style.display = match ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', applyFilters);
    roleFilter.addEventListener('change', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
});
</script>
@endpush