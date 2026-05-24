@if ($success)
    <div
        class="m-6 p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-800 dark:text-emerald-400 rounded-xl border border-emerald-200/60 dark:border-emerald-900/40 text-sm flex items-center gap-2.5 shadow-sm animate-fade-in">
        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-500" fill="none" stroke="currentColor"
            stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ $success }}
    </div>
@endif

@if ($error)
    <div
        class="m-6 p-4 bg-rose-50 dark:bg-rose-950/20 text-rose-800 dark:text-rose-400 rounded-xl border border-rose-200/60 dark:border-rose-900/40 text-sm flex items-center gap-2.5 shadow-sm animate-fade-in">
        <svg class="w-5 h-5 text-rose-600 dark:text-rose-500" fill="none" stroke="currentColor"
            stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ $error }}
    </div>
@endif