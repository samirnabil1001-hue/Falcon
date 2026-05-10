<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'falcon') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">

    <!-- Top Navigation -->
    @include('layouts.navigation')

    <div class="flex min-h-screen">

        <!-- Overlay -->
        <div id="overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden">
        </div>

        <!-- Sidebar -->
        <aside id="sidebar"
            class="fixed md:static top-0 left-0 z-50 w-64 h-screen
               bg-white dark:bg-gray-800 shadow-lg
               transform -translate-x-full md:translate-x-0
               transition-transform duration-300 ease-in-out">

            <!-- Logo -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">

                <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                    {{ config('app.name') }}
                </h1>

                <!-- Close Btn -->
                <button id="closeBtn" class="md:hidden text-gray-700 dark:text-gray-300">
                    ✕
                </button>
            </div>

            <!-- Navigation -->
            <nav class="p-4 space-y-2">

                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-3 rounded-lg
                   text-gray-700 dark:text-gray-200
                   hover:bg-gray-200 dark:hover:bg-gray-700 transition">

                    <span class="mr-3">icon</span>
                    Dashboard
                </a>

                <a href="{{ route('users') }}"
                    class="flex items-center px-4 py-3 rounded-lg
                   text-gray-700 dark:text-gray-200
                   hover:bg-gray-200 dark:hover:bg-gray-700 transition">

                    <span class="mr-3">icon</span>
                    Users
                </a>

                <a href="#"
                    class="flex items-center px-4 py-3 rounded-lg
                   text-gray-700 dark:text-gray-200
                   hover:bg-gray-200 dark:hover:bg-gray-700 transition">

                    <span class="mr-3">icon</span>
                    clients
                </a>

                <a href="#"
                    class="flex items-center px-4 py-3 rounded-lg
                   text-gray-700 dark:text-gray-200
                   hover:bg-gray-200 dark:hover:bg-gray-700 transition">

                    <span class="mr-3">icon</span>
                    Settings
                </a>

            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Page Header -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="px-4 sm:px-6 lg:px-8 py-4 flex items-center gap-4">

                        <!-- Mobile Menu Btn -->

                        <!-- Hamburger -->
                        <button id="menuBtn"
                            class="md:hidden p-2 rounded-lg
                                bg-gray-200 dark:bg-gray-700
                                text-gray-800 dark:text-white">

                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">

                                <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />

                                <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>

                        </button>

                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 p-4 md:p-6 overflow-x-hidden">
                {{ $slot }}
            </main>

        </div>

    </div>

    <!-- Sidebar Script -->
    <script>
        const menuBtn = document.getElementById('menuBtn');
        const closeBtn = document.getElementById('closeBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        menuBtn?.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        });

        closeBtn?.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        overlay?.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    </script>

</body>

</html>
