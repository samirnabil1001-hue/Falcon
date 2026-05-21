<x-app-layout>
    <!-- Container الرئيسي مع Alpine.js لإدارة أي تأكيدات أو مودالز مستقبلاً -->
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
        }
    }"
    class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 p-5 md:p-6 h-[calc(100vh-12rem)] lg:h-[calc(100vh-7rem)] flex flex-col overflow-hidden transition-colors duration-300">

        <!-- الهيدر الخاص بالصفحة وبيجيب إجمالي العدد -->
        <x-potential-customer-services.header :totalCount="$services->total()" />

        <!-- لوحة الفلترة والبحث -->
        <x-potential-customer-services.filter-panel 
            :search="request('search')"
            :dateFrom="request('date_from')"
            :dateTo="request('date_to')"
            :serviceType="request('service_type')"
            :userId="request('user_id')"
            :sortBy="request('sort_by', 'created_at')"
            :sortOrder="request('sort_order', 'desc')" 
        />

        <!-- جدول البيانات -->
        <div class="flex-1 h-0 overflow-hidden rounded-xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
            <div class="h-full overflow-auto custom-scrollbar">
                <table class="w-full min-w-[1000px] border-collapse text-left">
                    <thead class="sticky top-0 z-20 bg-gray-50/90 dark:bg-slate-800/90 backdrop-blur-md border-b border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-300">
                        <tr>
                            <!-- اسم العميل -->
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">
                                Customer Name
                            </th>
                            <!-- رقم الهاتف -->
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">
                                Customer Phone
                            </th>
                            
                            <!-- نوع الخدمة (قابل للترتيب) -->
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'service_type', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'service_type' ? 'desc' : 'asc']) }}"
                                   class="inline-flex items-center justify-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    Service Type
                                    @if (request('sort_by') === 'service_type')
                                        <span class="text-xs">{{ request('sort_order') === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>

                            <!-- الملاحظات -->
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">
                                Notes
                            </th>
                            
                            <!-- الموظف المسؤول -->
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">
                                Assigned Employee
                            </th>
                            
                            <!-- تاريخ الإنشاء (قابل للترتيب) -->
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'created_at' ? 'desc' : 'asc']) }}"
                                   class="inline-flex items-center justify-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    Created At
                                    @if (request('sort_by', 'created_at') === 'created_at')
                                        <span class="text-xs">{{ request('sort_order', 'desc') === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                        @forelse($services as $service)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors group">
                                <!-- اسم العميل -->
                                <td class="p-4 text-center whitespace-nowrap">
                                    <span class="font-semibold text-sm text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                        {{ $service->potentialCustomer->name ?? 'N/A' }}
                                    </span>
                                </td>

                                <!-- رقم هاتف العميل -->
                                <td class="p-4 text-center whitespace-nowrap text-xs font-medium text-gray-600 dark:text-slate-300">
                                    {{ $service->potentialCustomer->phone ?? 'N/A' }}
                                </td>

                                <!-- نوع الخدمة معالجة كـ Enum لمنع أي Type Error -->
                                <td class="p-4 text-center whitespace-nowrap">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">
                                        {{ $service->service_type instanceof \App\Enums\CompanyService ? $service->service_type->label() : $service->service_type }}
                                    </span>
                                </td>

                                <!-- الملاحظات نص مختصر -->
                                <td class="p-4 text-center text-xs text-gray-600 dark:text-slate-400 max-w-xs truncate">
                                    {{ $service->notes ?? '-' }}
                                </td>

                                <!-- اسم الموظف -->
                                <td class="p-4 text-center whitespace-nowrap text-xs font-medium text-gray-600 dark:text-slate-400">
                                    {{ $service->user->name ?? 'System' }}
                                </td>

                                <!-- تاريخ الوقت والإنشاء -->
                                <td class="p-4 text-center whitespace-nowrap text-gray-500 dark:text-slate-400 text-xs">
                                    {{ $service->created_at->format('M d, Y • H:i') }}
                                </td>
                            </tr>
                        @empty
                            <!-- في حالة عدم وجود بيانات متطابقة مع الفلتر -->
                            <tr>
                                <td colspan="6" class="p-12 text-center text-gray-400 dark:text-slate-500 italic text-sm">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <svg class="w-8 h-8 text-gray-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        No customer services logging records were found matching your filters.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- الترقيم (Pagination) -->
        <div class="shrink-0 pt-4 mt-2 border-t border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 dynamic-pagination">
            {{ $services->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- استايلات مخصصة لشريط التمرير (Scrollbar) متوافقة مع الـ Dark Mode -->
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