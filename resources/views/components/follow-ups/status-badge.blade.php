<span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full tracking-wide shadow-sm border {{ $statusClass }}">
    {{ is_object($status) ? $status->label() : $status }}
</span>