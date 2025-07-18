<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
        
        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Theme initialization script (must be before body) -->
        <script>
            // Initialize theme immediately to prevent flash
            (function() {
                if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            })();
        </script>
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * {
                transition: background-color 0.5s ease, color 0.5s ease;
            }
            .dark {
                color-scheme: dark;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: true }" @toggle-sidebar.window="sidebarOpen = !sidebarOpen" class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <!-- Navbar -->
            @include('layouts.navigation')

            <div class="flex min-h-screen">
                <!-- Sidebar -->
                @include('layouts.sidebar')

                <!-- Page Content -->
                <main class="flex-1 overflow-hidden mt-16 ml-64 transition-all duration-300" :class="{ 'ml-64': sidebarOpen, 'ml-0': !sidebarOpen }">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <script>
            // Theme toggle function
            function toggleTheme() {
                const isDark = document.documentElement.classList.contains('dark');
                
                if (isDark) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
                
                // Debug log
                console.log('Theme toggled to:', isDark ? 'light' : 'dark');
            }

            // Listen for storage changes (for multiple tabs)
            window.addEventListener('storage', function(e) {
                if (e.key === 'theme') {
                    if (e.newValue === 'dark') {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            });
        </script>
    </body>
</html>