<x-app-layout>

    <div class=" bg-gray-50 dark:bg-slate-900 min-h-screen" dir="rtl">
        <div
            class=" mx-auto bg-white dark:bg-slate-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-slate-700">

            <!-- الهيدر الخاص بالصفحة -->
            <div
                class="px-6 py-5 border-b border-gray-200 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50 dark:bg-slate-800/50">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-slate-100 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        سجل متابعة وتحديث حالات العملاء
                    </h2>
                    <p class="text-xs text-gray-400 dark:text-slate-400 mt-1">تابع تحركات العملاء، الأسباب، ومواعيد
                        التواصل القادمة أولاً بأول.</p>
                </div>
                <span
                    class="bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300 text-xs font-semibold px-3 py-1.5 rounded-lg border border-indigo-100 dark:border-indigo-900/50">
                    إجمالي العملاء: {{ $customers->total() }}
                </span>
            </div>

            <!-- رسائل التنبيه والنجاح -->
            @if (session('success'))
                <div
                    class="m-6 p-4 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-400 rounded-xl border border-emerald-200 dark:border-emerald-900/50 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->has('error'))
                <div
                    class="m-6 p-4 bg-rose-50 dark:bg-rose-950/30 text-rose-800 dark:text-rose-400 rounded-xl border border-rose-200 dark:border-rose-900/50 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $errors->first('error') }}
                </div>
            @endif

            <!-- جدول البيانات المطور -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-right text-gray-500 dark:text-slate-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-slate-700 dark:text-slate-300">
                        <tr>
                            <th scope="col" class="px-6 py-4">العميل</th>
                            <th scope="col" class="px-6 py-4">رقم الهاتف</th>
                            <th scope="col" class="px-6 py-4 text-center">الحالة الحالية</th>
                            <th scope="col" class="px-6 py-4 text-center">إجمالي المتابعات</th>
                            <th scope="col" class="px-6 py-4 text-center">آخر إجراء مسجل</th>
                            <th scope="col" class="px-6 py-4 text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse($customers as $customer)
                            <tr
                                class="bg-white border-b dark:bg-slate-800 dark:border-slate-700 hover:bg-gray-50/80 dark:hover:bg-slate-700/40 transition-colors">

                                <!-- اسم العميل -->
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 flex items-center justify-center font-bold text-xs">
                                            {{ mb_substr($customer->name, 0, 1) }}
                                        </div>
                                        <span>{{ $customer->name }}</span>
                                    </div>
                                </td>

                                <!-- رقم الهاتف -->
                                <td class="px-6 py-4 whitespace-nowrap text-left" dir="ltr">
                                    {{ $customer->phone }}
                                </td>

                                <!-- الحالة الحالية كـ Badge ملون -->
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if ($customer->status === 'NEW' || (is_object($customer->status) && $customer->status->value === 'NEW')) bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300
                                    @elseif($customer->status === 'CONTACTED' || (is_object($customer->status) && $customer->status->value === 'CONTACTED')) bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300
                                    @elseif($customer->status === 'CONFIRMED' || (is_object($customer->status) && $customer->status->value === 'CONFIRMED')) bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300
                                    @else bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-300 @endif">
                                        {{ is_object($customer->status) ? $customer->status->label() : $customer->status }}
                                    </span>
                                </td>

                                <!-- عدد المتابعات التراكمي القادم من الخدمة من خلال withCount -->
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 rounded-full border border-indigo-100 dark:border-indigo-900/30">
                                        {{ $customer->follow_ups_count }} إجراءات
                                    </span>
                                </td>

                                <!-- تفاصيل آخر خطوة مأخوذة من الـ Relation -->
                                <td class="px-6 py-4 text-center text-xs max-w-xs truncate">
                                    @if ($customer->followUps->first())
                                        <div class="text-gray-600 dark:text-slate-300 font-medium truncate">
                                            {{ $customer->followUps->first()->reason ?? 'بدون سبب معين' }}
                                        </div>
                                        <div class="text-[10px] text-gray-400 mt-0.5">
                                            {{ $customer->followUps->first()->created_at?->diffForHumans() }}
                                        </div>
                                    @else
                                        <span class="text-gray-300 dark:text-slate-600 italic">لا توجد متابعات
                                            سابقة</span>
                                    @endif
                                </td>

                                <!-- إجراء فتح المودال لإضافة خطوة جديدة للمتابعة -->
                                <td class="px-6 py-4 text-center whitespace-nowrap" x-data="{ openModal: false }">
                                    <button @click="openModal = true"
                                        class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg shadow-sm hover:shadow transition-all inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                        إجراء متابعة
                                    </button>

                                    <!-- حماية وترحيل المودال خارج الجدول إلى الـ Body لمنع مشاكل العرض والتداخل -->
                                    <template x-teleport="body">
                                        <div x-show="openModal" class="fixed inset-0 z-[9999] overflow-y-auto"
                                            style="display: none;">
                                            <div
                                                class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                                                <!-- غطاء الخلفية الداكن الشفاف -->
                                                <div @click="openModal = false"
                                                    class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 transition-opacity">
                                                </div>

                                                <span
                                                    class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                                                <!-- جسم المودال المنبثق -->
                                                <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-xl text-right overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full w-full p-6 border border-gray-100 dark:border-slate-700 relative"
                                                    dir="rtl">

                                                    <!-- زر الإغلاق X السريع علوي -->
                                                    <button type="button" @click="openModal = false"
                                                        class="absolute top-4 left-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>

                                                    <h3
                                                        class="text-lg font-bold text-gray-900 dark:text-slate-100 mb-2">
                                                        تسجيل إجراء متابعة جديد
                                                    </h3>
                                                    <p class="text-xs text-gray-400 dark:text-slate-400 mb-5">
                                                        أنت تقوم بتعديل بيانات للعميل: <span
                                                            class="text-indigo-600 dark:text-indigo-400 font-semibold">{{ $customer->name }}</span>
                                                    </p>

                                                    <!-- فوروم الحفظ المرتبط بالـ Route المباشر والـ Service -->
                                                    <form
                                                        action="{{ route('customer-follow-ups.store', $customer->id) }}"
                                                        method="POST" class="space-y-4">
                                                        @csrf

                                                        <!-- 1. تحديد الحالة الجديدة للعميل -->
                                                        <div>
                                                            <label
                                                                class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1.5">الحالة
                                                                الجديدة للعميل <span
                                                                    class="text-rose-500">*</span></label>
                                                            <select name="status" required
                                                                class="w-full text-sm border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2.5 bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                                                                <option value="CONTACTED"
                                                                    {{ $customer->status === 'CONTACTED' ? 'selected' : '' }}>
                                                                    CONTACTED (تم التواصل)</option>
                                                                <option value="CONFIRMED"
                                                                    {{ $customer->status === 'CONFIRMED' ? 'selected' : '' }}>
                                                                    CONFIRMED (مؤكد وحجز)</option>
                                                                <option value="CANCELLED"
                                                                    {{ $customer->status === 'CANCELLED' ? 'selected' : '' }}>
                                                                    CANCELLED (ملغي / غير مهتم)</option>
                                                            </select>
                                                        </div>

                                                        <!-- 2. سبب الإجراء الحالي (Reason) -->
                                                        <div>
                                                            <label
                                                                class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1.5">السبب
                                                                / نتيجة المكالمة</label>
                                                            <input type="text" name="reason"
                                                                placeholder="مثال: يسأل عن عروض الصيف / لم يرد"
                                                                class="w-full text-sm border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2.5 bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                                                        </div>

                                                        <!-- 3. تاريخ المتابعة القادم التذكيري -->
                                                        <div>
                                                            <label
                                                                class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1.5">تاريخ
                                                                ووقت المتابعة القادمة</label>
                                                            <input type="datetime-local" name="next_follow_up_date"
                                                                onclick="this.showPicker()"
                                                                class="w-full text-sm border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2.5 bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-right cursor-pointer">
                                                        </div>

                                                        <!-- 4. مساحة لكتابة الملاحظات المفصلة -->
                                                        <div>
                                                            <label
                                                                class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1.5">ملاحظات
                                                                داخلية إضافية</label>
                                                            <textarea name="notes" rows="3" placeholder="اكتب هنا أي تفاصيل دارت في المحادثة لمساعدتك لاحقاً..."
                                                                class="w-full text-sm border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2.5 bg-white dark:bg-slate-700 text-gray-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"></textarea>
                                                        </div>

                                                        <!-- أزرار التحكم وحفظ البيانات -->
                                                        <div
                                                            class="mt-6 pt-4 border-t border-gray-100 dark:border-slate-700 flex flex-row-reverse gap-2">
                                                            <button type="submit"
                                                                class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-sm hover:shadow transition-all">
                                                                حفظ وإضافة للـ Log
                                                            </button>
                                                            <button type="button" @click="openModal = false"
                                                                class="px-4 py-2.5 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-lg text-xs font-semibold hover:bg-gray-200 dark:hover:bg-slate-600 transition-all">
                                                                إلغاء التعديل
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <a href="{{ route('customer-follow-ups.show', $customer->id) }}"
                                        class="px-3 py-2 bg-slate-600 hover:bg-slate-700 text-white text-xs font-medium rounded-lg shadow-sm hover:shadow transition-all inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        عرض السجل التاريخي
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="px-6 py-12 text-center text-gray-400 dark:text-slate-500 font-medium">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <svg class="w-8 h-8 text-gray-300 dark:text-slate-600" fill="none"
                                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5" />
                                        </svg>
                                        لا يوجد أي عملاء محتملين مسجلين في النظام حالياً.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- الترقيم والصفحات للجدول (Pagination links) -->
            <div class="px-6 py-4 bg-gray-50/50 dark:bg-slate-800/50 border-t border-gray-100 dark:border-slate-700">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
