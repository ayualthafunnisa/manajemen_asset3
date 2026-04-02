<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard - Asset')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800">
    <!-- Splashscreen -->
    @include('partials.splashscreen')
    <!-- Loading Indicator -->
    <div id="loading" class="fixed inset-0 z-50 flex items-center justify-center bg-white bg-opacity-90 hidden">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
    </div>

    <!-- Notification Container -->
    <div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm"></div>

    <div class="min-h-screen flex">
        <!-- Sidebar -->
        @include('partials.sidebar')
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Navbar -->
            @include('partials.navbar')
            
            <!-- Main Content Area -->
            <main class="flex-1 p-4 md:p-6">
                <!-- Breadcrumbs -->
                @hasSection('breadcrumbs')
                    @yield('breadcrumbs')
                @endif
                
                <!-- Page Header -->
                @hasSection('header')
                    <div class="mb-6">
                        @yield('header')
                    </div>
                @endif
                
                <!-- Page Content -->
                <div class="space-y-6">
                    @yield('content')
                </div>
            </main>
            
            <!-- Footer -->
            @include('partials.footer')
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Loading indicator
        document.addEventListener('DOMContentLoaded', function() {
            const loading = document.getElementById('loading');
            if (loading) loading.classList.add('hidden');
        });

        // CSRF token for AJAX requests
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Axios default headers
        if (typeof axios !== 'undefined') {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrfToken;
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        }
    </script>
    
    @stack('scripts')
    
    <!-- Custom Scripts -->
    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Close sidebar when clicking overlay
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        // Notification function
        function showNotification(message, type = 'success') {
            const container = document.getElementById('notification-container');
            const id = 'notification-' + Date.now();
            
            const colors = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                warning: 'bg-yellow-500 text-white',
                info: 'bg-blue-500 text-white'
            };
            
            const notification = document.createElement('div');
            notification.id = id;
            notification.className = `p-4 rounded-lg shadow-lg ${colors[type]} transform transition-all duration-300`;
            notification.innerHTML = `
                <div class="flex items-center justify-between">
                    <span>${message}</span>
                    <button onclick="document.getElementById('${id}').remove()" class="ml-4 text-white hover:text-opacity-80">
                        ✕
                    </button>
                </div>
            `;
            
            container.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (document.getElementById(id)) {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-10px)';
                    setTimeout(() => notification.remove(), 300);
                }
            }, 5000);
        }
    </script>
</body>
</html>

