<x-app-layout>
    <!-- المكون الأب الرئيسي: أضفنا إليه مستمع الحدث @change-status.window -->
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
        handleStatusChange(event, formId) {
            let select = event.target;
            let originalValue = select.getAttribute('data-original-value');
    
            if (select.value === originalValue) {
                return;
            }
    
            this.pendingStatusValue = select.value;
            let statusLabel = select.options[select.selectedIndex].text;
    
            this.openConfirm(
                'تغيير حالة العميل',
                `هل أنت متأكد من تغيير حالة هذا العميل إلى (${statusLabel})؟`,
                formId,
                'bg-amber-600'
            );
    
            select.value = originalValue;
        },
        submitPendingForm() {
            let form = document.getElementById(this.formToSubmit);
            let select = form.querySelector('select[name=\'status\']');
            select.value = this.pendingStatusValue;
            form.submit();
        }
    }"
    @change-status.window="handleStatusChange($event.detail.event, $event.detail.formId)"
    class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 p-5 md:p-6 h-[calc(100vh-12rem)] lg:h-[calc(100vh-7rem)] flex flex-col overflow-hidden transition-colors duration-300">

        <x-potential-customers.header :totalCount="$customers->total()" />

        <x-potential-customers.filter-panel 
            :search="request('search')"
            :dateFrom="request('date_from')"
            :dateTo="request('date_to')"
            :source="request('source')"
            :status="request('status')"
            :sortBy="request('sort_by', 'added_at')"
            :sortOrder="request('sort_order', 'desc')" 
        />

        <div class="flex-1 h-0 overflow-hidden rounded-xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
            <div class="h-full overflow-auto custom-scrollbar">
                <table class="w-full min-w-[1000px] border-collapse text-left">
                    <thead class="sticky top-0 z-20 bg-gray-50/90 dark:bg-slate-800/90 backdrop-blur-md border-b border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-300">
                        <tr>
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'name' ? 'desc' : 'asc']) }}"
                                    class="inline-flex items-center justify-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    Customer Name
                                    @if (request('sort_by') === 'name')
                                        <span class="text-xs">{{ request('sort_order') === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">Phone</th>
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'source', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'source' ? 'desc' : 'asc']) }}"
                                    class="inline-flex items-center justify-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    Source
                                    @if (request('sort_by') === 'source')
                                        <span class="text-xs">{{ request('sort_order') === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'status' ? 'desc' : 'asc']) }}"
                                    class="inline-flex items-center justify-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    Status
                                    @if (request('sort_by') === 'status')
                                        <span class="text-xs">{{ request('sort_order') === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>
                            @if (auth()->user()->isCEO())
                                <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">Added By</th>
                            @endif
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'added_at', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'added_at' ? 'desc' : 'asc']) }}"
                                    class="inline-flex items-center justify-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    Added At
                                    @if (request('sort_by', 'added_at') === 'added_at')
                                        <span class="text-xs">{{ request('sort_order', 'desc') === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider w-48 min-w-[190px]">
                                Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                        @forelse($customers as $customer)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors group">
                                <td class="p-4 text-center whitespace-nowrap">
                                    <span class="font-semibold text-sm text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $customer->name }}</span>
                                </td>

                                <td class="p-4 text-center whitespace-nowrap text-xs font-medium text-gray-600 dark:text-slate-300">
                                    {{ $customer->phone }}
                                </td>

                                <td class="p-4 text-center whitespace-nowrap">
                                    <x-potential-customers.source-badge :source="$customer->source" />
                                </td>

                                <td class="p-4 text-center whitespace-nowrap">
                                    <x-potential-customers.status-badge :status="$customer->status" />
                                </td>

                                @if (auth()->user()->isCEO())
                                    <td class="p-4 text-center whitespace-nowrap text-xs font-medium text-gray-600 dark:text-slate-400">
                                        {{ $customer->creator->name ?? 'System' }}
                                    </td>
                                @endif

                                <td class="p-4 text-center whitespace-nowrap text-gray-500 dark:text-slate-400 text-xs">
                                    {{ \Carbon\Carbon::parse($customer->added_at)->format('M d, Y • H:i') }}
                                </td>

                                <td class="p-4 text-center whitespace-nowrap align-middle w-56 min-w-[220px]"
                                    x-data="{
                                        showModal: false,
                                        showCancelledModal: false,
                                        currentStatus: '{{ is_object($customer->status) ? $customer->status->value : $customer->status }}',
                                        showCurrentLabel(e) {
                                            let opt = e.target.querySelector('option[disabled]:checked');
                                            if (opt && !opt.text.includes('(الحالية)')) {
                                                opt.text = opt.text + ' (الحالية)';
                                            }
                                        },
                                        hideCurrentLabel(e) {
                                            let opt = e.target.querySelector('option[disabled]:checked');
                                            if (opt) {
                                                opt.text = opt.text.replace(' (الحالية)', '').trim();
                                            }
                                        },
                                        checkStatus(e) {
                                            this.hideCurrentLabel(e);
                                            if (e.target.value === '{{ \App\Enums\PotentialCustomerStatus::CONTACTED->value }}') {
                                                this.showModal = true;
                                                e.target.value = e.target.getAttribute('data-original-value');
                                            } else if (e.target.value === '{{ \App\Enums\PotentialCustomerStatus::CANCELLED->value }}') {
                                                this.showCancelledModal = true;
                                                e.target.value = e.target.getAttribute('data-original-value');
                                            } else {
                                                // هنا تم استبدال الاستدعاء المباشر القديم بـ $dispatch لإرسال الحدث للأب بنجاح
                                                $dispatch('change-status', { event: e, formId: 'status-form-{{ $customer->id }}' });
                                            }
                                        }
                                    }">

                                    <div class="grid grid-cols-[1fr_auto] items-center gap-2 max-w-[200px] mx-auto">
                                        <div class="w-full">
                                            <x-potential-customers.status-select :customer="$customer" />
                                        </div>

                                        <x-potential-customers.action-buttons :customer="$customer" />
                                    </div>

                                    <x-potential-customers.contacted-modal :customer="$customer" />
                                    <x-potential-customers.cancelled-modal :customer="$customer" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->isCEO() ? '7' : '6' }}"
                                    class="p-12 text-center text-gray-400 dark:text-slate-500 italic text-sm">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <svg class="w-8 h-8 text-gray-300 dark:text-slate-700" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        No potential lead logs matching filter arrays were found.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="shrink-0 pt-4 mt-2 border-t border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 dynamic-pagination">
            {{ $customers->appends(request()->query())->links() }}
        </div>

        <x-confirmation-modal />
    </div>

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