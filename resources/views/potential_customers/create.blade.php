{{-- resources/views/potential-customers/create.blade.php --}}

<x-app-layout>
    <div dir="rtl" class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8 text-right">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-950 dark:text-white tracking-tight">
                    إضافة عميل محتمل
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    إنشاء وحفظ ملف تعريف جديد في خطة العملاء الخاصة بك.
                </p>
            </div>

            <a href="{{ route('potential-customers.index') }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700/50 transition-all duration-200">
                <span>رجوع</span>
                <svg class="w-4 h-4 ms-2 transform rtl:-scale-x-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

        <!-- كارد الفورم -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200/80 dark:border-gray-800 overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                    تفاصيل العميل الجديد
                </h3>
            </div>

            <form action="{{ route('potential-customers.store') }}" 
                  method="POST" 
                  x-data="{ 
                      isSubmitting: false,
                      phone: '{{ old('phone') }}',
                      selectedCountry: '+20',
                      phoneError: '',
                      countries: {
                          '+20':  { name: '🇪🇬 مصر',       length: 10, placeholder: '100 123 4567', pattern: '^1[0125][0-9]{8}$' },
                          '+966': { name: '🇸🇦 السعودية',  length: 9,  placeholder: '50 123 4567',  pattern: '^5[013456789][0-9]{7}$' },
                          '+971': { name: '🇦🇪 الإمارات',  length: 9,  placeholder: '50 123 4567',  pattern: '^5[024568][0-9]{7}$' },
                          '+965': { name: '🇰🇼 الكويت',    length: 8,  placeholder: '5123 4567',    pattern: '^[569][0-9]{7}$' },
                          '+974': { name: '🇶🇦 قطر',       length: 8,  placeholder: '3323 4567',    pattern: '^[3567][0-9]{7}$' },
                          '+962': { name: '🇯🇴 الأردن',    length: 9,  placeholder: '79 123 4567',  pattern: '^7[789][0-9]{7}$' }
                      },
                      validateAndFormatPhone() {
                          this.phone = this.phone.replace(/\D/g, '');
                          let config = this.countries[this.selectedCountry];
                          
                          if (!this.phone) {
                              this.phoneError = 'رقم الهاتف مطلوب.';
                              return;
                          }
                          if (this.phone.length !== config.length) {
                              this.phoneError = `رقم الهاتف في هذه الدولة يجب أن يكون ${config.length} أرقام.`;
                              return;
                          }
                          
                          let regex = new RegExp(config.pattern);
                          if (!regex.test(this.phone)) {
                              this.phoneError = 'صيغة رقم الهاتف غير صحيحة.';
                              return;
                          }
                          this.phoneError = '';
                      }
                  }"
                  @submit="validateAndFormatPhone(); if(phoneError) { $event.preventDefault(); } else { isSubmitting = true; }"
                  class="p-6 space-y-6">
                @csrf

                <!-- اسم العميل -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">
                        اسم العميل <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
                           placeholder="مثال: أحمد محمد"
                           required
                           class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-150 @error('name') border-red-500 focus:ring-red-500/20 focus:border-red-500 @enderror">

                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center justify-start">
                            <svg class="w-4 h-4 me-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- رقم الهاتف ومفتاح الدولة -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">
                        رقم الهاتف <span class="text-red-500">*</span>
                    </label>
                    
                    <div dir="ltr" class="flex rounded-xl shadow-sm border bg-white dark:bg-gray-800 focus-within:ring-2 transition-all duration-150 overflow-hidden"
                         :class="phoneError ? 'border-red-500 focus-within:ring-red-500/20 focus-within:border-red-500' : 'border-gray-300 dark:border-gray-700 focus-within:ring-blue-500/20 focus-within:border-blue-500'">
                        
                        <div class="relative flex-shrink-0 border-l border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                            <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-400">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <select name="country_code"
                                    id="country_code"
                                    x-model="selectedCountry"
                                    @change="phone = ''; phoneError = '';"
                                    style="background-image: none;"
                                    class="h-full appearance-none bg-transparent py-0 pl-8 pr-3 text-sm text-gray-700 dark:text-gray-300 focus:ring-0 focus:border-transparent cursor-pointer">
                                <template x-for="(info, code) in countries" :key="code">
                                    <option :value="code" x-text="info.name + ' (' + code + ')'"></option>
                                </template>
                            </select>
                        </div>

                        <input type="text"
                               name="phone"
                               id="phone"
                               x-model="phone"
                               @input.debounce.800ms="validateAndFormatPhone"
                               @blur="validateAndFormatPhone"
                               :placeholder="countries[selectedCountry].placeholder"
                               required
                               dir="ltr"
                               class="w-full border-0 bg-transparent py-2.5 px-4 text-left text-gray-900 dark:text-white focus:ring-0 placeholder-gray-400 dark:placeholder-gray-500">
                    </div>

                    <div x-show="phoneError" x-transition class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center justify-start" style="display: none;">
                        <svg class="w-4 h-4 me-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span x-text="phoneError"></span>
                    </div>

                    @error('phone')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center justify-start">
                            <svg class="w-4 h-4 me-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- مصدر العميل (تم التعديل ليعمل بالـ Enum ديناميكيًا) -->
                <div>
                    <label for="source" class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">
                        مصدر العميل
                    </label>
                    <div class="relative">
                        <select name="source" required
                                id="source"
                                style="background-image: none;"
                                class="w-full appearance-none rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-150 @error('source') border-red-500 focus:ring-red-500/20 focus:border-red-500 @enderror">
                            
                            <option value="" disabled selected>اختر المصدر</option>
                            @foreach(\App\Enums\PotentialCustomerSource::cases() as $source)
                                <option value="{{ $source->value }}" {{ old('source') == $source->value ? 'selected' : '' }}>
                                    {{ $source->label() }} ({{ $source->value }})
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @error('source')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center justify-start">
                            <svg class="w-4 h-4 me-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- أزرار التحكم -->
                <div class="flex items-center justify-end gap-3 pt-6 mt-4 border-t border-gray-100 dark:border-gray-800">
                    <a href="{{ route('potential-customers.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700/50 transition shadow-sm">
                        إلغاء
                    </a>

                    <button type="submit"
                            :disabled="isSubmitting"
                            :class="isSubmitting ? 'opacity-70 cursor-not-allowed' : ''"
                            class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-xl shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        <svg x-show="isSubmitting" class="animate-spin -ml-1 ms-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" style="display: none;">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isSubmitting ? 'جاري الحفظ...' : 'حفظ العميل'">حفظ العميل</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>