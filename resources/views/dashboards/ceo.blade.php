<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex flex-col md:flex-row justify-between items-start md:items-center gap-4 py-4 border-b border-slate-100 dark:border-slate-800/60 mb-6"
            dir="rtl">

            <!-- جهة اليمين: العنوان الرئيسي -->
            <div class="flex-shrink-0">
                <h2 class="font-black text-2xl tracking-tight text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    لوحة التحكم
                    <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-md">
                        المدير التنفيذي
                    </span>
                </h2>
            </div>

            <!-- جهة اليسار: أدوات التحكم والفلترة -->
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto justify-end">

                <!-- فلتر الموظفين المنسدل الذكي والمفصول -->
                <div class="min-w-[220px] w-full sm:w-auto">
                    <x-user-filter-dropdown :users="$usersWithCustomers" />
                </div>

                <!-- زر عرض العملاء -->
                <a href="{{ route('potential-customers.index') }}"
                    class="flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-slate-700 bg-white border border-slate-200 rounded-xl dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-750 hover:text-slate-900 dark:hover:text-white hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-200 shadow-sm w-full sm:w-auto">
                    <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    عرض العملاء
                </a>
            </div>

        </div>
    </x-slot>

    <!-- Top Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6 font-sans text-right" dir="rtl">

        <!-- كرت: إجمالي العملاء -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">إجمالي العملاء</h3>
                    <p class="text-3xl font-bold text-slate-800 dark:text-white mt-1">
                        {{ number_format($totalCustomers) }}</p>
                </div>
                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-blue-500/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-50 dark:border-slate-700/50">
                <div class="flex justify-between text-[11px] text-slate-400 mb-1.5">
                    <span> {{ $opRatio }}% النسبة التشغيلية</span>
                    <span>الجدد:{{ number_format($newCount) }}</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5">
                    <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $opRatio }}%"></div>
                </div>
            </div>
        </div>

        <!-- كرت: قيد المتابعة -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">قيد المتابعة</h3>
                    <p class="text-3xl font-bold text-slate-800 dark:text-white mt-1">{{ number_format($pendingCount) }}</p>
                </div>
                <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-amber-500/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-50 dark:border-slate-700/50">
                <div class="flex justify-between text-[11px] text-slate-400 mb-1.5">
                    <span>معدل الانتظار</span>
                    <span class="font-bold text-amber-600">{{ $waitRatio }}%</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5">
                    <div class="bg-amber-500 h-1.5 rounded-full" style="width: {{ $waitRatio }}%"></div>
                </div>
            </div>
        </div>

        <!-- كرت: تم التنفيذ -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">تم التنفيذ</h3>
                    <p class="text-3xl font-bold text-slate-800 dark:text-white mt-1">{{ number_format($confirmedCount) }}</p>
                </div>
                <div class="p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-emerald-500/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-50 dark:border-slate-700/50">
                <div class="flex justify-between text-[11px] text-slate-400 mb-1.5">
                    <span>معدل الإغلاق</span>
                    <span class="font-bold text-emerald-600">{{ $closeRatio }}%</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5">
                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $closeRatio }}%"></div>
                </div>
            </div>
        </div>

        <!-- كرت: نسبة نجاح الإغلاق الكلية -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">معدل نجاح الصفقات (Win Rate)</h3>
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mt-1">{{ $winRate }}%</p>
                </div>
                <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-indigo-500/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-50 dark:border-slate-700/50">
                <div class="flex justify-between text-[11px] text-slate-400 mb-1.5">
                    <span>الملغية: {{ number_format($cancelledCount) }}</span>
                    <span class="font-bold text-rose-500">المؤكدة: {{ number_format($confirmedCount) }}</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5">
                    <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ $winRate }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="space-y-6 font-sans text-right p-4" dir="rtl">

        <!-- الصف الأول: الرسوم البيانية الدائرية الصغيرة -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
                <h3 class="text-slate-600 dark:text-slate-400 text-sm font-bold mb-4">النسبة التشغيلية ({{ $opRatio }}%)</h3>
                <div class="relative" style="height: 200px;">
                    <canvas id="chartOpRatio"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
                <h3 class="text-slate-600 dark:text-slate-400 text-sm font-bold mb-4">معدل الانتظار ({{ $waitRatio }}%)</h3>
                <div class="relative" style="height: 200px;">
                    <canvas id="chartWaitRatio"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
                <h3 class="text-slate-600 dark:text-slate-400 text-sm font-bold mb-4">تحليل مصادر العملاء</h3>
                <div class="relative" style="height: 200px;">
                    <canvas id="chartSources"></canvas>
                </div>
            </div>
        </div>

        <!-- الصف الثاني: المقارنات الأفقية والعمودية -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
                <h3 class="text-slate-600 dark:text-slate-400 text-sm font-bold mb-4">نسبة التنفيذ من الإجمالي ( {{ $closeRatio }}% )</h3>
                <div class="relative" style="height: 200px;">
                    <canvas id="chartExecutionTotal"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
                <h3 class="text-slate-600 dark:text-slate-400 text-sm font-bold mb-4">نسبة الرفض والعملاء غير المهتمين ({{ $rejectRatio }}%)</h3>
                <div class="relative" style="height: 200px;">
                    <canvas id="chartRejectRatio"></canvas>
                </div>
            </div>
        </div>

        <!-- الصف الثالث: المقارنة المركبة وجدول أفضل الموظفين -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- مخطط التوزيع النهائي -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
                <h3 class="text-slate-600 dark:text-slate-400 text-sm font-bold mb-4">تحليل النتائج النهائية (التنفيذ مقابل الرفض والمتابعة)</h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="chartFinalComparison"></canvas>
                </div>
            </div>

            <!-- جدول أفضل 5 موظفين مبيعاً -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-slate-600 dark:text-slate-400 text-sm font-bold mb-4">أفضل 5 موظفين (المبيعات المؤكدة)</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-right border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-slate-700 text-slate-400 text-xs">
                                    <th class="pb-2 font-medium">الموظف</th>
                                    <th class="pb-2 font-medium text-left">المبيعات الناجحة</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 dark:divide-slate-700/50 text-sm">
                                @forelse($topAgents as $agent)
                                    <tr class="text-slate-700 dark:text-slate-300">
                                        <td class="py-3 font-medium">{{ $agent->name }}</td>
                                        <td class="py-3 text-left font-bold text-emerald-600 dark:text-emerald-400">
                                            {{ number_format($agent->total_sales) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="py-4 text-center text-slate-400 text-xs">لا توجد بيانات مبيعات مؤكدة بعد</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // تعديل الدالة لتعمل بالتوافق مع الـ Custom Component الذي يستدعي selectUser()
    function selectUser(userId, userName) {
        const url = new URL(window.location.href);
        if (userId) {
            url.searchParams.set('user_id', userId);
        } else {
            url.searchParams.delete('user_id'); // عند اختيار "جميع الموظفين"
        }
        window.location.href = url.toString();
    }

    // دوال تصفية المكون المخصص للبحث الفازي الداخلي
    function normalizeArabic(text) {
        if (!text) return '';
        return text.trim().toLowerCase()
            .replace(/[أإآا]/g, 'ا')
            .replace(/[ةه]/g, 'ه')
            .replace(/ى/g, 'ي')
            .replace(/[\u064B-\u0652]/g, '');
    }

    function filterDropdownOptions() {
        const rawInput = document.getElementById('dropdown-search').value;
        const searchInput = normalizeArabic(rawInput);
        const options = document.querySelectorAll('.option-item');
        
        options.forEach(option => {
            const rawName = option.getAttribute('data-name');
            const normalizedName = normalizeArabic(rawName);
            
            if (normalizedName.includes(searchInput) || rawName === "") {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
    }

    function toggleDropdown() {
        const menu = document.getElementById('dropdown-menu');
        const arrow = document.getElementById('dropdown-arrow');
        menu.classList.toggle('hidden');
        if(arrow) arrow.classList.toggle('rotate-180');
        
        if (!menu.classList.contains('hidden')) {
            const searchInput = document.getElementById('dropdown-search');
            if(searchInput) searchInput.focus();
        }
    }

    document.addEventListener('click', function(event) {
        const container = document.getElementById('custom-dropdown-container');
        const menu = document.getElementById('dropdown-menu');
        const arrow = document.getElementById('dropdown-arrow');
        
        if (container && !container.contains(event.target) && menu) {
            menu.classList.add('hidden');
            if(arrow) arrow.classList.remove('rotate-180');
        }
    });

    // كود الرسوم البيانية (Charts) بدون تعديل
    document.addEventListener('DOMContentLoaded', function() {
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#94a3b8' : '#64748b';

        Chart.defaults.animation = false;

        const donutOptions = {
            cutout: '75%',
            plugins: { legend: { display: false } },
            maintainAspectRatio: false
        };

        const totalCustomers = {{ $totalCustomers }};
        const pendingCount = {{ $pendingCount }};
        const confirmedCount = {{ $confirmedCount }};
        const cancelledCount = {{ $cancelledCount }};

        const opRatio = {{ $opRatio }};
        const waitRatio = {{ $waitRatio }};
        const rejectRatio = {{ $rejectRatio }};

        const sourceLabels = {!! json_encode($sourceLabels) !!};
        const sourceData = {!! json_encode($sourceData) !!};

        // 1. نسبة التشغيل
        new Chart(document.getElementById('chartOpRatio'), {
            type: 'doughnut',
            data: {
                labels: ['تشغيل', 'غير نشط'],
                datasets: [{
                    data: [opRatio, 100 - opRatio],
                    backgroundColor: ['#3b82f6', 'rgba(226, 232, 240, 0.5)'],
                    borderWidth: 0
                }]
            },
            options: donutOptions
        });

        // 2. نسبة الانتظار
        new Chart(document.getElementById('chartWaitRatio'), {
            type: 'doughnut',
            data: {
                labels: ['انتظار', 'مكتمل'],
                datasets: [{
                    data: [waitRatio, 100 - waitRatio],
                    backgroundColor: ['#f59e0b', 'rgba(226, 232, 240, 0.5)'],
                    borderWidth: 0
                }]
            },
            options: donutOptions
        });

        // 3. مخطط مصادر العملاء
        new Chart(document.getElementById('chartSources'), {
            type: 'pie',
            data: {
                labels: sourceLabels,
                datasets: [{
                    data: sourceData,
                    backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ec4899', '#3b82f6', '#8b5cf6'],
                    borderWidth: 0
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: { boxWidth: 12, color: textColor, font: { size: 10 } }
                    }
                }
            }
        });

        // 4. نسبة التنفيذ
        new Chart(document.getElementById('chartExecutionTotal'), {
            type: 'bar',
            data: {
                labels: ['إجمالي العملاء', 'تم التنفيذ'],
                datasets: [{
                    data: [totalCustomers, confirmedCount],
                    backgroundColor: ['#e2e8f0', '#10b981'],
                    borderRadius: 10
                }]
            },
            options: {
                indexAxis: 'y',
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: textColor } },
                    y: { ticks: { color: textColor } }
                }
            }
        });

        // 5. نسبة الرفض
        new Chart(document.getElementById('chartRejectRatio'), {
            type: 'pie',
            data: {
                labels: ['رفض', 'أخرى'],
                datasets: [{
                    data: [cancelledCount, totalCustomers - cancelledCount],
                    backgroundColor: ['#f43f5e', 'rgba(226, 232, 240, 0.5)'],
                    borderWidth: 0
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        // 6. المقارنة النهائية
        new Chart(document.getElementById('chartFinalComparison'), {
            type: 'bar',
            data: {
                labels: ['توزيع الإجمالي'],
                datasets: [
                    { label: 'تم التنفيذ', data: [confirmedCount], backgroundColor: '#10b981', borderRadius: 5 },
                    { label: 'غير مهتم', data: [cancelledCount], backgroundColor: '#f43f5e', borderRadius: 5 },
                    { label: 'قيد المتابعة', data: [pendingCount], backgroundColor: '#f59e0b', borderRadius: 5 }
                ]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    x: { stacked: true, ticks: { color: textColor } },
                    y: { stacked: true, ticks: { color: textColor } }
                }
            }
        });
    });
</script>