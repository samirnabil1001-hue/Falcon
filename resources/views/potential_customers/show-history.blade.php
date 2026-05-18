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
                    <!-- الخط الرأسي تم نقله لأقصى اليمين تماماً (mr-2) ليبدأ من أول الشاشة -->
                    <div class="relative border-r-2 border-gray-200 dark:border-slate-700 mr-2 space-y-6 pb-4">
                        
                        @foreach($customer->followUps as $log)
                            <!-- عنصر المتابعة -->
                            <div @click="selectedLog = {{ json_encode([
                                     'status' => $log->status->value, 
                                     'status_label' => $log->status->label(),
                                     'reason' => $log->reason?->label() ?? 'بدون سبب محدد', 
                                     'notes' => $log->notes,
                                     'created_at' => $log->created_at,
                                     'next_follow_up_at' => $log->next_follow_up_at
                                 ]) }}; openDetailPopup = true" 
                                 class="relative pr-8 group cursor-pointer transition-all">
                                
                                <!-- النقطة الملونة مثبتة بدقة على الخط الجانبي الأيمن -->
                                <div class="absolute -right-[7px] top-2 w-3.5 h-3.5 rounded-full border-2 border-white dark:border-slate-800 shadow-sm transition-all duration-300 group-hover:scale-125
                                    {{ $log->status->value === 'confirmed' ? 'bg-emerald-600 dark:bg-emerald-500' : ($log->status->value === 'cancelled' ? 'bg-rose-600 dark:bg-rose-500' : 'bg-amber-500 dark:bg-amber-400') }}">
                                </div>

                                <!-- 🎨 تخصيص لون خلفية وحواف الكارت بالكامل بناءً على الحالة -->
                                <div class="rounded-xl p-5 border transition-all duration-200 hover:shadow-lg
                                    {{ $log->status->value === 'confirmed' ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-500/30' : 
                                       ($log->status->value === 'cancelled' ? 'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-500/30' : 
                                        'bg-amber-50 dark:bg-amber-950/30 border-amber-200 dark:border-amber-500/20') }}">
                                    
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-3">
                                        <!-- بادج الحالة والسبب الرئيسي -->
                                        <div class="flex items-center gap-3">
                                            <!-- بادج صلب ومتناسق مع لون الكارت الخلفي الجديد -->
                                            <span class="px-2.5 py-1 rounded-md text-xs font-bold text-white shadow-sm whitespace-nowrap
                                                {{ $log->status->value === 'confirmed' ? 'bg-emerald-600 dark:bg-emerald-500' : 
                                                   ($log->status->value === 'cancelled' ? 'bg-rose-600 dark:bg-rose-500' : 
                                                    'bg-amber-500 dark:bg-amber-600') }}">
                                                {{ $log->status->label() }}
                                            </span>
                                            <!-- ألوان الخطوط تتناسق مع الكروت الجديدة لضمان القراءة الفائقة -->
                                            <h3 class="text-base font-bold transition-colors
                                                {{ $log->status->value === 'confirmed' ? 'text-emerald-900 dark:text-emerald-200' : 
                                                   ($log->status->value === 'cancelled' ? 'text-rose-900 dark:text-rose-200' : 
                                                    'text-amber-900 dark:text-amber-200') }}">
                                                {{ $log->reason?->label() ?? 'بدون سبب محدد' }}
                                            </h3>
                                        </div>
                                        
                                        <!-- التوقيت والتاريخ مفرود على اليسار في الشاشات الكبيرة -->
                                        <div class="text-xs font-medium flex items-center gap-1
                                            {{ $log->status->value === 'confirmed' ? 'text-emerald-600 dark:text-emerald-400/70' : 
                                               ($log->status->value === 'cancelled' ? 'text-rose-600 dark:text-rose-400/70' : 
                                                'text-amber-600 dark:text-amber-400/70') }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $log->created_at->format('Y-m-d - h:i A') }}
                                        </div>
                                    </div>

                                    <!-- عرض الملاحظات كاملة وبمساحة داخلية مريحة وبنفس روح لون الكارت الشامل -->
                                    @if($log->notes)
                                        <div class="text-sm p-3 rounded-lg border mt-2
                                            {{ $log->status->value === 'confirmed' ? 'text-emerald-800 dark:text-emerald-300 bg-white/80 dark:bg-slate-900/60 border-emerald-100 dark:border-emerald-500/20' : 
                                               ($log->status->value === 'cancelled' ? 'text-rose-800 dark:text-rose-300 bg-white/80 dark:bg-slate-900/60 border-rose-100 dark:border-rose-500/20' : 
                                                'text-amber-800 dark:text-amber-300 bg-white/80 dark:bg-slate-900/60 border-amber-100 dark:border-amber-500/10') }}">
                                            <span class="text-[11px] font-bold block mb-1 opacity-70">الملاحظات المسجلة:</span>
                                            <p class="whitespace-pre-line">{{ $log->notes }}</p>
                                        </div>
                                    @endif

                                    <!-- نص إرشادي تفاعلي في أسفل اليسار يتلون حسب نوع الكارت -->
                                    <div class="mt-3 flex justify-end items-center text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity
                                        {{ $log->status->value === 'confirmed' ? 'text-emerald-600 dark:text-emerald-400' : 
                                           ($log->status->value === 'cancelled' ? 'text-rose-600 dark:text-rose-400' : 
                                            'text-amber-600 dark:text-amber-400') }}">
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
            <div x-show="openDetailPopup" class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;" x-transition.opacity>
                <div class="flex items-center justify-center min-h-screen p-4 text-center">
                    
                    <div @click="openDetailPopup = false" class="fixed inset-0 bg-slate-950/50 backdrop-blur-sm transition-opacity"></div>

                    <!-- تم تلوين خلفية البوب اب نفسه بشكل تفاعلي ذكي بناءً على الكارت المختار ليطابقه تماماً -->
                    <div class="inline-block rounded-xl text-right overflow-hidden shadow-2xl transform transition-all max-w-md w-full p-6 border relative" dir="rtl"
                         :class="{
                             'bg-emerald-50 dark:bg-slate-800 border-emerald-200 dark:border-emerald-500/30': selectedLog.status === 'confirmed',
                             'bg-rose-50 dark:bg-slate-800 border-rose-200 dark:border-rose-500/30': selectedLog.status === 'cancelled',
                             'bg-amber-50 dark:bg-slate-800 border-amber-200 dark:border-amber-500/20': selectedLog.status !== 'confirmed' && selectedLog.status !== 'cancelled'
                         }">
                        
                        <button type="button" @click="openDetailPopup = false" class="absolute top-4 left-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <h4 class="text-base font-bold mb-4 pb-2 border-b dark:text-slate-100"
                            :class="{
                                'text-emerald-900 border-emerald-200 dark:border-slate-700': selectedLog.status === 'confirmed',
                                'text-rose-900 border-rose-200 dark:border-slate-700': selectedLog.status === 'cancelled',
                                'text-amber-900 border-amber-200 dark:border-slate-700': selectedLog.status !== 'confirmed' && selectedLog.status !== 'cancelled'
                            }">
                            تفاصيل سجل المتابعة الكاملة
                        </h4>

                        <div class="space-y-4 text-sm">
                            <div>
                                <span class="text-xs block mb-1 opacity-70" :class="selectedLog.status === 'confirmed' ? 'text-emerald-800 dark:text-slate-400' : (selectedLog.status === 'cancelled' ? 'text-rose-800 dark:text-slate-400' : 'text-amber-800 dark:text-slate-400')">الحالة:</span>
                                <span class="text-xs font-bold px-3 py-1 rounded-md inline-block shadow-sm text-white" 
                                      :class="{
                                          'bg-emerald-600 dark:bg-emerald-500': selectedLog.status === 'confirmed',
                                          'bg-rose-600 dark:bg-rose-500': selectedLog.status === 'cancelled',
                                          'bg-amber-500 dark:bg-amber-600': selectedLog.status !== 'confirmed' && selectedLog.status !== 'cancelled'
                                      }"
                                      x-text="selectedLog.status_label">
                                </span>
                            </div>

                            <div>
                                <span class="block text-xs mb-1 opacity-70" :class="selectedLog.status === 'confirmed' ? 'text-emerald-800 dark:text-slate-400' : (selectedLog.status === 'cancelled' ? 'text-rose-800 dark:text-slate-400' : 'text-amber-800 dark:text-slate-400')">السبب أو نتيجة الإجراء:</span>
                                <p class="font-medium bg-white/80 dark:bg-slate-900/50 p-2.5 rounded-lg border text-gray-800 dark:text-slate-200" :class="selectedLog.status === 'confirmed' ? 'border-emerald-100 dark:border-slate-700' : (selectedLog.status === 'cancelled' ? 'border-rose-100 dark:border-slate-700' : 'border-amber-100 dark:border-slate-700')" x-text="selectedLog.reason"></p>
                            </div>

                            <div>
                                <span class="block text-xs mb-1 opacity-70" :class="selectedLog.status === 'confirmed' ? 'text-emerald-800 dark:text-slate-400' : (selectedLog.status === 'cancelled' ? 'text-rose-800 dark:text-slate-400' : 'text-amber-800 dark:text-slate-400')">الملاحظات الداخلية التفصيلية للـ Agent:</span>
                                <p class="text-xs whitespace-pre-line bg-white/80 dark:bg-slate-900/50 p-2.5 rounded-lg border max-h-[150px] overflow-y-auto text-gray-700 dark:text-slate-300" :class="selectedLog.status === 'confirmed' ? 'border-emerald-100 dark:border-slate-700' : (selectedLog.status === 'cancelled' ? 'border-rose-100 dark:border-slate-700' : 'border-amber-100 dark:border-slate-700')" x-text="selectedLog.notes || 'لا توجد ملاحظات إضافية مكتوبة لهذا السجل.'"></p>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-[11px] pt-3 border-t dark:border-slate-700" :class="selectedLog.status === 'confirmed' ? 'border-emerald-200' : (selectedLog.status === 'cancelled' ? 'border-rose-200' : 'border-amber-200')">
                                <div>
                                    <span class="text-gray-400 block">وقت وتاريخ الاتصال:</span>
                                    <span class="font-medium dark:text-slate-300" :class="selectedLog.status === 'confirmed' ? 'text-emerald-900' : (selectedLog.status === 'cancelled' ? 'text-rose-900' : 'text-amber-900')" x-text="selectedLog.created_at ? new Date(selectedLog.created_at).toLocaleString('ar-EG') : ''"></span>
                                </div>
                                <div>
                                    <span class="text-gray-400 block">المتابعة التذكيرية القادمة:</span>
                                    <span class="font-bold dark:text-indigo-400" :class="selectedLog.status === 'confirmed' ? 'text-emerald-700' : (selectedLog.status === 'cancelled' ? 'text-rose-700' : 'text-amber-700')" x-text="selectedLog.next_follow_up_at ? new Date(selectedLog.next_follow_up_at).toLocaleString('ar-EG') : 'لا يوجد موعد تذكيري'"></span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="button" @click="openDetailPopup = false" class="px-4 py-2 w-full bg-white/80 dark:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-lg text-xs font-semibold hover:bg-gray-100 dark:hover:bg-slate-600 transition-all border dark:border-transparent">
                                إغلاق تفاصيل السجل
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>