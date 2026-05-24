<div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-50/50 dark:bg-slate-900/50">
    <div>
        <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2.5">
            <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor"
                stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            سجل متابعة وتحديث حالات العملاء
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">تابع تحركات العملاء، الأسباب، ومواعيد
            التواصل القادمة أولاً بأول.</p>
    </div>
    <span
        class="bg-violet-50 text-violet-700 dark:bg-violet-950/40 dark:text-violet-300 text-xs font-bold px-3.5 py-2 rounded-xl border border-violet-100 dark:border-violet-900/30">
        إجمالي العملاء: <span class="text-sm font-extrabold">{{ $totalCount }}</span>
    </span>
</div>