<div x-data="{
    activeTab: 'contacted',
    confirmModal: false,
    modalTitle: '',
    modalMessage: '',
    formToSubmit: null,
    confirmColor: 'bg-indigo-600',
    pendingStatusValue: null,

    // فتح مودال التأكيد الشامل للحالات العادية
    openConfirm(title, message, formId, color = 'bg-indigo-600') {
        this.modalTitle = title;
        this.modalMessage = message;
        this.formToSubmit = formId;
        this.confirmColor = color;
        this.confirmModal = true;
    },

    // معالجة تغيير الحالة القادم من الـ Select
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

        // إرجاع القيمة ظاهرياً لحين الضغط على تأكيد
        select.value = originalValue;
    },

    // تنفيذ الإرسال الفعلي بعد التأكيد وضخ القيمة في الـ input المخفي
    submitPendingForm() {
        let form = document.getElementById(this.formToSubmit);
        let input = form.querySelector('input[name=\'status\']');
        if (input) {
            input.value = this.pendingStatusValue;
        }
        form.submit();
    }
}" @change-status.window="handleStatusChange($event.detail.event, $event.detail.formId)"
    class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-none">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5">
        <div>
            <h3 class="text-slate-700 dark:text-slate-200 text-sm font-bold flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-4 h-4 text-red-500 animate-pulse">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.857 17.082a9.041 9.041 0 01-9.714 0M18 10a6 6 0 00-12 0c0 7-3 9-3 9h18s-3-2-3-9zm-12 9a3 3 0 006 0" />
                </svg>
                عملاء يتطلبون اتصالاً عاجلاً
            </h3>
            <p class="text-[11px] text-slate-400 mt-0.5">أحدث العملاء بحسب الحالة المحددة لمتابعتهم وتحديث موقفهم فوراً
            </p>
        </div>

        <div
            class="flex p-1 bg-slate-100/80 dark:bg-slate-900/50 backdrop-blur-sm rounded-xl w-full sm:w-auto self-stretch sm:self-auto gap-1 border border-slate-200/40 dark:border-slate-800/40">

            <button @click="activeTab = 'contacted'"
                :class="activeTab === 'contacted' ?
                    'bg-white dark:bg-slate-800 text-amber-600 dark:text-amber-400 shadow-sm font-semibold' :
                    'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:-translate-y-0.5'"
                class="flex-1 sm:flex-initial px-4 py-2 text-xs rounded-lg transition-all duration-200 text-center flex items-center justify-center gap-2 transform">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-3.5 h-3.5 transition-transform duration-300"
                    :class="activeTab === 'contacted' && 'rotate-180'">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span>قيد المتابعة</span>
            </button>

            <button @click="activeTab = 'new'"
                :class="activeTab === 'new' ?
                    'bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 shadow-sm font-semibold' :
                    'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:-translate-y-0.5'"
                class="flex-1 sm:flex-initial px-4 py-2 text-xs rounded-lg transition-all duration-200 text-center flex items-center justify-center gap-2 transform">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-3.5 h-3.5" :class="activeTab === 'new' && 'animate-pulse'">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>جديد</span>
            </button>

        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-right text-slate-600 dark:text-slate-300">
            <thead class="text-xs text-slate-400 bg-slate-50/50 dark:bg-slate-900/40 rounded-lg">
                <tr>
                    <th class="px-4 py-2.5">اسم العميل</th>
                    <th class="px-4 py-2.5">رقم الهاتف</th>
                    <th class="px-4 py-2.5">المصدر</th>
                    <th class="px-4 py-2.5">الحالة</th>
                    <th class="px-4 py-2.5">تاريخ الإضافة</th>
                    <th class="px-4 py-2.5 text-center">الإجراءات</th>
                </tr>
            </thead>

            <tbody x-show="activeTab === 'contacted'" x-cloak
                class="divide-y divide-slate-100/70 dark:divide-slate-700/40">
                @forelse($contactedCustomers as $customer)
                    <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-900/20 transition-colors">
                        <td class="px-4 py-3 font-medium text-slate-800 dark:text-slate-200">{{ $customer->name }}</td>
                        <td class="px-4 py-3 text-xs tracking-wide font-mono" dir="ltr">{{ $customer->phone }}</td>
                        <td class="px-4 py-3 text-xs">
                            <span
                                class="px-2 py-0.5 bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 rounded text-[11px]">
                                {{ method_exists($customer->source, 'label') ? $customer->source->label() : $customer->source }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs">
                            <span
                                class="px-2 py-0.5 bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 rounded text-[11px] font-semibold">
                                قيد المتابعة
                            </span>
                        </td>
                        <td class="px-4 py-3 text-[11px] text-slate-400">
                            {{ $customer->created_at ? $customer->created_at->format('Y-m-d • H:i') : 'غير محدد' }}
                        </td>
                        <td class="p-4 text-center whitespace-nowrap align-middle w-56 min-w-[220px]"
                            x-data="{
                                showModal: false,
                                showCancelledModal: false,
                                showConfirmedModal: false,
                                checkStatus(e) {
                                    let selectedValue = e.target.value;
                                    let originalValue = e.target.getAttribute('data-original-value');
                            
                                    if (selectedValue === '{{ \App\Enums\PotentialCustomerStatus::CONTACTED->value }}') {
                                        this.showModal = true;
                                        e.target.value = originalValue;
                                    } else if (selectedValue === '{{ \App\Enums\PotentialCustomerStatus::CANCELLED->value }}') {
                                        this.showCancelledModal = true;
                                        e.target.value = originalValue;
                                    } else if (selectedValue === '{{ \App\Enums\PotentialCustomerStatus::CONFIRMED->value }}') {
                                        this.showConfirmedModal = true;
                                        e.target.value = originalValue;
                                    } else if (selectedValue !== originalValue) {
                                        $dispatch('change-status', { event: e, formId: 'status-form-urgent-contacted-{{ $customer->id }}' });
                                    }
                                }
                            }">

                            <form id="status-form-urgent-contacted-{{ $customer->id }}"
                                action="{{ route('potential-customers.update-status', $customer->id) }}" method="POST"
                                class="hidden">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="">
                            </form>

                            <div class="grid grid-cols-[1fr_auto] items-center gap-2 max-w-[200px] mx-auto">
                                <div class="w-full">
                                    <x-potential-customers.status-select :customer="$customer" />
                                </div>
                                <x-potential-customers.action-buttons :customer="$customer" />
                            </div>

                            <div x-cloak>
                                <x-potential-customers.contacted-modal :customer="$customer" />
                                <x-potential-customers.cancelled-modal :customer="$customer" />
                                <x-potential-customers.confirmed-modal :customer="$customer" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-400 text-xs">
                            ممتاز! لا يوجد عملاء بحالة 'قيد المتابعة' حالياً.
                        </td>
                    </tr>
                @endforelse
            </tbody>

            <tbody x-show="activeTab === 'new'" x-cloak class="divide-y divide-slate-100/70 dark:divide-slate-700/40">
                @forelse($newCustomers as $customer)
                    <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-900/20 transition-colors">
                        <td class="px-4 py-3 font-medium text-slate-800 dark:text-slate-200">{{ $customer->name }}</td>
                        <td class="px-4 py-3 text-xs tracking-wide font-mono" dir="ltr">{{ $customer->phone }}
                        </td>
                        <td class="px-4 py-3 text-xs">
                            <span
                                class="px-2 py-0.5 bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 rounded text-[11px]">
                                {{ method_exists($customer->source, 'label') ? $customer->source->label() : $customer->source }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs">
                            <span
                                class="px-2 py-0.5 bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 rounded text-[11px] font-semibold">
                                جديد
                            </span>
                        </td>
                        <td class="px-4 py-3 text-[11px] text-slate-400">
                            {{ $customer->created_at ? $customer->created_at->format('Y-m-d • H:i') : 'غير محدد' }}
                        </td>
                        <td class="p-4 text-center whitespace-nowrap align-middle w-56 min-w-[220px]"
                            x-data="{
                                showModal: false,
                                showCancelledModal: false,
                                showConfirmedModal: false,
                                checkStatus(e) {
                                    let selectedValue = e.target.value;
                                    let originalValue = e.target.getAttribute('data-original-value');
                            
                                    if (selectedValue === '{{ \App\Enums\PotentialCustomerStatus::CONTACTED->value }}') {
                                        this.showModal = true;
                                        e.target.value = originalValue;
                                    } else if (selectedValue === '{{ \App\Enums\PotentialCustomerStatus::CANCELLED->value }}') {
                                        this.showCancelledModal = true;
                                        e.target.value = originalValue;
                                    } else if (selectedValue === '{{ \App\Enums\PotentialCustomerStatus::CONFIRMED->value }}') {
                                        this.showConfirmedModal = true;
                                        e.target.value = originalValue;
                                    } else if (selectedValue !== originalValue) {
                                        $dispatch('change-status', { event: e, formId: 'status-form-urgent-new-{{ $customer->id }}' });
                                    }
                                }
                            }">

                            <form id="status-form-urgent-new-{{ $customer->id }}"
                                action="{{ route('potential-customers.update-status', $customer->id) }}" method="POST"
                                class="hidden">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="">
                            </form>

                            <div class="grid grid-cols-[1fr_auto] items-center gap-2 max-w-[200px] mx-auto">
                                <div class="w-full">
                                    <x-potential-customers.status-select :customer="$customer" />
                                </div>
                                <x-potential-customers.action-buttons :customer="$customer" />
                            </div>

                            <div x-cloak>
                                <x-potential-customers.contacted-modal :customer="$customer" />
                                <x-potential-customers.cancelled-modal :customer="$customer" />
                                <x-potential-customers.confirmed-modal :customer="$customer" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-400 text-xs">
                            ممتاز! لا يوجد عملاء جدد معلقين بحاجة لاتصال فوري.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
