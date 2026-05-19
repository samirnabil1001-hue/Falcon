{{-- resources/views/components/urgent-customers-table.blade.php --}}
@props(['recentUrgentCustomers'])

<div
    class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-none">
    <div class="flex justify-between items-center mb-5">
        <div>
            <h3 class="text-slate-700 dark:text-slate-200 text-sm font-bold">🚨 عملاء يتطلبون اتصالاً
                عاجلاً</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">أحدث 5 عملاء بحالة (جديد / قيد المتابعة) لمتابعتهم
                وتحديث موقفهم فوراً</p>
        </div>
        <span
            class="px-2.5 py-1 bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-400 rounded-md text-[10px] font-bold">مهام
            يومية معلقة</span>
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
                    <th class="px-4 py-2.5 text-left">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100/70 dark:divide-slate-700/40">
                @forelse($recentUrgentCustomers as $customer)
                    <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-900/20 transition-colors">
                        <td class="px-4 py-3 font-medium text-slate-800 dark:text-slate-200">
                            {{ $customer->name }}
                        </td>
                        <td class="px-4 py-3 text-xs tracking-wide font-mono" dir="ltr">
                            {{ $customer->phone }}
                        </td>
                        <td class="px-4 py-3 text-xs">
                            <span
                                class="px-2 py-0.5 bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 rounded text-[11px]">
                                {{ method_exists($customer->source, 'label') ? $customer->source->label() : $customer->source }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs">
                            @if ($customer->status->value === 'new')
                                <span
                                    class="px-2 py-0.5 bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 rounded text-[11px] font-semibold">جديد</span>
                            @else
                                <span
                                    class="px-2 py-0.5 bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 rounded text-[11px] font-semibold">قيد
                                    المتابعة</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-[11px] text-slate-400">
                            {{ $customer->created_at ? $customer->created_at->format('M d, Y • H:i') : 'غير محدد' }}
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
                                    let selectedValue = e.target.value;
                            
                                    if (selectedValue === '{{ \App\Enums\PotentialCustomerStatus::CONTACTED->value ?? 'contacted' }}') {
                                        this.showModal = true;
                                        e.target.value = e.target.getAttribute('data-original-value');
                                    } else if (selectedValue === '{{ \App\Enums\PotentialCustomerStatus::CANCELLED->value ?? 'cancelled' }}') {
                                        this.showCancelledModal = true;
                                        e.target.value = e.target.getAttribute('data-original-value');
                                    } else {
                                        let form = document.getElementById('status-form-{{ $customer->id }}');
                                        if (form) {
                                            form.querySelector('input[name=&quot;status&quot;]').value = selectedValue;
                                        }
                            
                                        $dispatch('change-status', { event: e, formId: 'status-form-{{ $customer->id }}' });
                                    }
                                }
                            }">

                            <form id="status-form-{{ $customer->id }}"
                                action="{{ route('potential-customers.update-status', $customer->id) }}"
                                method="POST" class="hidden">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="">
                            </form>

                            <div
                                class="grid grid-cols-[1fr_auto] items-center gap-2 max-w-[200px] mx-auto">
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
                        <td colspan="6" class="px-4 py-8 text-center text-slate-400 text-xs">
                            🎉 ممتاز! لا يوجد عملاء معلقين حالياً بحاجة لاتصال فوري.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>