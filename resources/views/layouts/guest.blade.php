<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-100 dark:bg-gray-950">
    
    <!-- تم تعديل الشرط هنا ليشمل صفحات التحقق من الإيميل وتغيير كلمة المرور لتظهر داخل البطاقة وفي المنتصف -->
    @if(request()->routeIs('login') || request()->routeIs('register') || request()->routeIs('password.*') || request()->routeIs('verification.*'))
        <!-- 1. تصميم البطاقة المخصص لصفحات الدخول، التسجيل، التحقق، واستعادة كلمة المرور -->
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900 px-4">
            <div class="mb-2">
                <a href="/">
                    <x-application-logo class="w-16 h-16 fill-current text-indigo-600 dark:text-indigo-400" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-4 px-8 py-8 bg-white dark:bg-gray-800 shadow-xl border border-gray-100 dark:border-gray-700/50 overflow-hidden sm:rounded-2xl transition-all">
                {{ $slot }}
            </div>
        </div>
    @else
        <!-- 2. تصميم حر طليق بدون أي قيود مخصص للـ Landing Page -->
        <div class="min-h-screen">
            {{ $slot }}
        </div>
    @endif

    <x-toaster />
</body>

</html>