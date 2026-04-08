{{-- resources/views/components/navbar.blade.php --}}
<header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-neutral-100 shadow-soft">
    <div class="px-4 md:px-6 py-3.5">
        <div class="flex items-center justify-between">
            <!-- Left Section -->
            <div class="flex items-center space-x-4">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl hover:bg-neutral-100 transition-colors">
                    <svg class="w-5 h-5 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-dp">
                        <svg class="w-4.5 h-4.5 text-white w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span class="text-xl font-extrabold tracking-tight text-neutral-800 hidden sm:inline">Aset<span class="text-primary-600">Ku</span></span>
                </div>
            </div>

            <!-- Right Section -->
            <div class="flex items-center space-x-2">

                {{-- NOTIFICATION BELL --}}
                <div class="relative" x-data="notificationComponent()" x-init="init()">

                    <button @click="toggleDropdown()"
                            class="relative p-2.5 rounded-xl hover:bg-primary-50 transition-all duration-200 group">
                        <svg class="w-5 h-5 text-neutral-500 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <!-- Badge -->
                        <span x-show="unreadCount > 0"
                              x-text="unreadCount > 9 ? '9+' : unreadCount"
                              class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[10px] font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 shadow-sm">
                        </span>
                    </button>

                    <!-- Notification Dropdown -->
                    <div x-show="open"
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
                         class="absolute right-0 mt-2 w-[380px] bg-white rounded-2xl shadow-[0_20px_60px_rgba(0,0,0,0.12)] border border-neutral-100/80 overflow-hidden z-50">

                        <!-- Header -->
                        <div class="px-5 py-4 flex justify-between items-center border-b border-neutral-100">
                            <div class="flex items-center space-x-2.5">
                                <h3 class="text-sm font-bold text-neutral-800">Notifikasi</h3>
                                <span x-show="unreadCount > 0"
                                      class="bg-primary-100 text-primary-700 text-xs font-bold px-2 py-0.5 rounded-full"
                                      x-text="unreadCount + ' baru'">
                                </span>
                            </div>
                            <button @click="markAllAsRead()"
                                    x-show="unreadCount > 0"
                                    class="text-xs text-primary-600 hover:text-primary-800 font-semibold hover:bg-primary-50 px-2.5 py-1.5 rounded-lg transition-all duration-150">
                                Tandai semua dibaca
                            </button>
                        </div>

                        <!-- Notification List -->
                        <div class="max-h-[420px] overflow-y-auto notif-scroll">

                            <!-- Loading state -->
                            <div x-show="loading" class="px-5 py-8 text-center">
                                <div class="w-6 h-6 border-2 border-primary-200 border-t-primary-600 rounded-full animate-spin mx-auto"></div>
                                <p class="text-xs text-neutral-400 mt-2">Memuat...</p>
                            </div>

                            <template x-if="!loading">
                                <div>
                                    <template x-for="notif in notifications" :key="notif.id">
                                        <a :href="notif.detail_url ? notif.detail_url : '{{ url('/notifications') }}/' + notif.id" @click="handleNotifClick(notif)" class="flex items-start space-x-3.5 px-5 py-3.5 hover:bg-neutral-50/80 transition-all duration-150 cursor-pointer group border-b border-neutral-50 last:border-0 no-underline"
                                           :class="{ 'bg-primary-50/40': !notif.is_read }">

                                            <!-- Icon -->
                                            <div class="flex-shrink-0 relative">
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg"
                                                     :class="notif.is_read ? 'bg-neutral-100' : 'bg-primary-100'">
                                                    <span x-text="notif.icon || '🔔'"></span>
                                                </div>
                                                <div x-show="!notif.is_read"
                                                     class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-primary-500 rounded-full border-2 border-white"></div>
                                            </div>

                                            <!-- Content -->
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-neutral-800 group-hover:text-primary-700 transition-colors leading-snug"
                                                   x-text="notif.title"
                                                   :class="{ 'text-primary-800': !notif.is_read }"></p>
                                                <p class="text-xs text-neutral-500 mt-0.5 line-clamp-2 leading-relaxed" x-text="notif.message"></p>
                                                <div class="flex items-center space-x-1.5 mt-1.5">
                                                    <svg class="w-3 h-3 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <p class="text-[11px] text-neutral-400" x-text="notif.time_ago"></p>
                                                </div>
                                            </div>

                                            <!-- Arrow indicator -->
                                            <div class="flex-shrink-0 self-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </div>
                                        </a>
                                    </template>

                                    <!-- Empty state -->
                                    <div x-show="notifications.length === 0" class="py-14 text-center px-6">
                                        <div class="w-14 h-14 bg-neutral-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-7 h-7 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-neutral-500">Semua sudah dibaca</p>
                                        <p class="text-xs text-neutral-400 mt-1">Tidak ada notifikasi baru</p>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Footer -->
                        <div class="px-5 py-3 border-t border-neutral-100 bg-neutral-50/60">
                            <a href="{{ route('notifications.index') }}" class="flex items-center justify-center space-x-1.5 text-xs text-primary-600 hover:text-primary-800 font-semibold transition-colors py-1">
                                <span>Lihat semua notifikasi</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Profile Dropdown -->
                <div class="relative" x-data="{ profileOpen: false }">
                    <button @click="profileOpen = !profileOpen"
                            class="flex items-center space-x-2.5 px-2.5 py-2 rounded-xl hover:bg-primary-50 transition-all duration-200 group">
                        @php
                            $user = auth()->user();
                            $instansi = $user->instansi ?? null;
                        @endphp
                        @if($instansi && $instansi->Logo)
                            <img src="{{ asset('storage/' . $instansi->Logo) }}"
                                alt="Logo Sekolah"
                                class="w-8 h-8 rounded-xl object-cover border-2 border-primary-100 group-hover:border-primary-300 transition-colors">
                        @else
                            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-white font-bold text-xs shadow-dp">
                                {{ strtoupper(substr($user->name ?? 'A', 0, 2)) }}
                            </div>
                        @endif
                        <div class="hidden md:block text-left">
                            <p class="text-sm font-semibold text-neutral-800 leading-tight">{{ $user->name ?? 'Admin' }}</p>
                            <p class="text-[11px] text-primary-500 font-medium">
                                @php $roleNames = ['super_admin'=>'Super Admin','admin_sekolah'=>'Admin Sekolah','petugas'=>'Petugas','teknisi'=>'Teknisi']; @endphp
                                {{ $roleNames[$user->role ?? ''] ?? 'Member' }}
                            </p>
                        </div>
                        <svg class="w-3.5 h-3.5 text-neutral-400 transition-transform duration-200 hidden md:block"
                             :class="{ 'rotate-180': profileOpen }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Profile Dropdown -->
                    <div x-show="profileOpen"
                         @click.away="profileOpen = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
                         class="absolute right-0 mt-2 w-72 bg-white rounded-2xl shadow-[0_20px_60px_rgba(0,0,0,0.12)] border border-neutral-100/80 overflow-hidden z-50">

                        <div class="p-5 border-b border-neutral-100">
                            <div class="flex items-center space-x-3">
                                @if($instansi && $instansi->Logo)
                                    <img src="{{ asset('storage/' . $instansi->Logo) }}"
                                        alt="Logo"
                                        class="w-11 h-11 rounded-xl object-cover border-2 border-primary-100">
                                @else
                                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-white font-bold shadow-dp">
                                        {{ strtoupper(substr($user->name ?? 'A', 0, 2)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-bold text-neutral-800">{{ $user->name ?? 'Admin' }}</p>
                                    <p class="text-xs text-neutral-400 mt-0.5">{{ $user->email ?? '' }}</p>
                                    <span class="inline-block mt-1 text-[10px] font-semibold bg-primary-100 text-primary-700 px-2 py-0.5 rounded-full">
                                        {{ $roleNames[$user->role ?? ''] ?? 'Member' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="p-2">
                            <a href="{{ route('profile.index') }}"
                            class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm text-neutral-700 hover:bg-primary-50 hover:text-primary-700 transition-all duration-150 group">
                                <div class="w-7 h-7 rounded-lg bg-neutral-100 group-hover:bg-primary-100 flex items-center justify-center transition-colors">
                                    <svg class="w-3.5 h-3.5 text-neutral-500 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Profil Saya</span>
                            </a>

                            <div class="my-1.5 border-t border-neutral-100"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm text-red-600 hover:bg-red-50 transition-all duration-150 group">
                                    <div class="w-7 h-7 rounded-lg bg-red-50 group-hover:bg-red-100 flex items-center justify-center transition-colors">
                                        <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium">Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
[x-cloak] { display: none !important; }
.notif-scroll::-webkit-scrollbar { width: 4px; }
.notif-scroll::-webkit-scrollbar-track { background: transparent; }
.notif-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 99px; }
.notif-scroll::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        sidebar.classList.toggle('translate-x-0');
        sidebar.classList.toggle('-translate-x-full');
    }
}

function notificationComponent() {
    return {
        open: false,
        loading: false,
        notifications: [],
        unreadCount: 0,

        init() {
            this.fetchNotifications();
            setInterval(() => this.fetchNotifications(), 30000);
        },

        toggleDropdown() {
            this.open = !this.open;
            if (this.open) {
                this.loading = true;
                this.fetchNotifications().finally(() => { this.loading = false; });
            }
        },

        async fetchNotifications() {
            try {
                const res = await fetch('{{ route("notifications.unread") }}');
                const data = await res.json();
                this.unreadCount = data.count;
                this.notifications = data.notifications;
            } catch (e) {
                console.error('Error fetching notifications:', e);
            }
        },

        handleNotifClick(notif) {
            // Auto-mark as read on click, then navigate (link handles navigation)
            if (!notif.is_read) {
                fetch(`/notifications/${notif.id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).catch(e => console.error(e));
                notif.is_read = true;
                this.unreadCount = Math.max(0, this.unreadCount - 1);
            }
            this.open = false;
        },

        markAllAsRead() {
            fetch('{{ route("notifications.mark-all-read") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(() => {
                this.notifications.forEach(n => n.is_read = true);
                this.unreadCount = 0;
            }).catch(e => console.error(e));
        }
    }
}
</script>