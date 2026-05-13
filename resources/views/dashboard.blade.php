<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 py-2">
            <h2 class="font-extrabold text-2xl tracking-tight text-slate-800 dark:text-slate-100">
                لوحة التحكم
            </h2>

            <div class="flex items-center gap-3">
                <!-- زر عرض العملاء -->
                <a href="#"
                    class="flex items-center px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700">
                    <svg class="w-4 h-4 ml-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    عرض العملاء
                </a>

                <!-- زر إضافة عميل جديد -->
                <button
                    class="flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-slate-800 shadow-sm rounded-xl dark:bg-indigo-600 border border-transparent">
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    إضافة عميل جديد
                </button>
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
                    <p class="text-3xl font-bold text-slate-800 dark:text-white mt-1">2,784</p>
                </div>
                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-blue-500/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-50 dark:border-slate-700/50">
                <div class="flex justify-between text-[11px] text-slate-400 mb-1.5">
                    <span>النسبة التشغيلية</span>
                    <span class="font-bold text-blue-500">89%</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5">
                    <div class="bg-blue-500 h-1.5 rounded-full" style="width: 89%"></div>
                </div>
            </div>
        </div>

        <!-- كرت: قيد المتابعة -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">قيد المتابعة</h3>
                    <p class="text-3xl font-bold text-slate-800 dark:text-white mt-1">186</p>
                </div>
                <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-amber-500/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-50 dark:border-slate-700/50">
                <div class="flex justify-between text-[11px] text-slate-400 mb-1.5">
                    <span>معدل الانتظار</span>
                    <span class="font-bold text-amber-600">7.4%</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5">
                    <div class="bg-amber-500 h-1.5 rounded-full" style="width: 7.4%"></div>
                </div>
            </div>
        </div>

        <!-- كرت: تم التنفيذ -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">تم التنفيذ</h3>
                    <p class="text-3xl font-bold text-slate-800 dark:text-white mt-1">1,965</p>
                </div>
                <div class="p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-emerald-500/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-50 dark:border-slate-700/50">
                <div class="flex justify-between text-[11px] text-slate-400 mb-1.5">
                    <span>معدل الإغلاق</span>
                    <span class="font-bold text-emerald-600">79.2%</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5">
                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 79.2%"></div>
                </div>
            </div>
        </div>

        <!-- كرت: غير مهتم -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">غير مهتم</h3>
                    <p class="text-3xl font-bold text-slate-800 dark:text-white mt-1">329</p>
                </div>
                <div class="p-3 bg-rose-50 dark:bg-rose-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-rose-500/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-50 dark:border-slate-700/50">
                <div class="flex justify-between text-[11px] text-slate-400 mb-1.5">
                    <span>نسبة الرفض</span>
                    <span class="font-bold text-rose-600">13.3%</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5">
                    <div class="bg-rose-500 h-1.5 rounded-full" style="width: 13.3%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="space-y-6 font-sans text-right p-4" dir="rtl">

        <!-- الصف الأول -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
                <h3 class="text-slate-600 dark:text-slate-400 text-sm font-bold mb-4">النسبة التشغيلية (89%)</h3>
                <div class="relative" style="height: 200px;">
                    <canvas id="chartOpRatio"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
                <h3 class="text-slate-600 dark:text-slate-400 text-sm font-bold mb-4">معدل الانتظار (7.4%)</h3>
                <div class="relative" style="height: 200px;">
                    <canvas id="chartWaitRatio"></canvas>
                </div>
            </div>
        </div>

        <!-- الصف الثاني -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
                <h3 class="text-slate-600 dark:text-slate-400 text-sm font-bold mb-4">نسبة التنفيذ من الإجمالي ( 70% )</h3>
                <div class="relative" style="height: 200px;">
                    <canvas id="chartExecutionTotal"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
                <h3 class="text-slate-600 dark:text-slate-400 text-sm font-bold mb-4">نسبة الرفض (13.3%)</h3>
                <div class="relative" style="height: 200px;">
                    <canvas id="chartRejectRatio"></canvas>
                </div>
            </div>
        </div>

        <!-- الصف الأخير -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
            <h3 class="text-slate-600 dark:text-slate-400 text-sm font-bold mb-4">تحليل النتائج النهائية (التنفيذ مقابل الرفض)</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="chartFinalComparison"></canvas>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#94a3b8' : '#64748b';

        // إيقاف جميع أنواع الأنميشن في Chart.js
        Chart.defaults.animation = false;

        const donutOptions = {
            cutout: '75%',
            plugins: {
                legend: { display: false }
            },
            maintainAspectRatio: false
        };

        // 1. نسبة التشغيل
        new Chart(document.getElementById('chartOpRatio'), {
            type: 'doughnut',
            data: {
                labels: ['تشغيل', 'غير نشط'],
                datasets: [{
                    data: [89, 11],
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
                    data: [7.4, 92.6],
                    backgroundColor: ['#f59e0b', 'rgba(226, 232, 240, 0.5)'],
                    borderWidth: 0
                }]
            },
            options: donutOptions
        });

        // 3. نسبة التنفيذ
        new Chart(document.getElementById('chartExecutionTotal'), {
            type: 'bar',
            data: {
                labels: ['إجمالي العملاء', 'تم التنفيذ'],
                datasets: [{
                    data: [2784, 1965],
                    backgroundColor: ['#e2e8f0', '#10b981'],
                    borderRadius: 10
                }]
            },
            options: {
                indexAxis: 'y',
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // 4. نسبة الرفض
        new Chart(document.getElementById('chartRejectRatio'), {
            type: 'pie',
            data: {
                labels: ['رفض', 'أخرى'],
                datasets: [{
                    data: [329, 2455],
                    backgroundColor: ['#f43f5e', 'rgba(226, 232, 240, 0.5)'],
                    borderWidth: 0
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // 5. المقارنة النهائية
        new Chart(document.getElementById('chartFinalComparison'), {
            type: 'bar',
            data: {
                labels: ['توزيع الإجمالي'],
                datasets: [
                    { label: 'تم التنفيذ', data: [1965], backgroundColor: '#10b981', borderRadius: 5 },
                    { label: 'غير مهتم', data: [329], backgroundColor: '#f43f5e', borderRadius: 5 },
                    { label: 'قيد المتابعة', data: [186], backgroundColor: '#f59e0b', borderRadius: 5 }
                ]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    x: { stacked: true, ticks: { color: textColor } },
                    y: { stacked: true, ticks: { color: textColor } }
                },
                plugins: {
                    legend: { position: 'bottom', labels: { color: textColor } }
                }
            }
        });
    });
</script>