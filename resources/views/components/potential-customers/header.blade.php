<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5 shrink-0">
    <div>
        <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">
            إدارة العملاء المحتملين

        </h3>
        <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">إدارة وتوجيه وتحويل العملاء المحتملين الواردين 
</p>
    </div>
    <div class="flex items-center gap-3 self-end sm:self-auto">
        <div
            class="text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 px-3 py-2 rounded-xl border border-indigo-100 dark:border-indigo-900/30">
            Total Leads: <span class="font-bold">{{ $totalCount }}</span>
        </div>
        <a href="{{ route('potential-customers.create') }}"
            class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-xs font-semibold py-2 px-4 rounded-xl shadow-sm shadow-indigo-100 dark:shadow-none transition-all hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Add Customer
        </a>
    </div>
</div>