<header class="sticky top-0 z-40 bg-background-header border-b border-neutral-200 shadow-soft">
    <div class="px-4 md:px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Left Section: Mobile Menu Button & Search -->
            <div class="flex items-center space-x-4">
                <!-- Mobile Menu Button -->
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-neutral-100 transition-colors">
                    <svg class="w-6 h-6 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                
            
            </div>
            
            <!-- Right Section: Notifications, Profile, etc. -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <div class="relative">
                    <button class="p-2 rounded-lg hover:bg-neutral-100 transition-colors relative">
                        <svg class="w-6 h-6 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-status-pending rounded-full"></span>
                    </button>
                </div>
                            
                <!-- User Profile -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-neutral-100 transition-colors">
                        
                        {{-- Logo Sekolah --}}
                        @php
                            $instansi = auth()->user()->instansi ?? null;
                        @endphp
                        
                        @if($instansi && $instansi->Logo)
                            <img src="{{ asset('storage/' . $instansi->Logo) }}" 
                                alt="Logo Sekolah"
                                class="w-8 h-8 rounded-full object-cover border border-neutral-200">
                        @else
                            <div class="w-8 h-8 rounded-full bg-gradient-primary flex items-center justify-center text-white font-semibold">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                        @endif
                        
                        <div class="hidden md:block text-left">
                            <p class="text-sm font-medium text-neutral-800">{{ auth()->user()->name ?? 'User' }}</p>
                            <p class="text-xs text-neutral-500">{{ auth()->user()->role ?? 'Member' }}</p>
                        </div>
                        <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-dp-lg border border-neutral-200 py-1 z-50"
                         style="display: none;">
                        <a href="" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                            👤 Profile Settings
                        </a>
                        <a href="" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                            ⚙️ Account Settings
                        </a>
                        <div class="border-t border-neutral-200 my-1"></div>
                        <form method="POST" action="">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-status-rejected hover:bg-neutral-50">
                                🚪 Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile Search -->
        <div class="mt-4 md:hidden">
            <div class="flex items-center bg-white border border-neutral-200 rounded-lg px-4 py-2 shadow-soft">
                <svg class="w-5 h-5 text-neutral-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" placeholder="Search..." 
                       class="flex-1 bg-transparent border-none focus:outline-none text-neutral-700 placeholder-neutral-400">
            </div>
        </div>
    </div>
</header>