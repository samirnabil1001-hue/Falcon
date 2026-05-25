@props([
    'dateFrom' => '',
    'dateTo' => '',
])

<div {{ $attributes->merge(['class' => 'flex flex-col sm:flex-row items-stretch sm:items-center gap-3 bg-white dark:bg-slate-800 px-3 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all']) }}>
    
    <!-- From -->
    <div class="flex items-center gap-2 flex-1 cursor-pointer" onclick="this.querySelector('input').showPicker()">
        <span class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider select-none">من</span>
        <input type="date" id="date_from" name="date_from" value="{{ $dateFrom }}"
            max="{{ now()->format('Y-m-d') }}" onchange="handleDateFromChange(this)"
            class="w-full text-xs bg-transparent border-0 text-gray-800 dark:text-gray-200 p-0 focus:ring-0 cursor-pointer dynamic-date-input">
    </div>

    <div class="hidden sm:block h-4 w-[1px] bg-gray-200 dark:bg-slate-700 mx-1"></div>

    <!-- To -->
    <div class="flex items-center gap-2 flex-1 cursor-pointer" onclick="this.querySelector('input').showPicker()">
        <span class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider select-none">إلي</span>
        <input type="date" id="date_to" name="date_to" value="{{ $dateTo }}"
            max="{{ now()->format('Y-m-d') }}" onchange="handleDateToChange(this)"
            class="w-full text-xs bg-transparent border-0 text-gray-800 dark:text-gray-200 p-0 focus:ring-0 cursor-pointer dynamic-date-input">
    </div>
</div>

<script>
    // تشغيل منطق الحدود الدنيا والعليا للتواريخ بمجرد تحميل الصفحة
    document.addEventListener('DOMContentLoaded', () => {
        const fromInput = document.getElementById('date_from');
        const toInput = document.getElementById('date_to');

        if (fromInput && fromInput.value) {
            if (toInput) toInput.min = fromInput.value;
        }
        if (toInput && toInput.value) {
            if (fromInput) fromInput.max = toInput.value;
        }
    });

    function handleDateFromChange(input) {
        const toInput = document.getElementById('date_to');

        if (input.value) {
            if (toInput) toInput.min = input.value;
        } else {
            if (toInput) toInput.removeAttribute('min');
        }

        if (input.form) input.form.submit();
    }

    function handleDateToChange(input) {
        const fromInput = document.getElementById('date_from');

        if (input.value) {
            if (fromInput) fromInput.max = input.value;
        } else {
            if (fromInput) fromInput.max = "{{ now()->format('Y-m-d') }}";
        }

        if (input.form) input.form.submit();
    }
</script>