<x-app-layout>
    <!-- المكون الأب الرئيسي: يحتوي على مستمع الأحداث لـ change-status و change-user -->
    <div x-data="{
        confirmModal: false,
        modalTitle: '',
        modalMessage: '',
        formToSubmit: null,
        confirmColor: 'bg-indigo-600',
        pendingStatusValue: null,
        pendingUserIdValue: null,
        isStatusChange: true,
        openConfirm(title, message, formId, color = 'bg-indigo-600') {
            this.modalTitle = title;
            this.modalMessage = message;
            this.formToSubmit = formId;
            this.confirmColor = color;
            this.confirmModal = true;
        },
        handleStatusChange(event, formId) {
            this.isStatusChange = true;
            let select = event.target;
            let originalValue = select.getAttribute('data-original-value');
    
            if (select.value === originalValue) return;
    
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
        handleUserChange(event, formId) {
            this.isStatusChange = false;
            let select = event.target;
            let originalValue = select.getAttribute('data-original-value') || '';
    
            if (select.value === originalValue) return;
    
            this.pendingUserIdValue = select.value;
            let userLabel = select.options[select.selectedIndex].text;
    
            this.openConfirm(
                'تغيير المسؤول عن العميل',
                `هل أنت متأكد من رغبتك في نقل تبعية العميل إلى (${userLabel})؟`,
                formId,
                'bg-rose-600 hover:bg-rose-700'
            );
    
            select.value = originalValue;
        },
        submitPendingForm() {
            let form = document.getElementById(this.formToSubmit);
            if (this.isStatusChange) {
                let select = form.querySelector('select[name=\'status\']');
                if (select) select.value = this.pendingStatusValue;
            } else {
                let select = form.querySelector('select[name=\'user_id\']');
                if (select) select.value = this.pendingUserIdValue;
            }
            form.submit();
        }
    }" @change-status.window="handleStatusChange($event.detail.event, $event.detail.formId)"
        @change-user.window="handleUserChange($event.detail.event, $event.detail.formId)"
        class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 p-5 md:p-6 h-[calc(100vh-12rem)] lg:h-[calc(100vh-7rem)] flex flex-col overflow-hidden transition-colors duration-300">

        <x-potential-customers.header :totalCount="$customers->total()" />

        <x-potential-customers.filter-panel :search="request('search')" :dateFrom="request('date_from')" :dateTo="request('date_to')" :source="request('source')"
            :status="request('status')" :sortBy="request('sort_by', 'added_at')" :sortOrder="request('sort_order', 'desc')" />

        <div
            class="flex-1 h-0 overflow-hidden rounded-xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
            <div class="h-full overflow-auto custom-scrollbar">
                <table class="w-full min-w-[1000px] border-collapse text-left">
                    <thead
                        class="sticky top-0 z-20 bg-gray-50/90 dark:bg-slate-800/90 backdrop-blur-md border-b border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-300">
                        <tr>
                            <th class="p-4 font-bold tracking-wider text-center text-[14px]">اسم العميل</th>
                            <th class="p-4 font-bold tracking-wider text-center text-[14px]">رقم الهاتف</th>
                            <th class="p-4 font-bold tracking-wider text-center text-[14px]">المصدر</th>
                            <th class="p-4 font-bold tracking-wider text-center text-[14px]">الحالة</th>
                            @if (auth()->user()->isCEO())
                                <th class="p-4 font-bold tracking-wider text-center text-[14px]">أُضيف بواسطة
                                </th>
                            @endif
                            <th class="p-4 font-bold tracking-wider text-center text-[14px]">تاريخ الإضافة
                            </th>
                            <th class="p-4 font-bold tracking-wider text-center text-[14px] w-48 min-w-[190px]">
                                الإجراءات</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                        @forelse($customers as $customer)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors group">
                                <td class="p-4 text-center whitespace-nowrap"><span
                                        class="font-semibold text-sm text-gray-900 dark:text-gray-100">{{ $customer->name }}</span>
                                </td>
                                <td class="p-4 text-center whitespace-nowrap text-xs font-medium text-gray-600 dark:text-slate-300"
                                    dir="ltr">
                                    <span
                                        class="text-gray-400 dark:text-slate-500 font-normal">{{ $customer->country_code }}</span>
                                    <span>{{ $customer->phone }}</span>
                                </td>
                                <td class="p-4 text-center whitespace-nowrap"><x-potential-customers.source-badge
                                        :source="$customer->source" /></td>
                                <td class="p-4 text-center whitespace-nowrap"><x-potential-customers.status-badge
                                        :status="$customer->status" /></td>

                                @if (auth()->user()->isCEO())
                                    <td
                                        class="p-4 text-center whitespace-nowrap text-xs font-medium text-gray-600 dark:text-slate-400">
                                        <form id="update-user-form-{{ $customer->id }}"
                                            action="{{ route('potential-customers.update-added-by', $customer->id) }}"
                                            method="POST" class="inline-block w-full">
                                            @csrf
                                            @method('PUT')

                                            <div class="relative w-full">

                                                <!-- أضفنا bg-none لإزالة أي سهم خلفية قادم من إضافات Tailwind -->
                                                <select name="user_id" data-original-value="{{ $customer->user_id }}"
                                                    @change="$dispatch('change-user', { event: $event, formId: 'update-user-form-{{ $customer->id }}' })"
                                                    class="appearance-none bg-none bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg block w-full p-1.5 pl-8 pr-3 dark:bg-slate-700 dark:border-slate-600 dark:text-white cursor-pointer">
                                                    <option value="">System</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}"
                                                            {{ $customer->user_id == $user->id ? 'selected' : '' }}>
                                                            {{ $user->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <!-- السهم المخصص الوحيد والمثبت على الشمال تماماً -->
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5 text-gray-500 dark:text-slate-400">
                                                    <svg class="h-2 w-2" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </div>

                                            </div>
                                        </form>
                                    </td>
                                @endif

                                <td class="p-4 text-center whitespace-nowrap text-gray-500 dark:text-slate-400 text-xs">
                                    {{ \Carbon\Carbon::parse($customer->added_at)->format('M d, Y • H:i') }}
                                </td>

                                <td class="p-4 text-center whitespace-nowrap align-middle w-56 min-w-[220px]"
                                    x-data="{
                                        showModal: false,
                                        showCancelledModal: false,
                                        showConfirmedModal: false,
                                        checkStatus(e) {
                                            if (e.target.value === '{{ \App\Enums\PotentialCustomerStatus::CONTACTED->value }}') {
                                                this.showModal = true;
                                                e.target.value = e.target.getAttribute('data-original-value');
                                            } else if (e.target.value === '{{ \App\Enums\PotentialCustomerStatus::CANCELLED->value }}') {
                                                this.showCancelledModal = true;
                                                e.target.value = e.target.getAttribute('data-original-value');
                                            } else if (e.target.value === '{{ \App\Enums\PotentialCustomerStatus::CONFIRMED->value }}') {
                                                this.showConfirmedModal = true;
                                                e.target.value = e.target.getAttribute('data-original-value');
                                            } else {
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
                                    <x-potential-customers.confirmed-modal :route="route('potential-customers.update-status', $customer->id)" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-12 text-center text-gray-400">No data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div
            class="shrink-0 pt-4 mt-2 border-t border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 dynamic-pagination">
            {{ $customers->appends(request()->query())->links() }}
        </div>

        <!-- تم تنظيف تكرار الـ x-cloak هنا لتجنب وميض المتصفح -->
        <div x-cloak x-show="confirmModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div @click.away="confirmModal = false"
                class="bg-white dark:bg-slate-800 rounded-xl p-6 max-w-sm w-full shadow-2xl">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white" x-text="modalTitle"></h3>
                <p class="text-sm text-gray-500 dark:text-slate-400 mt-2" x-text="modalMessage"></p>
                <div class="flex justify-end gap-2 mt-4">
                    <button @click="confirmModal = false"
                        class="px-4 py-2 bg-gray-200 text-gray-800 text-xs rounded-lg dark:bg-slate-700 dark:text-slate-300">إلغاء</button>
                    <button @click="submitPendingForm()" :class="confirmColor"
                        class="px-4 py-2 text-white text-xs rounded-lg">تأكيد</button>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>

<style>
    /* تعديل الكلمة الإملائية الصحيحة لتفعيل الـ x-cloak المانع للوميض */
    [x-cloak] {
        display: none !important;
    }
</style>
