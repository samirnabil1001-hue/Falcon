@props(['search', 'sortBy', 'sortOrder', 'users'])

<form id="filter-form" action="{{ url()->current() }}" method="GET"
    class="mb-0 bg-slate-50/80 dark:bg-slate-800/40 rounded-xl p-4 border border-slate-100 dark:border-slate-800">

    <input type="hidden" name="sort_by" value="{{ $sortBy }}">
    <input type="hidden" name="sort_order" value="{{ $sortOrder }}">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-center">

        <div class="relative">
            <input type="text" name="search" value="{{ $search }}" placeholder="بحث بالاسم أو رقم الهاتف..."
                class="w-full text-sm rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
            <div
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
        <div class="md:col-span-2 lg:col-span-2">
            <x-date-range-picker :dateFrom="request('date_from')" :dateTo="request('date_to')" />
        </div>
        <x-user-filter-dropdown :users="$users" />



        <div class="flex items-center justify-start px-2">
            <label
                class="relative flex items-center cursor-pointer select-none text-sm font-medium text-slate-700 dark:text-slate-300">
                <input type="checkbox" id="my-clients-checkbox" name="my_clients" value="1"
                    {{ request('my_clients') == '1' ? 'checked' : '' }} onchange="handleCheckboxChange(this)"
                    class="w-4 h-4 text-violet-600 bg-white border-slate-300 rounded-md focus:ring-violet-500 dark:focus:ring-violet-600 dark:ring-offset-slate-800 focus:ring-2 dark:bg-slate-700 dark:border-slate-600 transition-all cursor-pointer ml-2">
                <span>عملائي فقط</span>
            </label>
        </div>

        <div class="flex gap-2 justify-end items-center lg:col-span-5 md:col-span-2">
            {{-- تم إزالة المتغير $status من شرط ظهور زر إلغاء الفلترة --}}
            @if ($search || request('user_id') || request('my_clients') || request('date_from') || request('date_to'))
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
    // دالة توحيد الحروف العربية
    function normalizeArabic(text) {
        if (!text) return '';
        return text.trim().toLowerCase()
            .replace(/[أإآا]/g, 'ا')
            .replace(/[ةه]/g, 'ه')
            .replace(/ى/g, 'ي')
            .replace(/[\u064B-\u0652]/g, '');
    }

    // تصفية الموظفين بناءً على البحث المدخل
    function filterDropdownOptions() {
        const rawInput = document.getElementById('dropdown-search').value;
        const searchInput = normalizeArabic(rawInput);
        const options = document.querySelectorAll('.option-item');

        options.forEach(option => {
            const rawName = option.getAttribute('data-name');
            const normalizedName = normalizeArabic(rawName);

            if (normalizedName.includes(searchInput) || rawName === "") {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
    }

    function toggleDropdown() {
        const checkbox = document.getElementById('my-clients-checkbox');
        if (checkbox && checkbox.checked) return;

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
            if (dropdownBtn) dropdownBtn.disabled = true;
        } else {
            if (dropdownBtn) dropdownBtn.disabled = false;
        }
        checkbox.form.submit();
    }

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

    document.addEventListener("DOMContentLoaded", function() {
        const checkbox = document.getElementById('my-clients-checkbox');
        const dropdownBtn = document.getElementById('dropdown-btn');
        if (checkbox && checkbox.checked && dropdownBtn) {
            dropdownBtn.disabled = true;
        }
    });
</script>
