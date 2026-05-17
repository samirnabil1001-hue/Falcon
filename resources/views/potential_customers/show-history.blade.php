<x-app-layout>
    <!-- تم تغيير الـ max-w إلى 7xl ليأخذ الشاشة كاملة بالتناسق مع نظام لوحة التحكم -->
    <div class="py-12" x-data="{ openDetailPopup: false, selectedLog: {} }">
        <div class=" mx-auto sm:px-6 lg:px-8">
            
            <!-- الهيدر وأزرار الرجوع -->
            <div class="flex justify-between items-center mb-8" dir="rtl">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-slate-200 leading-tight">
                        السجل التاريخي للمتابعات
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">
                        الجدول الزمني الكامل لإجراءات ومكالمات العميل: <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $customer->name }}</span>
                    </p>
                </div>
                <a href="{{ route('potential-customers.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-lg text-xs font-semibold hover:bg-gray-200 transition-all">
                    عودة لقائمة العملاء
                </a>
            </div>

            <!-- حاوية الـ Timeline الشاملة للشاشة -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 dark:border-slate-700 p-6 sm:p-8" dir="rtl">
                
                @if($customer->followUps->isEmpty())
                    <div class="text-center py-12 text-gray-400 text-sm">
                        لا توجد أي سجلات متابعة مسجلة لهذا العميل حتى الآن.
                    </div>
                @else
                    <!-- 👈 الخط الرأسي تم نقله لأقصى اليمين تماماً (mr-2) ليبدأ من أول الشاشة -->
                    <div class="relative border-r-2 border-gray-200 dark:border-slate-700 mr-2 space-y-6 pb-4">
                        
                        @foreach($customer->followUps as $log)
                            <!-- عنصر المتابعة -->
                            <div @click="selectedLog = {{ json_encode($log) }}; openDetailPopup = true" 
                                 class="relative pr-8 group cursor-pointer transition-all">
                                
                                <!-- النقطة الملونة مثبتة بدقة على الخط الجانبي الأيمن -->
                                <div class="absolute -right-[7px] top-2 w-3.5 h-3.5 rounded-full border-2 border-white dark:border-slate-800 shadow-sm transition-all duration-300 group-hover:scale-125
                                    {{ $log->status === 'CONFIRMED' ? 'bg-emerald-500 group-hover:bg-emerald-600' : ($log->status === 'CANCELLED' ? 'bg-rose-500 group-hover:bg-rose-600' : 'bg-amber-500 group-hover:bg-amber-600') }}">
                                </div>

                                <!-- الكارت ممتد الآن ليعرض التفاصيل بكامل عرض الشاشة -->
                                <div class="bg-slate-50 dark:bg-slate-900/40 border border-gray-100 dark:border-slate-700/60 rounded-xl p-5 transition-all duration-200 hover:shadow-md group-hover:bg-indigo-50/40 dark:group-hover:bg-slate-700/30">
                                    
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-3">
                                        <!-- بادج الحالة والسبب الرئيسي -->
                                        <div class="flex items-center gap-3">
                                            <span class="px-3 py-1 rounded-md text-xs font-bold text-white shadow-sm whitespace-nowrap
                                                {{ $log->status === 'CONFIRMED' ? 'bg-emerald-500' : ($log->status === 'CANCELLED' ? 'bg-rose-500' : 'bg-amber-500') }}">
                                                {{ $log->status }}
                                            </span>
                                            <h3 class="text-base font-semibold text-gray-800 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                                {{ $log->reason ?? 'بدون سبب محدد' }}
                                            </h3>
                                        </div>
                                        
                                        <!-- التوقيت والتاريخ مفرود على اليسار في الشاشات الكبيرة -->
                                        <div class="text-xs text-gray-400 font-medium flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $log->created_at->format('Y-m-d - h:i A') }}
                                        </div>
                                    </div>

                                    <!-- عرض الملاحظات كاملة وبمساحة مريحة بما أن الشاشة أصبحت واسعة -->
                                    @if($log->notes)
                                        <div class="text-sm text-gray-600 dark:text-slate-300 bg-white dark:bg-slate-800/80 p-3 rounded-lg border border-gray-100 dark:border-slate-700/50 mt-2">
                                            <span class="text-[11px] font-bold text-gray-400 dark:text-slate-500 block mb-1">الملاحظات المسجلة:</span>
                                            <p class="whitespace-pre-line">{{ $log->notes }}</p>
                                        </div>
                                    @endif

                                    <!-- نص إرشادي تفاعلي في أسفل اليسار -->
                                    <div class="mt-3 flex justify-end items-center text-xs text-indigo-500 font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                                        انقر لعرض نافذة التفاصيل والتواريخ التذكيرية القادمة ←
                                    </div>

                                </div>
                            </div>
                        @endforeach

                    </div>
                @endif

            </div>
        </div>

        <!-- الـ Popup المنبثق لبيانات السجل المحدد -->
        <template x-teleport="body">
            <div x-show="openDetailPopup" class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen p-4 text-center">
                    
                    <div @click="openDetailPopup = false" class="fixed inset-0 bg-slate-950/50 backdrop-blur-sm transition-opacity"></div>

                    <div class="inline-block bg-white dark:bg-slate-800 rounded-xl text-right overflow-hidden shadow-2xl transform transition-all max-w-md w-full p-6 border border-gray-200 dark:border-slate-700 relative" dir="rtl">
                        
                        <button type="button" @click="openDetailPopup = false" class="absolute top-4 left-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <h4 class="text-base font-bold text-gray-900 dark:text-slate-100 mb-4 pb-2 border-b border-gray-100 dark:border-slate-700">
                            تفاصيل سجل المتابعة الكاملة
                        </h4>

                        <div class="space-y-4 text-sm">
                            <div>
                                <span class="text-xs text-gray-500 dark:text-slate-400 block mb-1">الحالة:</span>
                                <span class="text-xs font-bold px-2 py-0.5 rounded text-white inline-block shadow-sm" 
                                      :class="selectedLog.status === 'CONFIRMED' ? 'bg-emerald-500' : (selectedLog.status === 'CANCELLED' ? 'bg-rose-500' : 'bg-amber-500')"
                                      x-text="selectedLog.status"></span>
                            </div>

                            <div>
                                <span class="block text-xs text-gray-500 dark:text-slate-400 mb-1">السبب أو نتيجة الإجراء:</span>
                                <p class="text-gray-800 dark:text-slate-200 font-medium bg-gray-50 dark:bg-slate-900/30 p-2.5 rounded-lg border border-gray-100 dark:border-slate-700" x-text="selectedLog.reason || 'لم يتم تحديد سبب'"></p>
                            </div>

                            <div>
                                <span class="block text-xs text-gray-500 dark:text-slate-400 mb-1">الملاحظات الداخلية التفصيلية للـ Agent:</span>
                                <p class="text-gray-700 dark:text-slate-300 text-xs whitespace-pre-line bg-gray-50 dark:bg-slate-900/30 p-2.5 rounded-lg border border-gray-100 dark:border-slate-700 max-h-[150px] overflow-y-auto" x-text="selectedLog.notes || 'لا توجد ملاحظات إضافية مكتوبة لهذا السجل.'"></p>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-[11px] pt-3 border-t border-gray-100 dark:border-slate-700">
                                <div>
                                    <span class="text-gray-400 block">وقت وتاريخ الاتصال:</span>
                                    <span class="text-gray-600 dark:text-slate-300 font-medium" x-text="selectedLog.created_at ? new Date(selectedLog.created_at).toLocaleString('ar-EG') : ''"></span>
                                </div>
                                <div>
                                    <span class="text-gray-400 block">المتابعة التذكيرية القادمة:</span>
                                    <span class="text-indigo-600 dark:text-indigo-400 font-medium" x-text="selectedLog.next_follow_up_at ? new Date(selectedLog.next_follow_up_at).toLocaleString('ar-EG') : 'لا يوجد موعد تذكيري'"></span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="button" @click="openDetailPopup = false" class="px-4 py-2 w-full bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-lg text-xs font-semibold hover:bg-gray-200 transition-all">
                                إغلاق تفاصيل السجل
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>