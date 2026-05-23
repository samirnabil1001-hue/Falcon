<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Falcon') }}</title>

    <!-- Fonts & Scripts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 overflow-hidden h-screen">

    <!-- Navbar -->
    @include('layouts.navigation')

    <div class="flex h-screen overflow-hidden">

        <!-- Mobile Overlay -->
        <div id="overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>

        <!-- Sidebar Component -->
        <x-sidebar />

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden text-right">

            <!-- Page Header -->
            <header class="bg-white dark:bg-gray-800 shadow shrink-0">
                <div class="px-4 sm:px-6 lg:px-8 py-4 flex items-center gap-4">

                    <button id="menuBtn"
                        class="md:hidden p-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    @isset($header)
                        <div class="font-medium text-lg text-gray-800 dark:text-white w-full">
                            {{ $header }}
                        </div>
                    @endisset
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 p-4 md:p-6 overflow-y-auto bg-gray-50 dark:bg-gray-900">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- استدعاء كومبوننت التوستر الجديد -->
    <x-toaster />

    @stack('scripts')

</body>

</html>