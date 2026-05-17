<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <!-- الهيدر وزر العودة -->
            <div class="flex justify-between items-center mb-6" dir="rtl">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-slate-200 leading-tight">
                        تعديل بيانات العميل المحتمل
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">
                        أنت تقوم بتعديل بيانات العميل: <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $potentialCustomer->name }}</span>
                    </p>
                </div>
                <a href="{{ route('potential-customers.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-lg text-xs font-semibold hover:bg-gray-200 transition-all">
                    إلغاء وتراجع
                </a>
            </div>

            <!-- كارت الفورم -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 dark:border-slate-700 p-6 sm:p-8" dir="rtl">
                
                <form action="{{ route('potential-customers.update', $potentialCustomer->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT') <!-- 👈 مهمة جداً لتعريف الـ Route بأن الإجراء هو Update -->

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- اسم العميل (Customer Name) -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                اسم العميل <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $potentialCustomer->name) }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-slate-700 dark:bg-slate-900 text-gray-800 dark:text-slate-100 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm"
                                placeholder="اسم العميل الكامل">
                            @error('name')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- رقم الهاتف (Phone) -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                رقم الهاتف <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $potentialCustomer->phone) }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-slate-700 dark:bg-slate-900 text-gray-800 dark:text-slate-100 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm text-left"
                                dir="ltr" placeholder="+20 1xxxxxxxxx">
                            @error('phone')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- مصدر العميل (Source) -->
                    <div>
                        <label for="source" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                            مصدر العميل (Source) <span class="text-rose-500">*</span>
                        </label>
                        <select name="source" id="source" required
                            class="w-full rounded-lg border-gray-300 dark:border-slate-700 dark:bg-slate-900 text-gray-800 dark:text-slate-100 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
                            
                            @php
                                $currentSource = old('source', $potentialCustomer->source);
                            @endphp

                            <option value="Facebook" {{ $currentSource == 'Facebook' ? 'selected' : '' }}>فيسبوك (Facebook)</option>
                            <option value="Instagram" {{ $currentSource == 'Instagram' ? 'selected' : '' }}>إنستغرام (Instagram)</option>
                            <option value="WhatsApp" {{ $currentSource == 'WhatsApp' ? 'selected' : '' }}>واتساب (WhatsApp)</option>
                            <option value="TikTok" {{ $currentSource == 'TikTok' ? 'selected' : '' }}>تيك توك (TikTok)</option>
                            <option value="Google" {{ $currentSource == 'Google' ? 'selected' : '' }}>جوجل / موقع إلكتروني (Google)</option>
                            <option value="Referral" {{ $currentSource == 'Referral' ? 'selected' : '' }}>ترشيح من عميل (Referral)</option>
                            <option value="Other" {{ $currentSource == 'Other' ? 'selected' : '' }}>أخرى (Other)</option>
                        </select>
                        @error('source')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- أزرار التحكم في أسفل الفورم -->
                    <div class="pt-4 border-t border-gray-100 dark:border-slate-700 flex justify-end gap-3">
                        <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-semibold transition-all shadow-sm">
                            تحديث وتعديل البيانات
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>