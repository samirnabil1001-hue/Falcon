<x-app-layout>
    <!-- Container الرئيسي -->
    <div
        class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 p-5 md:p-6 h-[calc(100vh-12rem)] lg:h-[calc(100vh-7rem)] flex flex-col overflow-hidden transition-colors duration-300">

        <!-- الهيدر الخاص بالصفحة -->
        <x-potential-customer-services.header :totalCount="$services->total()" />

        <!-- لوحة الفلترة والبحث -->
        <x-potential-customer-services.filter-panel :users="$users" :search="request('search')" :dateFrom="request('date_from')"
            :dateTo="request('date_to')" :serviceType="request('service_type')" :userId="request('user_id')" :sortBy="request('sort_by', 'created_at')" :sortOrder="request('sort_order', 'desc')" />
        <!-- جدول البيانات -->
        <div
            class="flex-1 h-0 overflow-hidden rounded-xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
            <div class="h-full overflow-auto custom-scrollbar">
                <table class="w-full min-w-[1000px] border-collapse text-left">
                    <thead
                        class="sticky top-0 z-20 bg-gray-50/90 dark:bg-slate-800/90 backdrop-blur-md border-b border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-300">
                        <tr>
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">Customer Name</th>
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">Customer Phone
                            </th>
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">Requests Count
                            </th> <!-- العمود الجديد -->
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'service_type', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'service_type' ? 'desc' : 'asc']) }}"
                                    class="inline-flex items-center justify-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    Service Type
                                    @if (request('sort_by') === 'service_type')
                                        <span class="text-xs">{{ request('sort_order') === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">Notes</th>
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">Assigned Employee
                            </th>
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'created_at' ? 'desc' : 'asc']) }}"
                                    class="inline-flex items-center justify-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    Created At
                                    @if (request('sort_by', 'created_at') === 'created_at')
                                        <span
                                            class="text-xs">{{ request('sort_order', 'desc') === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                        @forelse($services as $service)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors group">
                                <!-- اسم العميل -->
                                <td class="p-4 text-center whitespace-nowrap">
                                    <span
                                        class="font-semibold text-sm text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                        {{ $service->potentialCustomer->name ?? 'N/A' }}
                                    </span>
                                </td>

                                <!-- رقم هاتف العميل -->
                                <td
                                    class="p-4 text-center whitespace-nowrap text-xs font-medium text-gray-600 dark:text-slate-300">
                                    {{ $service->potentialCustomer->phone ?? 'N/A' }}
                                </td>

                                <!-- عدد مرات طلب الخدمة (إجمالي الطلبات للعميل) -->
                                <td class="p-4 text-center whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold leading-none text-emerald-700 bg-emerald-50 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-full">
                                        {{ $service->potentialCustomer ? $service->potentialCustomer->services()->count() : 0 }}
                                        طلبات
                                    </span>
                                </td>

                                <!-- نوع الخدمة الأخيرة -->
                                <td class="p-4 text-center whitespace-nowrap">
                                    <span
                                        class="px-2.5 py-1 text-xs font-semibold rounded-md bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">
                                        {{ $service->service_type instanceof \App\Enums\CompanyService ? $service->service_type->label() : $service->service_type }}
                                    </span>
                                </td>

                                <!-- الملاحظات -->
                                <td class="p-4 text-center text-xs text-gray-600 dark:text-slate-400 max-w-xs truncate"
                                    dir="rtl">
                                    @if ($service->notes)
                                        {{ $service->notes }}
                                    @else
                                        <span class="text-gray-400 dark:text-slate-500 font-medium">نوع الخدمة المعتمدة:
                                            تنفيذ إجراء جديد</span>
                                    @endif
                                </td>

                                <!-- اسم الموظف -->
                                <td
                                    class="p-4 text-center whitespace-nowrap text-xs font-medium text-gray-600 dark:text-slate-400">
                                    {{ $service->user->name ?? 'System' }}
                                </td>

                                <!-- تاريخ الإنشاء -->
                                <td class="p-4 text-center whitespace-nowrap text-gray-500 dark:text-slate-400 text-xs">
                                    {{ $service->created_at->format('M d, Y • H:i') }}
                                </td>


                                <td class="p-4 text-center whitespace-nowrap align-middle" x-data="{ showConfirmedModal: false }">
                                    @php
                                        $currentCustomer = $service->potentialCustomer;
                                        // فحص ما إذا كان المستخدم الحالي هو صاحب السجل
                                        $isCreator = auth()->id() === $service->user_id;
                                    @endphp

                                    @if ($currentCustomer)
                                        <!-- تم تعديل الحاوية لتصبح flex-row-reverse من أجل ترتيب الأزرار بشكل صحيح في الـ RTL مع gap مناسب -->
                                        <div
                                            class="flex flex-row-reverse items-center justify-center gap-2 max-w-[240px] mx-auto">
                                            <a href="{{ route('customer-follow-ups.show', $currentCustomer->id) }}"
                                                class="px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-600 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-300 text-xs font-semibold rounded-lg transition-all flex items-center justify-center gap-1.5 border border-gray-200 dark:border-slate-700">
                                                <svg class="w-3.5 h-3.5 text-gray-400 dark:text-slate-500"
                                                    fill="none" stroke="currentColor" stroke-width="2"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                السجل
                                            </a>
                                            @if ($isCreator)
                                                <button @click="showConfirmedModal = true" type="button"
                                                    class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all flex items-center justify-center gap-1.5 shadow-sm bg-indigo-600 hover:bg-indigo-700 text-white shadow-indigo-200 dark:shadow-none cursor-pointer">
                                                    <svg class="w-3.5 h-3.5 text-white" fill="none"
                                                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    متابعة
                                                </button>
                                            @else
                                                <button type="button" disabled
                                                    title="لا تملك صلاحية المتابعة لهذا السجل"
                                                    class="px-3 py-1.5 text-xs font-semibold rounded-lg flex items-center justify-center gap-1.5 bg-gray-100 text-gray-400 dark:bg-slate-800 dark:text-slate-500 cursor-not-allowed opacity-75">
                                                    <svg class="w-3.5 h-3.5 text-gray-400 dark:text-slate-500"
                                                        fill="none" stroke="currentColor" stroke-width="2"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    متابعة
                                                </button>
                                            @endif



                                        </div>

                                        <!-- مودال المتابعة المنبثق -->
                                        @if ($isCreator)
                                            <div x-show="showConfirmedModal" x-cloak
                                                @close-modal.window="showConfirmedModal = false">
                                                <x-potential-customers.confirmed-modal :route="route(
                                                    'potential-customers.update-status',
                                                    $currentCustomer->id,
                                                )" />
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400 italic">No Customer</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8"
                                    class="p-12 text-center text-gray-400 dark:text-slate-500 italic text-sm">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <svg class="w-8 h-8 text-gray-300 dark:text-slate-700" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
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
        <div
            class="shrink-0 pt-4 mt-2 border-t border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 dynamic-pagination">
            {{ $services->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- استايلات مخصصة لشريط التمرير (Scrollbar) -->
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
            border-radius: 8px;
        }
    </style>
</x-app-layout>
