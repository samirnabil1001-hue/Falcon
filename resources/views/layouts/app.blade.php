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
                        <div class="font-medium text-lg text-gray-800 dark:text-white">
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
    <!-- Google Angular Style Notification with Progress Bar -->
    <div x-data="{
        show: false,
        message: '',
        title: '',
        type: 'error',
        progress: 100,
        timer: null,
        startTimer() {
            this.progress = 100;
            const duration = 5000; // 5 ثواني
            const interval = 50; // تحديث كل 50 ملي ثانية
            const step = (interval / duration) * 100;
    
            if (this.timer) clearInterval(this.timer);
    
            this.timer = setInterval(() => {
                this.progress -= step;
                if (this.progress <= 0) {
                    this.show = false;
                    clearInterval(this.timer);
                }
            }, interval);
        }
    }" x-init="@if(session()->has('error'))
    title = 'Error';
    message = '{{ session('error') }}';
    type = 'error';
    show = true;
    startTimer();
    @endif
    @if(session()->has('success'))
    title = 'Success';
    message = '{{ session('success') }}';
    type = 'success';
    show = true;
    startTimer();
    @endif
    @if(session()->has('warning'))
    title = 'Warning';
    message = '{{ session('warning') }}';
    type = 'warning';
    show = true;
    startTimer();
    @endif" x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-y-10"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-10"
        class="fixed top-6 left-1/2 -translate-x-1/2 z-[9999] w-full max-w-[380px] px-4" style="display: none;">

        <div class="relative bg-white border border-gray-100 shadow-2xl rounded-lg overflow-hidden flex items-start gap-4 p-4 shadow-[0_10px_30px_rgba(0,0,0,0.08)]"
            :class="{
                'border-s-[6px] border-s-red-600': type === 'error',
                'border-s-[6px] border-s-emerald-600': type === 'success',
                'border-s-[6px] border-s-orange-500': type === 'warning'
            }">

            <!-- Icon -->
            <div class="flex-shrink-0">
                <template x-if="type === 'error'">
                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                </template>
                <template x-if="type === 'success'">
                    <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                </template>
            </div>

            <!-- Content -->
            <div class="flex-1">
                <h3 class="text-[14px] font-bold uppercase tracking-wide mb-1"
                    :class="{
                        'text-red-600': type === 'error',
                        'text-emerald-600': type === 'success',
                        'text-orange-500': type === 'warning'
                    }"
                    x-text="title"></h3>
                <p class="text-[12px] text-gray-500 font-medium leading-tight" x-text="message"></p>
            </div>

            <!-- Close -->
            <button @click="show = false" class="text-gray-300 hover:text-gray-500 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            <!-- Progress Bar -->
            <div class="absolute bottom-0 left-0 h-[3px] transition-all duration-75 ease-linear"
                :class="{
                    'bg-red-600/30': type === 'error',
                    'bg-emerald-600/30': type === 'success',
                    'bg-orange-500/30': type === 'warning'
                }"
                :style="`width: ${progress}%`
                text - align: left;">
            </div>
        </div>
    </div>
    @stack('scripts')

</body>

</html>
