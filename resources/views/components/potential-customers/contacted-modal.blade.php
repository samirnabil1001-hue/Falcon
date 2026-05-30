<div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title"
    role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-slate-900 dark:bg-opacity-80 transition-opacity"
            aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="showModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full w-full relative"
            dir="rtl">

            <button type="button" @click="showModal = false"
                class="absolute top-4 left-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <form action="{{ $route }}" method="POST" class="m-0">
                @csrf
                @method('PATCH')

                <input type="hidden" name="status" value="{{ \App\Enums\PotentialCustomerStatus::CONTACTED->value }}">

                <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-right w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-slate-100"
                                id="modal-title">
                                تحديث حالة العميل وتفاصيل التواصل
                            </h3>

                            <div class="mt-4 space-y-4 text-sm text-gray-500 dark:text-slate-400">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">السبب
                                        (Reason)</label>
                                    <div class="relative w-full">
                                        <select name="reason" required
                                            class="w-full text-sm border border-gray-300 dark:border-slate-600 rounded-lg pr-3 pl-10 py-2 bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 appearance-none bg-none text-right [appearance:none] [&::-ms-expand]:hidden">
                                            <option value="" disabled selected>اختر السبب...</option>
                                            {{-- تم التحديث هنا لاستدعاء الـ Enum الجديد الخاص بالمتابعة --}}
                                            @foreach (\App\Enums\FollowUpReason::cases() as $reason)
                                                <option value="{{ $reason->value }}">{{ $reason->label() }}</option>
                                            @endforeach
                                        </select>

                                        <!-- السهم المخصص والمثبت في جهة اليسار فقط -->
                                        <div
                                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500 dark:text-slate-400">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">تاريخ
                                        ووقت المتابعة القادم</label>
                                    <input type="datetime-local" name="next_follow_up_date" onclick="this.showPicker()"
                                        class="w-full flex justify-end text-sm border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">ملاحظات</label>
                                    <textarea name="notes" rows="3" placeholder="أضف أي تفاصيل أخرى هنا..."
                                        class="w-full text-sm border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-slate-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button type="submit"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm transition-colors">
                        حفظ وتحديث
                    </button>
                    <button type="button" @click="showModal = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-slate-500 shadow-sm px-4 py-2 bg-white dark:bg-slate-800 text-base font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
