<x-app-layout>
    <!-- المكون الأب الرئيسي: تم تطبيق نفس خصائص الارتفاع المتجاوب والتمرير الخاصة بالمرجع -->
    <div x-data="{
        confirmModal: false,
        modalTitle: '',
        modalMessage: '',
        formToSubmit: null,
        confirmColor: 'bg-indigo-600',
        pendingStatusValue: null,
        openConfirm(title, message, formId, color = 'bg-indigo-600') {
            this.modalTitle = title;
            this.modalMessage = message;
            this.formToSubmit = formId;
            this.confirmColor = color;
            this.confirmModal = true;
        },
        submitPendingForm() {
            let form = document.getElementById(this.formToSubmit);
            form.submit();
        }
    }"
        class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 p-5 md:p-6 h-[calc(100vh-12rem)] lg:h-[calc(100vh-7rem)] flex flex-col overflow-hidden transition-colors duration-300 text-right"
        dir="rtl">

        <!-- الهيدر -->
        <x-follow-ups.header :totalCount="$customers->total()" />
        <x-follow-ups.status-cards :statusCounts="$statusCounts" />


        <!-- رسائل التنبيه والخطأ -->
        <x-follow-ups.alert-messages :success="session('success')" :error="$errors->first('error')" />

        <!-- لوحة الفلاتر -->
        <x-follow-ups.filter-panel :search="request('search')" :status="request('status')" :sortBy="request('sort_by', 'created_at')" :sortOrder="request('sort_order', 'desc')"
            :users="$users" />

        <!-- الحاوية المرنة للجدول لضمان عدم وجود اسكرول مزدوج بالصفحة -->
        <div
            class="flex-1 h-0 overflow-hidden rounded-xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm mt-4">
            <div class="h-full overflow-auto custom-scrollbar">
                <table class="w-full min-w-[1000px] border-collapse text-right">
                    <!-- الهيدر الثابت أثناء التمرير العلوى والسفلي -->
                    <thead
                        class="sticky top-0 z-20 bg-gray-50/90 dark:bg-slate-800/90 backdrop-blur-md border-b border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-300 text-xs uppercase">
                        <tr>
                            <th scope="col" class="p-4 font-bold tracking-wider text-right">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => request('sort_by') === 'name' && request('sort_order') === 'asc' ? 'desc' : 'asc']) }}"
                                    class="inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    العميل
                                    @if (request('sort_by') === 'name')
                                        <span class="text-xs">{{ request('sort_order') === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="p-4 font-bold tracking-wider text-left" dir="ltr">رقم الهاتف
                            </th>
                            <th scope="col" class="p-4 font-bold tracking-wider text-center">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_order' => request()->status === 'status' && request('sort_order') === 'asc' ? 'desc' : 'asc']) }}"
                                    class="inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    الحالة الحالية
                                    @if (request('sort_by') === 'status')
                                        <span class="text-xs">{{ request('sort_order') === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="p-4 font-bold tracking-wider text-center">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'follow_ups_count', 'sort_order' => request('sort_by') === 'follow_ups_count' && request('sort_order') === 'asc' ? 'desc' : 'asc']) }}"
                                    class="inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    إجمالي المتابعات
                                    @if (request('sort_by') === 'follow_ups_count')
                                        <span class="text-xs">{{ request('sort_order') === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="p-4 font-bold tracking-wider text-center">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_by') === 'created_at' && request('sort_order') === 'asc' ? 'desc' : 'asc']) }}"
                                    class="inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    آخر إجراء مسجل
                                    @if (request('sort_by', 'created_at') === 'created_at')
                                        <span
                                            class="text-xs">{{ request('sort_order', 'desc') === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="p-4 font-bold tracking-wider text-center w-48 min-w-[190px]">
                                إجراءات</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                        @forelse($customers as $customer)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors group">

                                <!-- اسم العميل -->
                                <td class="p-4 whitespace-nowrap text-right">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm border border-indigo-100/50 dark:border-indigo-900/30">
                                            {{ mb_substr($customer->name, 0, 1) }}
                                        </div>
                                        <span
                                            class="font-semibold text-sm text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $customer->name }}</span>
                                    </div>
                                </td>

                                <!-- رقم الهاتف -->
                                <td class="p-4 whitespace-nowrap text-left text-xs font-medium text-gray-600 dark:text-slate-300 font-mono tracking-wide"
                                    dir="ltr">
                                    {{ $customer->phone }}
                                </td>

                                <!-- الحالة الحالية -->
                                <td class="p-4 text-center whitespace-nowrap">
                                    <x-follow-ups.status-badge :status="$customer->status" />
                                </td>

                                <!-- عدد المتابعات التراكمي -->
                                <td class="p-4 text-center whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold bg-gray-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 rounded-lg border border-gray-200 dark:border-slate-700">
                                        {{ $customer->follow_ups_count }} إجراءات
                                    </span>
                                </td>

                                <!-- تفاصيل آخر خطوة -->
                                <td class="p-4 text-center text-xs max-w-xs truncate">
                                    @if ($customer->followUps->first())
                                        <div class="text-gray-700 dark:text-slate-300 font-medium truncate">
                                            {{ $customer->followUps->first()->reason ?? 'بدون سبب معين' }}
                                        </div>
                                        <div
                                            class="text-[10px] text-gray-400 dark:text-slate-500 mt-1 flex items-center justify-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $customer->followUps->first()->created_at?->diffForHumans() }}
                                        </div>
                                    @else
                                        <span class="text-gray-300 dark:text-slate-600 italic font-normal">لا توجد
                                            متابعات سابقة</span>
                                    @endif
                                </td>

                                <!-- الإجراءات -->
                                <td class="p-4 text-center whitespace-nowrap align-middle" x-data="{ showModal: false }">
                                    @php
                                        $isContacted =
                                            $customer->status === \App\Enums\PotentialCustomerStatus::CONTACTED ||
                                            $customer->status === 'contacted' ||
                                            (is_object($customer->status) && $customer->status->value === 'contacted');
                                    @endphp

                                    <div class="grid grid-cols-2 items-center gap-2 max-w-[200px] mx-auto">

                                        <!-- زر إجراء متابعة -->
                                        <button @click="showModal = true"
                                            @if (!$isContacted) disabled @endif
                                            class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all flex items-center justify-center gap-1.5 shadow-sm 
                                            {{ $isContacted
                                                ? 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-indigo-200 dark:shadow-none cursor-pointer'
                                                : 'bg-gray-100 text-gray-400 dark:bg-slate-800 dark:text-slate-600 cursor-not-allowed shadow-none' }}">
                                            <svg class="w-3.5 h-3.5 {{ $isContacted ? 'text-white' : 'text-gray-300 dark:text-slate-700' }}"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                            متابعة
                                        </button>

                                        <!-- زر عرض السجل -->
                                        <a href="{{ route('customer-follow-ups.show', $customer->id) }}"
                                            class="px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-600 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-300 text-xs font-semibold rounded-lg transition-all flex items-center justify-center gap-1.5 border border-gray-200 dark:border-slate-700">
                                            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-slate-500" fill="none"
                                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            السجل
                                        </a>
                                    </div>

                                    <!-- استدعاء المودال المتوافق مع الكود المرجعي -->
                                    @if ($isContacted)
                                        <x-potential-customers.contacted-modal :customer="$customer" />
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="p-12 text-center text-gray-400 dark:text-slate-500 italic text-sm">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <svg class="w-8 h-8 text-gray-300 dark:text-slate-700" fill="none"
                                            stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        لا يوجد أي عملاء محتملين مسجلين في النظام حالياً.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- أزرار التنقل Pagination المثبتة بالأسفل تماماً مثل المرجع -->
        <div
            class="shrink-0 pt-4 mt-2 border-t border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 dynamic-pagination">
            {{ $customers->appends(request()->query())->links() }}
        </div>

        <!-- مودال التأكيد -->
        <x-confirmation-modal />
    </div>

    <!-- ستايل الاسكرول بار المخصص والـ Cloak المتطابق مع المرجع -->
    <style>
        [x-cloak] {
            display: none !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
        }
    </style>
</x-app-layout>
