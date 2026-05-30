
<x-app-layout>
    <div class="py-12" x-data="{ openDetailPopup: false, selectedLog: {} }">
        <div class="mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-8" dir="rtl">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-slate-200 leading-tight">
                        السجل التاريخي للمتابعات والخدمات
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">
                        الجدول الزمني الموحد لجميع إجراءات ومكالمات وخدمات العميل (الأحدث أولاً)
                    </p>
                </div>
                <a href="{{ route('potential-customers.index') }}"
                    class="px-4 py-2 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-lg text-xs font-semibold hover:bg-gray-200 transition-all">
                    عودة لقائمة العملاء
                </a>
            </div>
            
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 dark:border-slate-700 p-6 sm:p-8"
                dir="rtl">
                <div class="flex items-center mb-4">
                    <div
                        class="w-16 h-16 me-2.5 rounded-2xl bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex items-center justify-center overflow-hidden shrink-0">
                        @if (isset($customer->avatar_url) && $customer->avatar_url)
                            <img src="{{ $customer->avatar_url }}" alt="{{ $customer->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <span class="text-xl font-bold text-slate-500 dark:text-slate-300">
                                {{ mb_substr($customer->name, 0, 1) }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-800 dark:text-slate-100">
                            {{ $customer->name }}
                        </h2>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs text-slate-400">حالة العميل الحالية:</span>
                            <x-potential-customers.status-badge :status="$customer->status" />
                        </div>
                    </div>
                </div>

                @if ($sortedTimeline->isEmpty())
                    <div class="text-center py-12 text-gray-400 text-sm">
                        لا توجد أي سجلات متابعة أو خدمات مسجلة لهذا العميل حتى الآن.
                    </div>
                @else
                    <div class="relative border-r-2 border-gray-200 dark:border-slate-700 mr-2 space-y-6 pb-4">

                        @foreach ($sortedTimeline as $item)
                            <div @click="selectedLog = {{ json_encode([
                                'status' => $item['status'],
                                'status_label' => $item['status_label'],
                                'reason' => $item['reason'],
                                'notes' => $item['notes'],
                                'created_at' => $item['created_at'],
                                'next_follow_up_at' => $item['next_follow_up_at'],
                                'type' => $item['type'],
                                'user_name' => $item['user_name']
                            ]) }}; openDetailPopup = true"
                                class="relative pr-8 group cursor-pointer transition-all">

                                <div class="absolute -right-[7px] top-2 w-3.5 h-3.5 rounded-full border-2 border-white dark:border-slate-800 shadow-sm transition-all duration-300 group-hover:scale-125
                                    {{ $item['type'] === 'service' ? 'bg-emerald-600 dark:bg-emerald-500' : ($item['status'] === 'cancelled' ? 'bg-rose-600 dark:bg-rose-500' : 'bg-amber-500 dark:bg-amber-400') }}">
                                </div>

                                <div class="rounded-xl p-5 border transition-all duration-200 hover:shadow-lg
                                    {{ $item['type'] === 'service' ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-500/30' : ($item['status'] === 'cancelled' ? 'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-500/30' : 'bg-amber-50 dark:bg-amber-950/30 border-amber-200 dark:border-amber-500/20') }}">

                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-3">
                                        <div class="flex items-center gap-3">
                                            <span class="px-2.5 py-1 rounded-md text-xs font-bold text-white shadow-sm whitespace-nowrap
                                                {{ $item['type'] === 'service' ? 'bg-emerald-600 dark:bg-emerald-500' : ($item['status'] === 'cancelled' ? 'bg-rose-600 dark:bg-rose-500' : 'bg-amber-500 dark:bg-amber-600') }}">
                                                {{ $item['status_label'] }}
                                            </span>
                                            <h3 class="text-base font-bold transition-colors
                                                {{ $item['type'] === 'service' ? 'text-emerald-900 dark:text-emerald-200' : ($item['status'] === 'cancelled' ? 'text-rose-900 dark:text-rose-200' : 'text-amber-900 dark:text-amber-200') }}">
                                                {{ $item['reason'] }}
                                            </h3>
                                        </div>

                                        <div class="text-xs font-medium flex items-center gap-1
                                            {{ $item['type'] === 'service' ? 'text-emerald-600 dark:text-emerald-400/70' : ($item['status'] === 'cancelled' ? 'text-rose-600 dark:text-rose-400/70' : 'text-amber-600 dark:text-amber-400/70') }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ \Carbon\Carbon::parse($item['created_at'])->format('Y-m-d - h:i A') }}
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-1 mt-2 text-xs font-semibold opacity-75 {{ $item['type'] === 'service' ? 'text-emerald-800 dark:text-emerald-300' : ($item['status'] === 'cancelled' ? 'text-rose-800 dark:text-rose-300' : 'text-amber-800 dark:text-amber-300') }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                        <span>الموظف: {{ $item['user_name'] }}</span>
                                    </div>

                                    @if ($item['notes'] || $item['status'] === 'confirmed' || $item['type'] === 'service')
                                        <div class="text-sm p-3 rounded-lg border mt-2 bg-white/80 dark:bg-slate-900/60
                                            {{ $item['type'] === 'service' ? 'text-emerald-800 dark:text-emerald-300 border-emerald-100 dark:border-emerald-500/20' : ($item['status'] === 'cancelled' ? 'text-rose-800 dark:text-rose-300 border-rose-100 dark:border-rose-500/20' : 'text-amber-800 dark:text-amber-300 border-amber-100 dark:border-amber-500/10') }}">
                                            <span class="text-[11px] font-bold block mb-1 opacity-70">
                                                {{ $item['notes'] ? 'الملاحظات المسجلة:' : 'تفاصيل الخدمة المؤكدة:' }}
                                            </span>
                                            <p class="whitespace-pre-line">
                                                {{ $item['notes'] ?: $item['reason'] }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <template x-teleport="body">
            <div x-show="openDetailPopup" class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;" x-transition.opacity>
                <div class="flex items-center justify-center min-h-screen p-4 text-center">
                    <div @click="openDetailPopup = false" class="fixed inset-0 bg-slate-950/50 backdrop-blur-sm"></div>
                    <div class="inline-block rounded-xl text-right overflow-hidden shadow-2xl transform transition-all max-w-md w-full p-6 border relative bg-white dark:bg-slate-800" dir="rtl">
                        
                        <h4 class="text-base font-bold mb-4 pb-2 border-b dark:text-slate-100">تفاصيل السجل التاريخي</h4>

                        <div class="space-y-4 text-sm">
                            <div>
                                <span class="text-xs block mb-1 opacity-70">نوع الإجراء:</span>
                                <span class="text-xs font-bold px-3 py-1 rounded-md inline-block shadow-sm text-white" x-text="selectedLog.status_label" :class="selectedLog.type === 'service' ? 'bg-emerald-600' : (selectedLog.status === 'cancelled' ? 'bg-rose-600' : 'bg-amber-500')"></span>
                            </div>

                            <div>
                                <span class="text-xs block mb-1 opacity-70">الموظف المسؤول:</span>
                                <span class="font-bold text-gray-800 dark:text-slate-200 bg-gray-50 dark:bg-slate-700 px-3 py-1.5 rounded-lg border dark:border-slate-600" x-text="selectedLog.user_name"></span>
                            </div>

                            <div>
                                <span class="block text-xs mb-1 opacity-70">البيان الأساسي:</span>
                                <p class="font-bold p-2.5 rounded-lg border bg-gray-50 dark:bg-slate-700 dark:text-slate-200" x-text="selectedLog.reason"></p>
                            </div>

                            <div>
                                <span class="block text-xs mb-1 opacity-70">الملاحظات:</span>
                                <p class="text-xs p-2.5 rounded-lg border bg-gray-50 dark:bg-slate-700 dark:text-slate-300" x-text="selectedLog.notes || 'لا توجد ملاحظات'"></p>
                            </div>
                        </div>

                        <button type="button" @click="openDetailPopup = false" class="mt-6 px-4 py-2 w-full bg-slate-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-lg text-xs font-semibold hover:bg-gray-200">إغلاق</button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>

```