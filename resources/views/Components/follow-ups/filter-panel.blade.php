<form action="{{ url()->current() }}" method="GET"
    class=" mb-0 bg-slate-50/80 dark:bg-slate-800/40 rounded-xl p-4 border border-slate-100 dark:border-slate-800">

    <input type="hidden" name="sort_by" value="{{ $sortBy }}">
    <input type="hidden" name="sort_order" value="{{ $sortOrder }}">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Search Field -->
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

        <!-- Actions -->
        <div class="flex gap-2 justify-end">
            @if ($search || $status)
                <a href="{{ route('customer-follow-ups.index') }}"
                    class="bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-950/40 text-rose-600 dark:text-rose-400 text-xs font-semibold py-2.5 px-4 rounded-xl flex items-center justify-center transition-colors">
                    clear </a>
            @endif

            <button type="submit"
                class="w-full sm:w-auto bg-gray-200 hover:bg-indigo-600 hover:text-white dark:bg-slate-700 dark:text-gray-200 dark:hover:bg-indigo-600 text-gray-700 text-xs font-semibold py-1 px-5 rounded-xl transition-all shadow-sm">
                apply layout filter </button>
        </div>
    </div>
</form>
