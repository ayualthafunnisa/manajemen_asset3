<!-- resources/views/components/navbar.blade.php -->
<header class="sticky top-0 z-40 bg-white border-b border-neutral-200 shadow-soft">
    <div class="px-4 md:px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Left Section: Mobile Menu Button & Logo -->
            <div class="flex items-center space-x-4">
                <!-- Mobile Menu Button -->
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-neutral-100 transition-colors">
                    <svg class="w-6 h-6 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-primary flex items-center justify-center shadow-dp">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Right Section: Profile Only -->
            <div class="flex items-center">
                <!-- User Profile Dropdown -->
                <div class="relative" x-data="{ 
                    open: false, 
                    showSchoolModal: false, 
                    showAdminModal: false
                }" @click.outside="open = false">
                    
                    <button @click="open = !open" 
                            class="flex items-center space-x-3 px-3 py-2 rounded-xl hover:bg-primary-50 transition-all duration-200 group">
                        {{-- Profile Picture --}}
                        @php
                            $user = auth()->user();
                            $instansi = $user->instansi ?? null;
                        @endphp
                        
                        @if($instansi && $instansi->Logo)
                            <img src="{{ asset('storage/' . $instansi->Logo) }}" 
                                alt="Logo Sekolah"
                                class="w-9 h-9 rounded-xl object-cover border-2 border-primary-200 group-hover:border-primary-400 transition-colors">
                        @else
                            <div class="w-9 h-9 rounded-xl bg-gradient-primary flex items-center justify-center text-white font-semibold text-sm shadow-dp">
                                {{ substr($user->name ?? 'A', 0, 2) }}
                            </div>
                        @endif
                        
                        <div class="hidden md:block text-left">
                            <p class="text-sm font-semibold text-neutral-800">{{ $user->name ?? 'Admin' }}</p>
                            <p class="text-xs text-primary-600">
                                @php
                                    $roleNames = [
                                        'super_admin' => 'Super Admin',
                                        'admin_sekolah' => 'Admin Sekolah',
                                        'petugas' => 'Petugas',
                                        'teknisi' => 'Teknisi'
                                    ];
                                @endphp
                                {{ $roleNames[$user->role ?? ''] ?? 'Member' }}
                            </p>
                        </div>
                        
                        <svg class="w-4 h-4 text-neutral-400 transition-transform duration-200 group-hover:text-primary-500" 
                             :class="{ 'rotate-180': open }" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu Modern -->
                    <div x-show="open" 
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-dp-lg border border-neutral-100 overflow-hidden z-50">
                        
                        <!-- Profile Header -->
                        <div class="px-5 py-4 bg-gradient-to-r from-primary-50 to-primary-100 border-b border-primary-100">
                            <div class="flex items-center space-x-3">
                                @if($instansi && $instansi->Logo)
                                    <img src="{{ asset('storage/' . $instansi->Logo) }}" 
                                        alt="Logo Sekolah"
                                        class="w-12 h-12 rounded-xl object-cover border-2 border-white shadow-dp">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-gradient-primary flex items-center justify-center text-white font-bold shadow-dp">
                                        {{ substr($user->name ?? 'A', 0, 2) }}
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-neutral-800">{{ $user->name ?? 'Admin' }}</p>
                                    <p class="text-xs text-primary-600 mt-0.5">{{ $user->email ?? 'admin@sekolah.com' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Menu Items -->
                        <div class="py-2">
                            <!-- Data Sekolah -->
                            @if(in_array($user->role ?? '', ['admin_sekolah', 'super_admin']))
                                <button @click="open = false; showSchoolModal = true" 
                                        class="w-full text-left px-5 py-3 text-sm text-neutral-700 hover:bg-primary-50 hover:text-primary-700 flex items-center space-x-3 transition-all duration-200 group">
                                    <div class="w-8 h-8 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center group-hover:bg-primary-600 group-hover:text-white transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium">Data Sekolah</span>
                                </button>
                                
                                <button @click="open = false; showAdminModal = true" 
                                        class="w-full text-left px-5 py-3 text-sm text-neutral-700 hover:bg-primary-50 hover:text-primary-700 flex items-center space-x-3 transition-all duration-200 group">
                                    <div class="w-8 h-8 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center group-hover:bg-primary-600 group-hover:text-white transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium">Kelola Admin</span>
                                </button>
                                
                                <div class="border-t border-neutral-100 my-2"></div>
                            @endif
                            
                            <!-- Profile Settings -->
                            <a href="{{ route('profile.settings') }}" 
                               class="block px-5 py-3 text-sm text-neutral-700 hover:bg-primary-50 hover:text-primary-700 flex items-center space-x-3 transition-all duration-200 group">
                                <div class="w-8 h-8 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center group-hover:bg-primary-600 group-hover:text-white transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Profil Saya</span>
                            </a>
                            
                            <a href="{{ route('account.settings') }}" 
                               class="block px-5 py-3 text-sm text-neutral-700 hover:bg-primary-50 hover:text-primary-700 flex items-center space-x-3 transition-all duration-200 group">
                                <div class="w-8 h-8 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center group-hover:bg-primary-600 group-hover:text-white transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Pengaturan Akun</span>
                            </a>
                            
                            <div class="border-t border-neutral-100 my-2"></div>
                            
                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" 
                                        class="w-full text-left px-5 py-3 text-sm text-danger-600 hover:bg-danger-50 flex items-center space-x-3 transition-all duration-200 group">
                                    <div class="w-8 h-8 rounded-lg bg-danger-100 text-danger-600 flex items-center justify-center group-hover:bg-danger-600 group-hover:text-white transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium">Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Modal Edit Data Sekolah -->
                    @include('modals.edit-school', ['instansi' => $instansi])
                    
                    <!-- Modal Kelola Admin Sekolah -->
                    @include('modals.manage-admins', ['instansi' => $instansi])
                </div>
            </div>
        </div>
    </div>
</header>

<style>
[x-cloak] { display: none !important; }
</style>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        sidebar.classList.toggle('translate-x-0');
        sidebar.classList.toggle('-translate-x-full');
    }
}

// Ensure Alpine.js is loaded
document.addEventListener('alpine:init', () => {
    console.log('Alpine.js initialized');
});
</script>