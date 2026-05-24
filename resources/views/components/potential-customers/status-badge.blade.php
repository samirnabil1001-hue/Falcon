<span dir="rtl"
    class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide border shadow-sm {{ $statusClasses }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $dotClasses }}"></span>
    {{ $statusEnum?->label() ?? ($status ?? 'غير محدد') }}
</span>