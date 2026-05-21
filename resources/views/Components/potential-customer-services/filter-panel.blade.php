@props([
    'search' => '',
    'dateFrom' => '',
    'dateTo' => '',
    'serviceType' => '',
    'userId' => '',
    'sortBy' => 'created_at',
    'sortOrder' => 'desc'
])

<form method="GET" action="{{ url()->current() }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 py-4 shrink-0 items-end">
    <!-- Preserve existing sort states implicitly -->
    <input type="hidden" name="sort_by" value="{{ $sortBy }}">
    <input type="hidden" name="sort_order" value="{{ $sortOrder }}">

    <!-- Search input -->
    <div class="flex flex-col gap-1">
        <label class="text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Search</label>
        <input type="text" name="search" value="{{ $search }}" placeholder="Search notes, phone, names..."
               class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all py-2 px-3">
    </div>

    <!-- Filter by Service Type Enum Dropdown -->
    <div class="flex flex-col gap-1">
        <label class="text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Service Type</label>
        <select name="service_type" 
                class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all py-2 px-3">
            <option value="">All Services (الكل)</option>
            @foreach(\App\Enums\CompanyService::cases() as $serviceCase)
                <option value="{{ $serviceCase->value }}" {{ $serviceType === $serviceCase->value ? 'selected' : '' }}>
                    {{ $serviceCase->label() }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Date From -->
    <div class="flex flex-col gap-1">
        <label class="text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Date From</label>
        <input type="date" name="date_from" value="{{ $dateFrom }}"
               class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all py-2 px-3">
    </div>

    <!-- Date To -->
    <div class="flex flex-col gap-1">
        <label class="text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Date To</label>
        <input type="date" name="date_to" value="{{ $dateTo }}"
               class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all py-2 px-3">
    </div>

    <!-- Filter Action Buttons -->
    <div class="flex gap-2">
        <button type="submit" class="flex-1 justify-center inline-flex items-center gap-1 px-4 py-2 text-xs font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 shadow-sm shadow-indigo-200 dark:shadow-none transition-all duration-150">
            Apply Filters
        </button>
        @if(request()->anyFilled(['search', 'service_type', 'user_id', 'date_from', 'date_to']))
            <a href="{{ url()->current() }}" class="inline-flex items-center justify-center p-2 text-gray-500 hover:text-red-500 bg-gray-100 hover:bg-red-50 dark:bg-slate-800 dark:hover:bg-red-950/30 rounded-xl transition-all duration-150" title="Clear Filters">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        @endif
    </div>
</form>