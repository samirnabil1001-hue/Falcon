{{-- resources/views/components/urgent-customers-table.blade.php --}}

<div x-data="{ activeTab: 'contacted' }"
    class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-none">

    <!-- الهيدر وأزرار التبديل -->
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
            <p class="text-[11px] text-slate-400 mt-0.5">أحدث 7 عملاء بحسب الحالة المحددة لمتابعتهم وتحديث موقفهم فوراً
            </p>
        </div>

        <!-- أزرار التبديل (Switch Tabs) الافتراضي هو contacted -->
        <div
            class="flex p-1 bg-slate-100/80 dark:bg-slate-900/60 rounded-xl w-full sm:w-auto self-stretch sm:self-auto">
            <button @click="activeTab = 'contacted'"
                :class="activeTab === 'contacted' ?
                    'bg-white dark:bg-slate-800 text-amber-600 dark:text-amber-400 shadow-sm font-bold' :
                    'text-slate-500 dark:text-slate-400'"
                class="flex-1 sm:flex-initial px-4 py-1.5 text-xs rounded-lg transition-all duration-200 text-center flex items-center justify-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-3.5 h-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                قيد المتابعة (Contacted)
            </button>

            <button @click="activeTab = 'new'"
                :class="activeTab === 'new' ?
                    'bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 shadow-sm font-bold' :
                    'text-slate-500 dark:text-slate-400'"
                class="flex-1 sm:flex-initial px-4 py-1.5 text-xs rounded-lg transition-all duration-200 text-center flex items-center justify-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-3.5 h-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.813 15.904L9 21l8.954-8.955M21 12h0M12 21h0M21 3H9m0 0L3 9m6-6v6" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11.48 3.499c-.105-.347-.492-.546-.86-.444L3.433 5.084c-.406.113-.64.53-.522.928l1.415 4.79c.117.397.548.613.948.474l2.428-.843a.475.475 0 01.374.032l2.308 1.25c.34.184.757.08 1.026-.226l3.415-3.89a.573.573 0 00-.022-.816l-3.322-3.084z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.813 15.904L9 21l8.954-8.955M21 12h0M12 21h0M21 3H9m0 0L3 9m6-6v6" class="hidden" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 6l1 .5L17 6l.5 1-.5 1-.5-1-1-.5zm-7 5l.5.25.25.5.25-.5.5-.25-.5-.25-.25-.5-.25.5zm9 7l.5.25.25.5.25-.5.5-.25-.5-.25-.25-.5-.25.5z" />
                </svg>
                جديد (New)
            </button>
        </div>
    </div>

    <!-- الجدول ومحتوياته -->
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

            <tbody x-show="activeTab === 'contacted'" class="divide-y divide-slate-100/70 dark:divide-slate-700/40">
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
                                class="px-2 py-0.5 bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 rounded text-[11px] font-semibold">قيد
                                المتابعة</span>
                        </td>
                        <td class="px-4 py-3 text-[11px] text-slate-400">
                            {{ $customer->created_at ? $customer->created_at->format('M d, Y • H:i') : 'غير محدد' }}
                        </td>
                        <td class="p-4 text-center whitespace-nowrap align-middle w-56 min-w-[220px]"
                            x-data="{ showModal: false, showCancelledModal: false, currentStatus: 'contacted', checkStatus(e) { /* نفس كود الـ Alpine القديم بالكامل */ } }">
                            <!-- الـ Form والأزرار الخاصة بالإجراءات تترك كما هي دون تغيير -->
                            <form id="status-form-{{ $customer->id }}"
                                action="{{ route('potential-customers.update-status', $customer->id) }}" method="POST"
                                class="hidden">
                                @csrf @method('PATCH') <input type="hidden" name="status" value="">
                            </form>
                            <div class="grid grid-cols-[1fr_auto] items-center gap-2 max-w-[200px] mx-auto">
                                <div class="w-full"><x-potential-customers.status-select :customer="$customer" /></div>
                                <x-potential-customers.action-buttons :customer="$customer" />
                            </div>
                            <x-potential-customers.contacted-modal :customer="$customer" />
                            <x-potential-customers.cancelled-modal :customer="$customer" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-400 text-xs">
                            🎉 ممتاز! لا يوجد عملاء بحالة 'قيد المتابعة' حالياً.
                        </td>
                    </tr>
                @endforelse
            </tbody>

            <!-- تبويب جديد (يعرض 5 كحد أقصى) -->
            <tbody x-show="activeTab === 'new'" class="divide-y divide-slate-100/70 dark:divide-slate-700/40" x-cloak>
                @forelse($newCustomers as $customer)
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
                                class="px-2 py-0.5 bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 rounded text-[11px] font-semibold">جديد</span>
                        </td>
                        <td class="px-4 py-3 text-[11px] text-slate-400">
                            {{ $customer->created_at ? $customer->created_at->format('M d, Y • H:i') : 'غير محدد' }}
                        </td>
                        <td class="p-4 text-center whitespace-nowrap align-middle w-56 min-w-[220px]"
                            x-data="{ showModal: false, showCancelledModal: false, currentStatus: 'new', checkStatus(e) { /* نفس كود الـ Alpine القديم بالكامل */ } }">
                            <form id="status-form-{{ $customer->id }}"
                                action="{{ route('potential-customers.update-status', $customer->id) }}" method="POST"
                                class="hidden">
                                @csrf @method('PATCH') <input type="hidden" name="status" value="">
                            </form>
                            <div class="grid grid-cols-[1fr_auto] items-center gap-2 max-w-[200px] mx-auto">
                                <div class="w-full"><x-potential-customers.status-select :customer="$customer" /></div>
                                <x-potential-customers.action-buttons :customer="$customer" />
                            </div>
                            <x-potential-customers.contacted-modal :customer="$customer" />
                            <x-potential-customers.cancelled-modal :customer="$customer" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-400 text-xs">
                            🎉 ممتاز! لا يوجد عملاء جدد معلقين بحاجة لاتصال فوري.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>
