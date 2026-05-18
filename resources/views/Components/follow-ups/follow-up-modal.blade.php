<template x-teleport="body">
    <div x-show="openModal" class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div @click="openModal = false"
                class="fixed inset-0 bg-slate-950/40 dark:bg-slate-950/70 backdrop-blur-sm transition-opacity">
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-right overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full w-full p-6 border border-slate-100 dark:border-slate-800 relative"
                dir="rtl">

                <button type="button" @click="openModal = false"
                    class="absolute top-4 left-4 p-1.5 rounded-lg text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-1">
                    تسجيل إجراء متابعة جديد</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">أنت تقوم بتعديل بيانات للعميل: <span
                        class="text-violet-600 dark:text-violet-400 font-bold">{{ $customer->name }}</span>
                </p>

                <form action="{{ $route }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">الحالة
                            الجديدة للعميل <span class="text-rose-500">*</span></label>
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
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">السبب
                            / نتيجة المكالمة</label>
                        <input type="text" name="reason"
                            placeholder="مثال: يسأل عن عروض الصيف / لم يرد"
                            class="w-full text-sm border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-3 bg-slate-50 dark:bg-slate-800/80 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">تاريخ
                            ووقت المتابعة القادمة</label>
                        <input type="datetime-local" name="next_follow_up_date" onclick="this.showPicker()"
                            class="w-full text-sm border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-3 bg-slate-50 dark:bg-slate-800/80 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 outline-none transition-all text-right cursor-pointer">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">ملاحظات
                            داخلية إضافية</label>
                        <textarea name="notes" rows="3" placeholder="اكتب هنا أي تفاصيل دارت في المحادثة لمساعدتك لاحقاً..."
                            class="w-full text-sm border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-3 bg-slate-50 dark:bg-slate-800/80 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 outline-none transition-all resize-none"></textarea>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 flex flex-row-reverse gap-2">
                        <button type="submit"
                            class="px-4 py-2.5 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-xs font-bold shadow-md shadow-violet-200 dark:shadow-none transition-all">
                            حفظ وإضافة للـ Log
                        </button>
                        <button type="button" @click="openModal = false"
                            class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>