<!DOCTYPE html>
<html lang="ar" dir="rtl"> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'نظام تسجيل الأنشطة' }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased">

    <nav class="bg-white shadow mb-8 p-4">
        <div class="container mx-auto font-bold text-lg">Activity Logger</div>
    </nav>

    <main>
        {{ $slot }}
    </main>

</body>
</html>