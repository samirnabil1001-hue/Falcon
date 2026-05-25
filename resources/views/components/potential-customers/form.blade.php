{{-- resources/views/components/potential-customers/form.blade.php --}}

@props(['customer' => null])

@php
    $isEdit = (bool) $customer;
    $actionUrl = $isEdit ? route('potential-customers.update', $customer->id) : route('potential-customers.store');
@endphp

<form action="{{ $actionUrl }}" 
      method="POST" 
      x-data="{ 
          isSubmitting: false,
          phone: '{{ old('phone', $customer?->phone) }}',
          selectedCountry: '{{ old('country_code', $customer?->country_code ?? '+20') }}', {{-- 👈 تثبيت كود الدولة المخزن --}}
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
      x-init="if(phone) validateAndFormatPhone();"
      @submit="validateAndFormatPhone(); if(phoneError) { $event.preventDefault(); } else { isSubmitting = true; }"
      class="space-y-6">
    
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">
                اسم العميل <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   name="name"
                   id="name"
                   value="{{ old('name', $customer?->name) }}"
                   placeholder="مثال: أحمد محمد"
                   required
                   class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-150 @error('name') border-red-500 focus:ring-red-500/20 focus:border-red-500 @enderror">

            @error('name')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center justify-start">{{ $message }}</p>
            @enderror
        </div>

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
                    
                    {{-- 💡 تعديل الربط هنا باستخدام x-model للتأكد من إرسال القيمة الصحيحة للـ Controller --}}
                    <select name="country_code"
                            id="country_code"
                            x-model="selectedCountry"
                            @change="phone = ''; phoneError = '';"
                            style="background-image: none;"
                            class="h-full appearance-none bg-transparent py-0 pl-8 pr-3 text-sm text-gray-700 dark:text-gray-300 focus:ring-0 focus:border-transparent cursor-pointer">
                        <template x-for="(info, code) in countries" :key="code">
                            <option :value="code" :selected="selectedCountry === code" x-text="info.name + ' (' + code + ')'"></option>
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
                <span x-text="phoneError"></span>
            </div>

            @error('phone')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center justify-start">{{ $message }}</p>
            @enderror
            @error('country_code')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center justify-start">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="source" class="block text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">
            مصدر العميل <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <select name="source" required
                    id="source"
                    style="background-image: none;"
                    class="w-full appearance-none rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-150 @error('source') border-red-500 focus:ring-red-500/20 focus:border-red-500 @enderror">
                
                <option value="" disabled {{ !old('source', $customer?->source) ? 'selected' : '' }}>اختر المصدر</option>
                
                @php
                    $currentSource = old('source', $customer?->source instanceof \App\Enums\PotentialCustomerSource ? $customer->source->value : $customer?->source);
                @endphp

                @foreach(\App\Enums\PotentialCustomerSource::cases() as $source)
                    <option value="{{ $source->value }}" {{ $currentSource === $source->value ? 'selected' : '' }}>
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
            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center justify-start">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-end gap-3 pt-6 mt-4 border-t border-gray-100 dark:border-gray-800">
        <a href="{{ route('potential-customers.index') }}"
           class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700/50 transition shadow-sm">
            إلغاء
        </a>

        <button type="submit"
                :disabled="isSubmitting"
                :class="isSubmitting ? 'opacity-70 cursor-not-allowed' : ''"
                class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-white {{ $isEdit ? 'bg-amber-500 hover:bg-amber-600' : 'bg-blue-600 hover:bg-blue-700' }} rounded-xl shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
            <svg x-show="isSubmitting" class="animate-spin -ml-1 ms-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" style="display: none;">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span x-text="isSubmitting ? 'جاري الحفظ...' : '{{ $isEdit ? 'تحديث وتعديل البيانات' : 'حفظ العميل' }}'"></span>
        </button>
    </div>
</form>