@props(['totalCount' => 0])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-5 border-b border-gray-100 dark:border-slate-800 shrink-0">
    <div>
        <div class="flex items-center gap-2.5">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                Potential Customer Services Log
            </h1>
            <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400 border border-indigo-100/50 dark:border-indigo-900/30">
                {{ $totalCount }} Total
            </span>
        </div>
        <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">
            Manage and track actions, follow-ups, and service logs for prospective leads.
        </p>
    </div>
</div>