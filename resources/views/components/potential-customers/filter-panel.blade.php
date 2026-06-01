@props(['search', 'source', 'status', 'sortBy', 'sortOrder', 'dateFrom' => '', 'dateTo' => ''])

<form id="filter-form" action="{{ url()->current() }}" method="GET" onsubmit="submitCleanForm(event)"
    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-5 bg-gray-50 dark:bg-slate-800/50 p-4 rounded-2xl border border-gray-100 dark:border-slate-800">

    <input type="hidden" name="sort_by" value="{{ $sortBy }}">
    <input type="hidden" name="sort_order" value="{{ $sortOrder }}">

    <div class="relative sm:col-span-2 lg:col-span-1">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or phone..."
            class="w-full text-xs rounded-xl border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-gray-100 pl-9 pr-4 py-2.5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">

        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-slate-500">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    <x-date-range-picker :date-from="$dateFrom" :date-to="$dateTo" class="lg:col-span-2" />

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">

        @if (auth()->user()->isCEO())
            <div>
                <x-user-filter-dropdown :users="$users" />
            </div>
        @else
            <div class="relative w-full">
                <select name="source" onchange="triggerFormSubmit()"
                    class="appearance-none [appearance:none] [-webkit-appearance:none] [-moz-appearance:none] bg-none w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all cursor-pointer py-2.5 pr-3 pl-9">

                    <option value="">كل المصادر</option>

                    @foreach (App\Enums\PotentialCustomerSource::cases() as $sourceOption)
                        <option value="{{ $sourceOption->value }}" {{ $source == $sourceOption->value ? 'selected' : '' }}>
                            {{ $sourceOption->label() }}
                        </option>
                    @endforeach
                </select>

                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-slate-500">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        @endif

        <div class="relative w-full">
            <select name="status" onchange="triggerFormSubmit()"
                class="appearance-none [appearance:none] [-webkit-appearance:none] [-moz-appearance:none] bg-none w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all cursor-pointer py-2.5 pr-3 pl-9">

                <option value="">كل الحالات</option>

                @foreach (App\Enums\PotentialCustomerStatus::cases() as $statusOption)
                    <option value="{{ $statusOption->value }}" {{ $status == $statusOption->value ? 'selected' : '' }}>
                        {{ $statusOption->label() }}
                    </option>
                @endforeach
            </select>

            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-slate-500">
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

    </div>

    <div class="flex flex-col sm:flex-row gap-2 sm:col-span-2 lg:col-span-4 justify-end mt-1">
        <button type="submit"
            class="w-full sm:w-auto bg-gray-200 hover:bg-indigo-600 hover:text-white dark:bg-slate-700 dark:text-gray-200 dark:hover:bg-indigo-600 text-gray-700 text-xs font-semibold py-2.5 px-5 rounded-xl transition-all shadow-sm">
            تطبيق
        </button>
        
        @if ($search || $source || $status || $dateFrom || $dateTo)
            <a href="{{ route('potential-customers.index') }}"
                class="w-full sm:w-auto bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-950/40 text-rose-600 dark:text-rose-400 text-xs font-semibold py-2 px-4 rounded-xl flex items-center justify-center transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </a>
        @endif
    </div>
</form>

<script>
    // دالة توحيد الحروف العربية للبحث
    function normalizeArabic(text) {
        if (!text) return '';
        return text.trim().toLowerCase()
            .replace(/[أإآا]/g, 'ا')
            .replace(/[ةه]/g, 'ه')
            .replace(/ى/g, 'ي')
            .replace(/[\u064B-\u0652]/g, '');
    }

    // تصفية خيارات القائمة المنسدلة
    function filterDropdownOptions() {
        const searchInput = normalizeArabic(document.getElementById('dropdown-search').value);
        const options = document.querySelectorAll('.option-item');

        options.forEach(option => {
            const rawName = option.getAttribute('data-name');
            const normalizedName = normalizeArabic(rawName);
            option.style.display = (normalizedName.includes(searchInput) || rawName === "") ? 'block' : 'none';
        });
    }

    // تبديل حالة القائمة المنسدلة
    window.toggleDropdown = function() {
        const checkbox = document.getElementById('my-clients-checkbox');
        if (checkbox && checkbox.checked) return;

        const menu = document.getElementById('dropdown-menu');
        const arrow = document.getElementById('dropdown-arrow');

        if (menu) menu.classList.toggle('hidden');
        if (arrow) arrow.classList.toggle('rotate-180');

        if (menu && !menu.classList.contains('hidden')) {
            const searchInput = document.getElementById('dropdown-search');
            if (searchInput) searchInput.focus();
        }
    };

    // دالة لتنظيف البيانات الفارغة قبل الإرسال (تمنع إرسال المدخلات الفارغة في الرابط)
    function submitCleanForm(event) {
        if (event) event.preventDefault();
        
        const form = document.getElementById('filter-form');
        const formData = new FormData(form);
        const params = new URLSearchParams();

        formData.forEach((value, key) => {
            // نأخذ فقط العناصر التي تحتوي على قيمة وليست فارغة
            if (value !== '' && value !== null) {
                params.append(key, value);
            }
        });

        // توجيه المستخدم للرابط النظيف الجديد
        window.location.href = form.action + '?' + params.toString();
    }

    // دالة مساعدة لتشغيل الإرسال النظيف عند تغيير الـ Select أو المدخلات تلقائياً
    window.triggerFormSubmit = function() {
        submitCleanForm();
    };

    // اختيار مستخدم من القائمة وإرسال الفورم بدون حقول فارغة
    window.selectUser = function(id, name) {
        const userIdInput = document.getElementById('hidden-user-id');
        const dropdownLabel = document.getElementById('dropdown-label');
        const menu = document.getElementById('dropdown-menu');
        const arrow = document.getElementById('dropdown-arrow');

        if (userIdInput) userIdInput.value = id;
        if (dropdownLabel) dropdownLabel.innerText = name;
        if (menu) menu.classList.add('hidden');
        if (arrow) arrow.remove('rotate-180');

        // تنفيذ الإرسال النظيف فوراً
        submitCleanForm();
    };

    // التعامل مع تغيير الـ Checkbox
    window.handleCheckboxChange = function(checkbox) {
        const dropdownBtn = document.getElementById('dropdown-btn');
        if (checkbox.checked) {
            document.getElementById('hidden-user-id').value = '';
            document.getElementById('dropdown-label').innerText = 'جميع الموظفين';
            if (dropdownBtn) dropdownBtn.disabled = true;
        } else {
            if (dropdownBtn) dropdownBtn.disabled = false;
        }
        
        // تنفيذ الإرسال النظيف فوراً
        submitCleanForm();
    };

    // إغلاق القائمة عند النقر خارجها
    document.addEventListener('click', function(event) {
        const container = document.getElementById('custom-dropdown-container');
        const menu = document.getElementById('dropdown-menu');
        const arrow = document.getElementById('dropdown-arrow');

        if (container && !container.contains(event.target) && menu) {
            menu.classList.add('hidden');
            if (arrow) arrow.classList.remove('rotate-180');
        }
    });

    // تهيئة الحالة عند تحميل الصفحة
    document.addEventListener("DOMContentLoaded", function() {
        const checkbox = document.getElementById('my-clients-checkbox');
        const dropdownBtn = document.getElementById('dropdown-btn');
        if (checkbox && checkbox.checked && dropdownBtn) {
            dropdownBtn.disabled = true;
        }
    });
</script>