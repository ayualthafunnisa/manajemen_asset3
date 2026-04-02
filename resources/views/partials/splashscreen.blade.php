<!-- Modern Splashscreen -->
<div id="splashscreen" class="fixed inset-0 z-[100] flex items-center justify-center bg-white" style="transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1)">
    <div class="text-center px-4">
        <!-- Animated Logo dengan Floating Effect -->
        <div class="relative mb-6">
            <!-- Glow Effect -->
            <div class="absolute inset-0 bg-purple-500 rounded-full blur-xl opacity-20 animate-pulse"></div>
            
            <!-- Logo Container dengan Scale Animation -->
            <div class="relative w-20 h-20 mx-auto">
                <div class="absolute inset-0 bg-gradient-to-tr from-purple-600 to-purple-400 rounded-2xl rotate-45 animate-[spin_8s_linear_infinite] opacity-20"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl shadow-lg flex items-center justify-center transform hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- App Name dengan Clean Typography -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-1 tracking-tight">
            Asset
        </h2>
        
        <p class="text-sm text-gray-500 mb-8 font-medium tracking-wide">Management</p>

        <!-- Modern Progress Indicator -->
        <div class="flex items-center justify-center space-x-1.5">
            <div class="w-1.5 h-1.5 bg-purple-600 rounded-full animate-[bounce_1s_infinite]"></div>
            <div class="w-1.5 h-1.5 bg-purple-600 rounded-full animate-[bounce_1s_infinite_0.2s]"></div>
            <div class="w-1.5 h-1.5 bg-purple-600 rounded-full animate-[bounce_1s_infinite_0.4s]"></div>
            <span class="text-xs text-gray-400 ml-2 font-medium" id="loading-text">Loading...</span>
        </div>
    </div>
</div>

<script>
    (function() {
        // Loader messages yang lebih fresh
        const messages = [
            'Menyiapkan workspace',
            'Memuat komponen',
            'Mengoptimalkan tampilan',
            'Hampir selesai'
        ];
        
        let index = 0;
        const loadingText = document.getElementById('loading-text');
        const splash = document.getElementById('splashscreen');
        
        // Rotate messages setiap 800ms
        const messageInterval = setInterval(() => {
            if (loadingText) {
                index = (index + 1) % messages.length;
                loadingText.style.opacity = '0';
                setTimeout(() => {
                    loadingText.textContent = messages[index] + '...';
                    loadingText.style.opacity = '1';
                }, 150);
            }
        }, 800);
        
        // Fungsi untuk hide splashscreen dengan smooth transition
        window.hideSplashscreen = function() {
            clearInterval(messageInterval);
            
            if (splash) {
                splash.style.opacity = '0';
                setTimeout(() => {
                    splash.style.display = 'none';
                }, 400);
            }
        };
        
        // Auto hide setelah 2.5 detik (fallback)
        setTimeout(() => {
            if (splash && splash.style.display !== 'none') {
                window.hideSplashscreen();
            }
        }, 2500);
    })();
</script>