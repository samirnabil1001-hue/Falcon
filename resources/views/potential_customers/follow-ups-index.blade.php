<x-app-layout>

    <div class="bg-slate-50 dark:bg-slate-950 min-h-screen text-right" dir="rtl">
        <div
            class="mx-auto bg-white dark:bg-slate-900 shadow-xl rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800/80 transition-colors duration-300">

            <!-- الهيدر الخاص بالصفحة -->
            <div
                class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-50/50 dark:bg-slate-900/50">
                <div>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2.5">
                        <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        سجل متابعة وتحديث حالات العملاء
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">تابع تحركات العملاء، الأسباب، ومواعيد
                        التواصل القادمة أولاً بأول.</p>
                </div>
                <span
                    class="bg-violet-50 text-violet-700 dark:bg-violet-950/40 dark:text-violet-300 text-xs font-bold px-3.5 py-2 rounded-xl border border-violet-100 dark:border-violet-900/30">
                    إجمالي العملاء: <span class="text-sm font-extrabold">{{ $customers->total() }}</span>
                </span>
            </div>

            <!-- رسائل التنبيه والنجاح -->
            @if (session('success'))
                <div
                    class="m-6 p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-800 dark:text-emerald-400 rounded-xl border border-emerald-200/60 dark:border-emerald-900/40 text-sm flex items-center gap-2.5 shadow-sm animate-fade-in">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-500" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->has('error'))
                <div
                    class="m-6 p-4 bg-rose-50 dark:bg-rose-950/20 text-rose-800 dark:text-rose-400 rounded-xl border border-rose-200/60 dark:border-rose-900/40 text-sm flex items-center gap-2.5 shadow-sm animate-fade-in">
                    <svg class="w-5 h-5 text-rose-600 dark:text-rose-500" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $errors->first('error') }}
                </div>
            @endif

            <!-- جدول البيانات المطور -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-right text-slate-600 dark:text-slate-300">
                    <thead
                        class="text-xs text-slate-700 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold">العميل</th>
                            <th scope="col" class="px-6 py-4 font-semibold">رقم الهاتف</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center">الحالة الحالية</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center">إجمالي المتابعات</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center">آخر إجراء مسجل</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @forelse($customers as $customer)
                            <tr
                                class="bg-white dark:bg-slate-900 hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition-colors duration-200">

                                <!-- اسم العميل -->
                                <td class="px-6 py-4 font-medium text-slate-900 dark:text-white whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-xl bg-violet-50 dark:bg-violet-950/50 text-violet-600 dark:text-violet-400 flex items-center justify-center font-bold text-sm border border-violet-100/50 dark:border-violet-900/30">
                                            {{ mb_substr($customer->name, 0, 1) }}
                                        </div>
                                        <span
                                            class="font-semibold text-slate-800 dark:text-slate-200">{{ $customer->name }}</span>
                                    </div>
                                </td>

                                <!-- رقم الهاتف -->
                                <td class="px-6 py-4 whitespace-nowrap text-left text-slate-600 dark:text-slate-400 font-mono tracking-wide"
                                    dir="ltr">
                                    {{ $customer->phone }}
                                </td>

                                <!-- الحالة الحالية كـ Badge ملون -->
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full tracking-wide shadow-sm border
                                    @if ($customer->status === 'NEW' || (is_object($customer->status) && $customer->status->value === 'NEW')) bg-blue-50 text-blue-700 border-blue-100 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-900/30
                                    @elseif($customer->status === 'CONTACTED' || (is_object($customer->status) && $customer->status->value === 'CONTACTED')) bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-900/30
                                    @elseif($customer->status === 'CONFIRMED' || (is_object($customer->status) && $customer->status->value === 'CONFIRMED')) bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-900/30
                                    @else bg-rose-50 text-rose-700 border-rose-100 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-900/30 @endif">
                                        {{ is_object($customer->status) ? $customer->status->label() : $customer->status }}
                                    </span>
                                </td>

                                <!-- عدد المتابعات التراكمي -->
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 rounded-lg border border-slate-200/60 dark:border-slate-700/60">
                                        {{ $customer->follow_ups_count }} إجراءات
                                    </span>
                                </td>

                                <!-- تفاصيل آخر خطوة -->
                                <td class="px-6 py-4 text-center text-xs max-w-xs truncate">
                                    @if ($customer->followUps->first())
                                        <div class="text-slate-700 dark:text-slate-300 font-medium truncate">
                                            {{ $customer->followUps->first()->reason ?? 'بدون سبب معين' }}
                                        </div>
                                        <div
                                            class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 flex items-center justify-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $customer->followUps->first()->created_at?->diffForHumans() }}
                                        </div>
                                    @else
                                        <span class="text-slate-300 dark:text-slate-600 italic font-normal">لا توجد
                                            متابعات سابقة</span>
                                    @endif
                                </td>

                                <!-- إجراء فتح المودال وعرض السجل -->
                                <td class="px-6 py-4 text-center whitespace-nowrap" x-data="{ openModal: false }">
                                    <div
                                        class="inline-flex rounded-xl shadow-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-0.5 gap-1">

                                        @php
                                            // تحديد ما إذا كان العميل في حالة "قيد المتابعة" أم لا
                                            $isContacted =
                                                $customer->status === \App\Enums\PotentialCustomerStatus::CONTACTED ||
                                                $customer->status === 'contacted' ||
                                                (is_object($customer->status) &&
                                                    $customer->status->value === 'contacted');
                                        @endphp

                                        <!-- زر المتابعة (يصبح Disabled إذا لم تكن الحالة contacted) -->
                                        <button @click="openModal = true"
                                            @if (!$isContacted) disabled @endif
                                            class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all flex items-center gap-1.5 shadow-sm 
            {{ $isContacted
                ? 'bg-violet-600 hover:bg-violet-700 text-white shadow-violet-200 dark:shadow-none cursor-pointer'
                : 'bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-600 cursor-not-allowed shadow-none' }}">
                                            <svg class="w-3.5 h-3.5 {{ $isContacted ? 'text-white' : 'text-slate-300 dark:text-slate-700' }}"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                            إجراء متابعة
                                        </button>

                                        <a href="{{ route('customer-follow-ups.show', $customer->id) }}"
                                            class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-600 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-300 text-xs font-semibold rounded-lg transition-all flex items-center gap-1.5 border border-slate-200/40 dark:border-slate-700/40">
                                            <svg class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500" fill="none"
                                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            عرض السجل
                                        </a>
                                    </div>

                                    <!-- الـ Modal لن يظهر أو يعمل إلا إذا كان الزر فعالاً (خيار حماية إضافي) -->
                                    @if ($isContacted)
                                        <template x-teleport="body">
                                            <div x-show="openModal" class="fixed inset-0 z-[9999] overflow-y-auto"
                                                style="display: none;">
                                                <div
                                                    class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                                                    <div @click="openModal = false"
                                                        class="fixed inset-0 bg-slate-950/40 dark:bg-slate-950/70 backdrop-blur-sm transition-opacity">
                                                    </div>
                                                    <span
                                                        class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                                                    <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-right overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full w-full p-6 border border-slate-100 dark:border-slate-800 relative"
                                                        dir="rtl">

                                                        <button type="button" @click="openModal = false"
                                                            class="absolute top-4 left-4 p-1.5 rounded-lg text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>

                                                        <h3
                                                            class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-1">
                                                            تسجيل إجراء متابعة جديد</h3>
                                                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">أنت
                                                            تقوم بتعديل بيانات للعميل: <span
                                                                class="text-violet-600 dark:text-violet-400 font-bold">{{ $customer->name }}</span>
                                                        </p>

                                                        <form
                                                            action="{{ route('customer-follow-ups.store', $customer->id) }}"
                                                            method="POST" class="space-y-4">
                                                            @csrf
                                                            <div>
                                                                <label
                                                                    class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">الحالة
                                                                    الجديدة للعميل <span
                                                                        class="text-rose-500">*</span></label>
                                                                <select name="status" required
                                                                    class="w-full text-sm border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-3 bg-slate-50 dark:bg-slate-800/80 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 outline-none transition-all">
                                                                    <option value="contacted"
                                                                        {{ $customer->status === \App\Enums\PotentialCustomerStatus::CONTACTED || (is_object($customer->status) && $customer->status->value === 'contacted') ? 'selected' : '' }}>
                                                                        CONTACTED (تم التواصل)</option>
                                                                    <option value="confirmed"
                                                                        {{ $customer->status === \App\Enums\PotentialCustomerStatus::CONFIRMED || (is_object($customer->status) && $customer->status->value === 'confirmed') ? 'selected' : '' }}>
                                                                        CONFIRMED (مؤكد وحجز)</option>
                                                                    <option value="cancelled"
                                                                        {{ $customer->status === \App\Enums\PotentialCustomerStatus::CANCELLED || (is_object($customer->status) && $customer->status->value === 'cancelled') ? 'selected' : '' }}>
                                                                        CANCELLED (ملغي / غير مهتم)</option>
                                                                </select>
                                                            </div>

                                                            <div>
                                                                <label
                                                                    class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">السبب
                                                                    / نتيجة المكالمة</label>
                                                                <input type="text" name="reason"
                                                                    placeholder="مثال: يسأل عن عروض الصيف / لم يرد"
                                                                    class="w-full text-sm border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-3 bg-slate-50 dark:bg-slate-800/80 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 outline-none transition-all">
                                                            </div>

                                                            <div>
                                                                <label
                                                                    class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">تاريخ
                                                                    ووقت المتابعة القادمة</label>
                                                                <input type="datetime-local"
                                                                    name="next_follow_up_date"
                                                                    onclick="this.showPicker()"
                                                                    class="w-full text-sm border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-3 bg-slate-50 dark:bg-slate-800/80 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 outline-none transition-all text-right cursor-pointer">
                                                            </div>

                                                            <div>
                                                                <label
                                                                    class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">ملاحظات
                                                                    داخلية إضافية</label>
                                                                <textarea name="notes" rows="3" placeholder="اكتب هنا أي تفاصيل دارت في المحادثة لمساعدتك لاحقاً..."
                                                                    class="w-full text-sm border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-3 bg-slate-50 dark:bg-slate-800/80 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 outline-none transition-all resize-none"></textarea>
                                                            </div>

                                                            <div
                                                                class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 flex flex-row-reverse gap-2">
                                                                <button type="submit"
                                                                    class="px-4 py-2.5 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-xs font-bold shadow-md shadow-violet-200 dark:shadow-none transition-all">حفظ
                                                                    وإضافة للـ Log</button>
                                                                <button type="button" @click="openModal = false"
                                                                    class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">إلغاء</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="px-6 py-16 text-center text-slate-400 dark:text-slate-500 font-medium">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-2xl">
                                            <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none"
                                                stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5" />
                                            </svg>
                                        </div>
                                        <span class="text-sm">لا يوجد أي عملاء محتملين مسجلين في النظام حالياً.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- الترقيم والصفحات للجدول (Pagination links) -->
            <div
                class="px-6 py-4 bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800/60">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
