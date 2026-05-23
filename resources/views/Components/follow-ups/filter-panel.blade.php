@props(['search', 'status', 'sortBy', 'sortOrder', 'users'])

<form id="filter-form" action="{{ url()->current() }}" method="GET"
    class="mb-0 bg-slate-50/80 dark:bg-slate-800/40 rounded-xl p-4 border border-slate-100 dark:border-slate-800">

    <input type="hidden" name="sort_by" value="{{ $sortBy }}">
    <input type="hidden" name="sort_order" value="{{ $sortOrder }}">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-center">
        
        <!-- Search Field -->
        <div class="relative">
            <input type="text" name="search" value="{{ $search }}" placeholder="بحث بالاسم أو رقم الهاتف..."
                class="w-full text-sm rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <!-- Status Filter -->
        <div>
            <select name="status" onchange="this.form.submit()"
                class="w-full text-sm rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all cursor-pointer py-2.5">
                <option value="">جميع الحالات</option>
                @foreach (App\Enums\PotentialCustomerStatus::cases() as $statusOption)
                    <option value="{{ $statusOption->value }}" {{ $status == $statusOption->value ? 'selected' : '' }}>
                        {{ $statusOption->label() }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Custom User Filter -->
        <div class="relative" id="custom-dropdown-container">
            <input type="hidden" name="user_id" id="hidden-user-id" value="{{ request('user_id') }}">
            
            <button type="button" id="dropdown-btn" onclick="toggleDropdown()"
                class="w-full text-right flex justify-between items-center text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all cursor-pointer py-2.5 px-3 disabled:opacity-50 disabled:cursor-not-allowed">
                <span id="dropdown-label">
                    @if(request('user_id') && $users->firstWhere('id', request('user_id')))
                        {{ $users->firstWhere('id', request('user_id'))->name }}
                    @else
                        جميع الموظفين
                    @endif
                </span>
                <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" id="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div id="dropdown-menu" class="hidden absolute z-50 mt-1 w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-lg overflow-hidden">
                <!-- خانة البحث الداخلية -->
                <div class="p-2 border-b border-slate-100 dark:border-slate-700">
                    <input type="text" id="dropdown-search" oninput="filterDropdownOptions()" placeholder="ابحث عن موظف (أحمد، عبدالله...)"
                        class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 px-3 py-1.5 focus:outline-none focus:border-violet-500">
                </div>
                <!-- خيارات الموظفين مع الـ Scroll -->
                <div id="options-list" class="max-h-[190px] overflow-y-auto text-sm text-slate-700 dark:text-slate-200">
                    <div onclick="selectUser('', 'جميع الموظفين')" class="option-item px-3 py-2 hover:bg-violet-50 dark:hover:bg-violet-950/40 cursor-pointer transition-colors" data-name="">
                        جميع الموظفين
                    </div>
                    @if($users)
                        @foreach ($users as $user)
                            @if($user->id !== auth()->id())
                                <div onclick="selectUser('{{ $user->id }}', '{{ $user->name }}')" 
                                     class="option-item px-3 py-2 hover:bg-violet-50 dark:hover:bg-violet-950/40 cursor-pointer transition-colors {{ request('user_id') == $user->id ? 'bg-violet-500/10 font-medium text-violet-600' : '' }}" 
                                     data-name="{{ $user->name }}">
                                    {{ $user->name }}
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Checkbox "عملائي" السريع -->
        <div class="flex items-center justify-start px-2">
            <label class="relative flex items-center cursor-pointer select-none text-sm font-medium text-slate-700 dark:text-slate-300">
                <input type="checkbox" id="my-clients-checkbox" name="my_clients" value="1" 
                    {{ request('my_clients') == '1' ? 'checked' : '' }}
                    onchange="handleCheckboxChange(this)"
                    class="w-4 h-4 text-violet-600 bg-white border-slate-300 rounded-md focus:ring-violet-500 dark:focus:ring-violet-600 dark:ring-offset-slate-800 focus:ring-2 dark:bg-slate-700 dark:border-slate-600 transition-all cursor-pointer ml-2">
                <span>عملائي فقط</span>
            </label>
        </div>

        <!-- Actions -->
        <div class="flex gap-2 justify-end items-center lg:col-span-1 md:col-span-2">
            @if ($search || $status || request('user_id') || request('my_clients'))
                <a href="{{ route('customer-follow-ups.index') }}"
                    class="bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-950/40 text-rose-600 dark:text-rose-400 text-xs font-semibold py-2.5 px-4 rounded-xl flex items-center justify-center transition-colors">
                    إلغاء الفلترة 
                </a>
            @endif

            <button type="submit"
                class="w-full sm:w-auto bg-gray-200 hover:bg-indigo-600 hover:text-white dark:bg-slate-700 dark:text-gray-200 dark:hover:bg-indigo-600 text-gray-700 text-xs font-semibold py-2.5 px-5 rounded-xl transition-all shadow-sm">
                تطبيق الفلترة
            </button>
        </div>
    </div>
</form>

<script>
    // دالة ذكية لتنظيف وتوحيد الحروف العربية المتشابهة لتسهيل البحث الفازي (Fuzzy Search)
    function normalizeArabic(text) {
        if (!text) return '';
        return text.trim().toLowerCase()
            .replace(/[أإآا]/g, 'ا')  // توحيد كل أشكال الألف إلى ألف عادية
            .replace(/[ةه]/g, 'ه')    // توحيد التاء المربوطة والهاء
            .replace(/ى/g, 'ي')       // توحيد الألف المقصورة والياء
            .replace(/[\u064B-\u0652]/g, ''); // إزالة التشكيل تماماً إن وُجد
    }

    // تصفية الموظفين بناءً على الحروف المتشابهة والمتقاربة
    function filterDropdownOptions() {
        const rawInput = document.getElementById('dropdown-search').value;
        const searchInput = normalizeArabic(rawInput); // النص المدخل بعد التنظيف
        const options = document.querySelectorAll('.option-item');
        
        options.forEach(option => {
            const rawName = option.getAttribute('data-name');
            const normalizedName = normalizeArabic(rawName); // اسم الموظف بعد التنظيف
            
            // التحقق مما إذا كان اسم الموظف يحتوي على الحروف المتتابعة المدخلة
            if (normalizedName.includes(searchInput) || rawName === "") {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
    }

    function toggleDropdown() {
        const checkbox = document.getElementById('my-clients-checkbox');
        if (checkbox.checked) return;

        const menu = document.getElementById('dropdown-menu');
        const arrow = document.getElementById('dropdown-arrow');
        menu.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
        
        if (!menu.classList.contains('hidden')) {
            document.getElementById('dropdown-search').focus();
        }
    }

    function selectUser(id, name) {
        document.getElementById('hidden-user-id').value = id;
        document.getElementById('dropdown-label').innerText = name;
        document.getElementById('dropdown-menu').classList.add('hidden');
        document.getElementById('dropdown-arrow').classList.remove('rotate-180');
        document.getElementById('filter-form').submit();
    }

    function handleCheckboxChange(checkbox) {
        const dropdownBtn = document.getElementById('dropdown-btn');
        if (checkbox.checked) {
            document.getElementById('hidden-user-id').value = '';
            document.getElementById('dropdown-label').innerText = 'جميع الموظفين';
            dropdownBtn.disabled = true;
        } else {
            dropdownBtn.disabled = false;
        }
        checkbox.form.submit();
    }

    document.addEventListener('click', function(event) {
        const container = document.getElementById('custom-dropdown-container');
        const menu = document.getElementById('dropdown-menu');
        const arrow = document.getElementById('dropdown-arrow');
        
        if (container && !container.contains(event.target)) {
            menu.classList.add('hidden');
            arrow.classList.remove('rotate-180');
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        if (document.getElementById('my-clients-checkbox').checked) {
            document.getElementById('dropdown-btn').disabled = true;
        }
    });
</script>