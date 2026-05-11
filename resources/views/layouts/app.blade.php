<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

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

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 overflow-hidden h-screen">

    <!-- Top Navigation (Standard Breeze Nav) -->
    @include('layouts.navigation')

    <div class="flex h-screen overflow-hidden">

        <!-- Overlay: يظهر عند فتح القائمة في الموبايل -->
        <div id="overlay"
            class="fixed inset-0 bg-black/50 z-40 hidden md:hidden">
        </div>

        <!-- Sidebar: تم تعديل الاتجاه لليمين -->
        <aside id="sidebar"
            class="fixed md:static top-0 right-0 z-50 w-64 h-full
                   bg-white dark:bg-gray-800 shadow-lg
                   transform translate-x-full md:translate-x-0
                   transition-transform duration-300 ease-in-out">

            <!-- Logo & Close Btn -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                    {{ config('app.name') }}
                </h1>

                <!-- Close Btn (Mobile only) -->
                <button id="closeBtn" class="md:hidden text-gray-700 dark:text-gray-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-2">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-3 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <span class="ml-3 italic text-xs text-gray-400">icon</span>
                    Dashboard
                </a>

                <a href="{{ route('users.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <span class="ml-3 italic text-xs text-gray-400">icon</span>
                    Users
                </a>

                <a href="#"
                    class="flex items-center px-4 py-3 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <span class="ml-3 italic text-xs text-gray-400">icon</span>
                    Clients
                </a>

                <a href="#"
                    class="flex items-center px-4 py-3 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <span class="ml-3 italic text-xs text-gray-400">icon</span>
                    Settings
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden text-right">

            <!-- Page Header Container -->
            <header class="bg-white dark:bg-gray-800 shadow shrink-0">
                <div class="px-4 sm:px-6 lg:px-8 py-4 flex items-center gap-4">
                    
                    <!-- Burger Button: متاح دائماً في الموبايل -->
                    <button id="menuBtn"
                        class="md:hidden p-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Page Heading (Slot) -->
                    @isset($header)
                        <div class="font-medium text-lg text-gray-800 dark:text-white">
                            {{ $header }}
                        </div>
                    @endisset
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 md:p-6 overflow-y-auto bg-gray-50 dark:bg-gray-900">
                {{ $slot }}
            </main>

        </div>

    </div>

    <!-- Sidebar Control Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const menuBtn = document.getElementById('menuBtn');
            const closeBtn = document.getElementById('closeBtn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');

            // فتح السايدبار
            menuBtn?.addEventListener('click', () => {
                sidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
            });

            // وظيفة الإغلاق
            const closeSidebar = () => {
                sidebar.classList.add('translate-x-full');
                overlay.classList.add('hidden');
            };

            closeBtn?.addEventListener('click', closeSidebar);
            overlay?.addEventListener('click', closeSidebar);
        });
    </script>

</body>
</html>