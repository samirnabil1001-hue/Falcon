@if ($source)
    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-medium border {{ $colorClass }}">
        {{ $sourceEnum?->label() ?? $source }}
    </span>
@else
    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-medium bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-500 border border-transparent">
        غير محدد
    </span>
@endif