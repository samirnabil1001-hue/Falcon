<x-guest-layout>
    <div class="bg-zinc-50 dark:bg-[#0b0f19] min-h-screen font-sans selection:bg-slate-700 selection:text-white transition-colors duration-300">
        
        <!-- 1. شريط التنقل العلوي (Navbar) -->
        <nav class="border-b border-zinc-200/60 dark:border-slate-800/60 bg-white/80 dark:bg-[#0b0f19]/80 backdrop-blur-md sticky top-0 z-50 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <!-- الشعار اسم النظام (Navy Style) -->
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-slate-800 dark:bg-slate-700 rounded-xl text-white shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-slate-800 dark:text-slate-200">
                            CRM Pro
                        </span>
                    </div>

                    <!-- أزرار التحكم السريعة في الـ Navbar -->
                    <div class="flex items-center gap-4">
                        @guest
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-zinc-600 dark:text-zinc-400 hover:text-slate-800 dark:hover:text-slate-200 transition">
                                تسجيل الدخول
                            </a>
                            <a href="{{ route('register') }}" class="hidden sm:inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 rounded-xl shadow-sm transition">
                                ابدأ مجاناً
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 rounded-xl shadow-sm transition">
                                لوحة التحكم
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <!-- 2. قسم البطل الرئيسي (Hero Section) -->
        <header class="relative overflow-hidden py-20 lg:py-32">
            <!-- توهج خلفي بلون كحلي مطفي جداً غير فاقع -->
            <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
                <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-slate-200 to-zinc-300 dark:from-slate-900/40 dark:to-zinc-800/30 opacity-25 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <!-- شارة الترحيب باللون الزيتي/الرمادي الهادئ -->
                @auth
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 mb-6 bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                            مرحباً بعودتك، {{ Auth::user()->name }}
                        </span>
                    </div>
                @endauth

                <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100 sm:text-6xl max-w-4xl mx-auto leading-tight sm:leading-none">
                    أدِر عملاءك المحتملين <br class="hidden sm:inline">
                    <span class="text-slate-700 dark:text-slate-300">
                        وضاعف مبيعات فريقك
                    </span>
                </h1>
                
                <p class="mt-6 text-lg sm:text-xl text-zinc-500 dark:text-zinc-400 max-w-2xl mx-auto leading-relaxed">
                    المنصة المتكاملة لمتابعة العملاء المحتملين، تنظيم الخدمات، وإدارة وتطوير عمليات الفريق بكل سهولة وسلاسة ومن مكان واحد.
                </p>

                <!-- أزرار تفاعلية بلون كحلي هادئ جداً -->
                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    @guest
                    <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-4 bg-white dark:bg-[#121826] border border-zinc-200 dark:border-slate-800 text-zinc-700 dark:text-zinc-300 font-semibold rounded-xl hover:bg-zinc-50 dark:hover:bg-slate-800/40 shadow-sm transition duration-200 text-base">
                        تسجيل الدخول
                    </a>
                        <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-4 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-bold rounded-xl shadow-md transition duration-200 group text-base">
                            ابدأ رحلتك الآن
                            <svg class="w-5 h-5 mx-2  " fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" ></path>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-4 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-bold rounded-xl shadow-md transition duration-200 text-base">
                            الانتقال إلى لوحة التحكم
                        </a>
                    @endguest
                </div>
            </div>
        </header>

        <!-- 3. قسم المميزات والأدوات (Features Section) -->
        <section class="py-16 sm:py-24 bg-white dark:bg-[#121826]/40 border-t border-zinc-200/60 dark:border-slate-800/60 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100 sm:text-4xl">
                        كل ما تحتاجه لتنظيم وإدارة مبيعاتك
                    </h2>
                    <p class="mt-4 text-zinc-500 dark:text-zinc-400">
                        صممنا الأدوات لمساعدتك على التركيز على إغلاق الصفقات، بدلاً من تشتيت نفسك في جداول البيانات اليدوية المعقدة.
                    </p>
                </div>

                <!-- شبكة المميزات بألوان مطفية هادئة مريحة للعين -->
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    
                    <!-- الميزة الأولى -->
                    <div class="p-8 bg-zinc-50 dark:bg-[#121826] rounded-2xl border border-zinc-200/50 dark:border-slate-800/50 hover:border-slate-400 dark:hover:border-slate-600 transition duration-300">
                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 rounded-xl flex items-center justify-center mb-5 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-2">تتبع العملاء المحتملين</h3>
                        <p class="text-zinc-500 dark:text-zinc-400 text-sm leading-relaxed">
                            سجل بيانات عملائك وتابع كل مرحلة من مراحل اهتمامهم بخدماتك بشكل لحظي ومنظم وبنقرة زر.
                        </p>
                    </div>

                    <!-- الميزة الثانية -->
                    <div class="p-8 bg-zinc-50 dark:bg-[#121826] rounded-2xl border border-zinc-200/50 dark:border-slate-800/50 hover:border-slate-400 dark:hover:border-slate-600 transition duration-300">
                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 rounded-xl flex items-center justify-center mb-5 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-2">جدولة المتابعات الحية</h3>
                        <p class="text-zinc-500 dark:text-zinc-400 text-sm leading-relaxed">
                            احتفظ بسجلات المتابعة والاتصالات السابقة لضمان عدم نسيان أي عميل مهتم بالخدمة.
                        </p>
                    </div>

                    <!-- الميزة الثالثة -->
                    <div class="p-8 bg-zinc-50 dark:bg-[#121826] rounded-2xl border border-zinc-200/50 dark:border-slate-800/50 hover:border-slate-400 dark:hover:border-slate-600 transition duration-300">
                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 rounded-xl flex items-center justify-center mb-5 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-2">حماية وتحكم كامل بالأدوار</h3>
                        <p class="text-zinc-500 dark:text-zinc-400 text-sm leading-relaxed">
                            نظام أمان عالي يضمن حماية بيانات العملاء وحصر الصلاحيات للمستخدمين المعتمدين فقط.
                        </p>
                    </div>

                </div>
            </div>
        </section>

    </div>
</x-guest-layout>