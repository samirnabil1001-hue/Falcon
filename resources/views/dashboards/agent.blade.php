<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 py-2">
            <h2 class="font-extrabold text-2xl tracking-tight text-slate-800 dark:text-slate-100">
                لوحة التحكم (متابعة الأداء الشخصي)
            </h2>

            <div class="flex items-center gap-3">
                <!-- زر عرض العملاء -->
                <a href="{{ route('potential-customers.index') }}"
                    class="flex items-center px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 shadow-none">
                    <svg class="w-4 h-4 ml-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    عرض العملاء
                </a>

                <!-- زر إضافة عميل جديد -->
                <a href="{{ route('potential-customers.create') }}"
                    class="flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-slate-800 rounded-xl dark:bg-indigo-600 border border-transparent shadow-none">
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    إضافة عميل جديد
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Top Cards (تم الإبقاء عليها تماماً كما هي) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6 font-sans text-right" dir="rtl">

        <!-- كرت: إجمالي العملاء -->
        <div
            class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-none">
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
                    <span>النسبة التشغيلية</span>
                    <span class="font-bold text-blue-500">{{ $opRatio }}%</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5">
                    <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $opRatio }}%"></div>
                </div>
            </div>
        </div>

        <!-- كرت: قيد المتابعة -->
        <div
            class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-none">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">قيد المتابعة</h3>
                    <p class="text-3xl font-bold text-slate-800 dark:text-white mt-1">{{ number_format($pendingCount) }}
                    </p>
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
        <div
            class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-none">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">تم التنفيذ</h3>
                    <p class="text-3xl font-bold text-slate-800 dark:text-white mt-1">
                        {{ number_format($confirmedCount) }}</p>
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

        <!-- كرت: غير مهتم -->
        <div
            class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-none">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h3 class="text-slate-500 dark:text-slate-400 text-sm font-medium">غير مهتم</h3>
                    <p class="text-3xl font-bold text-slate-800 dark:text-white mt-1">
                        {{ number_format($cancelledCount) }}</p>
                </div>
                <div class="p-3 bg-rose-50 dark:bg-rose-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-rose-500/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-50 dark:border-slate-700/50">
                <div class="flex justify-between text-[11px] text-slate-400 mb-1.5">
                    <span>نسبة الرفض</span>
                    <span class="font-bold text-rose-600">{{ $rejectRatio }}%</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5">
                    <div class="bg-rose-500 h-1.5 rounded-full" style="width: {{ $rejectRatio }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content (جدول التقارير والمتابعات والمصادر) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 font-sans text-right p-1" dir="rtl">

        <!-- العمود الأيمن (2/3): جدول المتابعات الفورية والعاجلة للـ Agent -->
        <div class="lg:col-span-2 space-y-6">
            <x-urgent-customers-table :recentUrgentCustomers="$recentUrgentCustomers" />
        </div>

        <!-- العمود الأيسر (1/3): إحصائيات الأداء والمصادر المربحة للموظف -->
        <div class="space-y-6">
            <!-- كرت كفاءة الإغلاق الفردي الدائري -->
            <div
                class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-none">
                <h3 class="text-slate-700 dark:text-slate-200 text-sm font-bold mb-1">🎯 كفاءة الحسم والإغلاق</h3>
                <p class="text-[11px] text-slate-400 mb-4">تحليل الصفقات الناجحة (تم التنفيذ) منسوبة لعملائك</p>

                <div class="relative flex items-center justify-center mb-2" style="height: 140px;">
                    <canvas id="agentCloseRateChart"></canvas>
                    <div class="absolute text-center">
                        <span
                            class="text-2xl font-extrabold text-slate-700 dark:text-white">{{ $closeRatio }}%</span>
                        <p class="text-[10px] text-slate-400">معدل الإغلاق</p>
                    </div>
                </div>
            </div>

            <!-- تقرير إنتاجية مصادر الجلب الخاصة بالموظف نفسه -->
            <div
                class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-none">
                <h3 class="text-slate-700 dark:text-slate-200 text-sm font-bold mb-1">📊 قنوات الجلب الخاصة بك</h3>
                <p class="text-[11px] text-slate-400 mb-3">توزيع إجمالي حجم عملائك الحاليين بحسب مصادرهم</p>

                <div class="relative" style="height: 150px;">
                    @if (count($sourceLabels) > 0)
                        <canvas id="agentSourceChart"></canvas>
                    @else
                        <div class="flex items-center justify-center h-full text-slate-400 text-xs">
                            لا توجد بيانات مصادر مسجلة لك بعد
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#94a3b8' : '#64748b';

        // إيقاف الأنميشن لاستقرار تام وسرعة استجابة فائقة
        Chart.defaults.animation = false;

        // 1. مؤشر كفاءة الإغلاق الدائري للـ Agent (Donut Chart)
        const closeRatio = {{ $closeRatio }};
        new Chart(document.getElementById('agentCloseRateChart'), {
            type: 'doughnut',
            data: {
                labels: ['تم التنفيذ', 'متبقي للهدف'],
                datasets: [{
                    data: [closeRatio, 100 - closeRatio],
                    backgroundColor: ['#10b981', 'rgba(241, 245, 249, 0.9)'],
                    hoverBackgroundColor: ['#10b981', 'rgba(241, 245, 249, 0.9)'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '80%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                maintainAspectRatio: false
            }
        });

        // 2. الرسم البياني للمصادر (Horizontal Bar Chart)
        @if (count($sourceLabels) > 0)
            new Chart(document.getElementById('agentSourceChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($sourceLabels) !!},
                    datasets: [{
                        data: {!! json_encode($sourceData) !!},
                        backgroundColor: 'rgba(99, 102, 241, 0.75)',
                        borderRadius: 6,
                        barThickness: 12
                    }]
                },
                options: {
                    indexAxis: 'y',
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: textColor,
                                stepSize: 1,
                                precision: 0
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: textColor
                            }
                        }
                    }
                }
            });
        @endif
    });
</script>