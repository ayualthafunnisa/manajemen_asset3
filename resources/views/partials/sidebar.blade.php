<!-- Mobile Overlay -->
<div id="sidebar-overlay"
     class="lg:hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-30 hidden"
     onclick="closeSidebar()"></div>

<!-- Sidebar -->
<aside id="sidebar"
       class="fixed lg:sticky top-0 left-0 h-screen w-64 bg-white border-r border-neutral-100 shadow-xl z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col">

    {{-- ===== LOGO ===== --}}
    <div class="px-5 py-5 border-b border-neutral-100">
        <a href="" class="flex items-center gap-3 group">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-md shadow-primary-200 group-hover:shadow-primary-300 transition-shadow">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-extrabold tracking-tight text-neutral-900 leading-none">
                    Asset<span class="text-primary-600">.</span>
                </h1>
                <p class="text-[10px] text-neutral-400 mt-0.5 font-medium tracking-wide uppercase">SMK Informatika Utama</p>
            </div>
        </a>
    </div>

    {{-- ===== NAVIGATION ===== --}}
    <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-0.5 scrollbar-thin scrollbar-thumb-neutral-200">

        @php
            $role = auth()->user()->role ?? null;
            $navItems = [];

            // Dashboard
            $dashRoutes = [
                'super_admin'   => 'dashboard.superadmin',
                'admin_sekolah' => 'dashboard.admin',
                'staf_asset'    => 'dashboard.staf',
                'teknisi'       => 'dashboard.teknisi',
            ];
            if (isset($dashRoutes[$role])) {
                $navItems[] = [
                    'route' => $dashRoutes[$role],
                    'label' => 'Dashboard',
                    'icon'  => 'M3 12l9-9 9 9M4 10v10a1 1 0 001 1h5m8-11v10a1 1 0 01-1 1h-5',
                    'group' => null,
                ];
            }

            // Super Admin only
            if ($role === 'super_admin') {
                $navItems[] = [
                    'route' => 'instansi.index',
                    'label' => 'Instansi',
                    'icon'  => 'M3 21h18M5 21V7a2 2 0 012-2h10a2 2 0 012 2v14M9 9h2m-2 4h2m4-4h2m-2 4h2',
                    'group' => 'Manajemen',
                ];
            }

            // Admin Sekolah
            if ($role === 'admin_sekolah') {
                $navItems[] = ['route' => 'kategori.index', 'label' => 'Kategori',    'icon' => 'M3 7a2 2 0 012-2h3l2 2h9a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z', 'group' => 'Master Data'];
                $navItems[] = ['route' => 'lokasi.index',  'label' => 'Lokasi Asset', 'icon' => 'M12 21s-6-5.686-6-10a6 6 0 1112 0c0 4.314-6 10-6 10zM12 11a2 2 0 100-4 2 2 0 000 4z', 'group' => 'Master Data'];
                $navItems[] = ['route' => 'user.index',    'label' => 'User',         'icon' => 'M17 20h5v-2a4 4 0 00-5.477-3.685M9 20H4v-2a4 4 0 015.477-3.685M15 7a4 4 0 11-8 0 4 4 0 018 0z', 'group' => 'Master Data'];
                $navItems[] = ['route' => 'penyusutan.index', 'label' => 'Penyusutan', 'icon' => 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6', 'group' => 'Laporan'];
            }

            // Admin Sekolah + Staf Asset
            if (in_array($role, ['admin_sekolah', 'petugas'])) {
                $navItems[] = ['route' => 'asset.index',      'label' => 'Asset',      'icon' => 'M20 7l-8-4-8 4m16 0v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7m16 0l-8 4-8-4', 'group' => 'Manajemen'];
                $navItems[] = ['route' => 'kerusakan.index',  'label' => 'Kerusakan',  'icon' => 'M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z', 'group' => 'Manajemen'];
                $navItems[] = ['route' => 'penghapusan.index','label' => 'Penghapusan','icon' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16', 'group' => 'Manajemen'];
            }

            // Teknisi
            if ($role === 'teknisi') {
                $navItems[] = ['route' => 'keluhan.index', 'label' => 'Keluhan Masuk',    'icon' => 'M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z', 'group' => 'Pekerjaan'];
                $navItems[] = ['route' => 'riwayat.index', 'label' => 'Riwayat Perbaikan','icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'group' => 'Pekerjaan'];
            }

            // Group items
            $grouped = [];
            foreach ($navItems as $item) {
                $grouped[$item['group'] ?? ''][] = $item;
            }
        @endphp

        @foreach ($grouped as $groupLabel => $items)
            @if ($groupLabel)
                <p class="px-3 pt-4 pb-1 text-[10px] font-semibold uppercase tracking-widest text-neutral-400 select-none">
                    {{ $groupLabel }}
                </p>
            @endif

            @foreach ($items as $item)
                @php $isActive = request()->routeIs($item['route']); @endphp

                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                          {{ $isActive
                              ? 'bg-primary-50 text-primary-700 shadow-sm'
                              : 'text-neutral-500 hover:bg-neutral-50 hover:text-neutral-800' }}">

                    <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg transition-colors
                                 {{ $isActive ? 'bg-primary-100 text-primary-600' : 'text-neutral-400 group-hover:text-neutral-600' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="{{ $item['icon'] }}"/>
                        </svg>
                    </span>

                    <span>{{ $item['label'] }}</span>

                    @if ($isActive)
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-primary-500"></span>
                    @endif
                </a>
            @endforeach
        @endforeach

        {{-- Divider --}}
        <div class="pt-3 mt-3 border-t border-neutral-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-red-400 hover:bg-red-50 hover:text-red-600 transition-all duration-150">
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-red-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </span>
                    Logout
                </button>
            </form>
        </div>

    </nav>

    {{-- ===== USER PROFILE (Bottom) ===== --}}
    <div class="px-4 py-4 border-t border-neutral-100">
        <div class="flex items-center gap-3 px-3 py-3 bg-neutral-50 rounded-xl">

            @php $instansi = auth()->user()->instansi ?? null; @endphp

            @if($instansi && $instansi->Logo)
                <img src="{{ asset('storage/' . $instansi->Logo) }}"
                     alt="Logo Sekolah"
                     class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm flex-shrink-0">
            @else
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
            @endif

            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-neutral-800 truncate leading-tight">
                    {{ auth()->user()->name ?? 'User' }}
                </p>
                <p class="text-[11px] text-neutral-400 truncate mt-0.5">
                    {{ auth()->user()->email ?? 'user@email.com' }}
                </p>
            </div>
        </div>
    </div>

</aside>