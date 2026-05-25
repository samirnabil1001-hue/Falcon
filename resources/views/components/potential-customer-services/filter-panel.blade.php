@props([
    'search' => '',
    'dateFrom' => '',
    'dateTo' => '',
    'serviceType' => '',
    'userId' => '',
    'sortBy' => 'created_at',
    'sortOrder' => 'desc',
    'users' => [],
    'onlyMe' => request()->boolean('only_me'),
])

@php
    if (empty($users) || count($users) === 0) {
        $users = \App\Models\User::join(
            'potential_customer_services',
            'users.id',
            '=',
            'potential_customer_services.user_id',
        )
            ->select('users.id', 'users.name', \DB::raw('count(potential_customer_services.id) as customers_count'))
            ->where('users.id', '!=', auth()->id())
            ->groupBy('users.id', 'users.name')
            ->orderBy('users.name')
            ->get();
    }
@endphp

<form method="GET" action="{{ url()->current() }}" id="filter-search-form" x-data="{ onlyMe: {{ $onlyMe ? 'true' : 'false' }} }"
    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-12 gap-3 py-4 shrink-0 items-end text-right"
    dir="rtl">

    <input type="hidden" name="sort_by" value="{{ $sortBy }}">
    <input type="hidden" name="sort_order" value="{{ $sortOrder }}">

    <div class="flex flex-col gap-1 lg:col-span-3">
        <label class="text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">البحث
            العام</label>
        <input type="text" name="search" value="{{ $search }}" placeholder="ابحث بالملاحظات، الهاتف..."
            class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all py-2 px-3">
    </div>

    <div class="flex flex-col gap-1 lg:col-span-2">
        <label class="text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">نوع
            الخدمة</label>

        <div class="relative w-full">
            <select name="service_type" onchange="document.getElementById('filter-search-form').submit();"
                class="appearance-none [appearance:none] [-webkit-appearance:none] [-moz-appearance:none] bg-none w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all py-2 pr-3 pl-9">

                <option value="">كل الخدمات</option>
                @foreach (\App\Enums\CompanyService::cases() as $serviceCase)
                    <option value="{{ $serviceCase->value }}"
                        {{ $serviceType === $serviceCase->value ? 'selected' : '' }}>
                        {{ $serviceCase->label() }}
                    </option>
                @endforeach
            </select>

            <div
                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-slate-500">
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-1 lg:col-span-4">
        <label class="text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">نطاق
            التاريخ</label>
        <x-date-range-picker :date-from="$dateFrom" :date-to="$dateTo" />
    </div>
    <div class="flex flex-col gap-1 lg:col-span-2">
        <label class="text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">الموظف
            المسؤول</label>

        <div class="relative w-full">
            <select name="user_id" :disabled="onlyMe"
                onchange="document.getElementById('filter-search-form').submit();"
                :class="onlyMe ? 'opacity-40 cursor-not-allowed bg-gray-100 dark:bg-slate-800 text-gray-400' : ''"
                class="appearance-none bg-none w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all py-2 pr-3 pl-9">

                <option value="">كل الموظفين</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->customers_count ?? 0 }})
                    </option>
                @endforeach
            </select>

            <div
                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-slate-500">
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-1 lg:col-span-1 w-full min-w-[90px]">
        <label class="inline-flex items-center gap-1.5 cursor-pointer select-none mb-1 self-start">
            <input type="checkbox" name="only_me" value="1" x-model="onlyMe"
                onchange="document.getElementById('filter-search-form').submit();"
                class="w-3.5 h-3.5 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800 dark:focus:ring-offset-slate-900">
            <span class="text-[11px] font-bold text-gray-600 dark:text-slate-300 whitespace-nowrap">سجلاتي</span>
        </label>

        <div class="flex gap-1 w-full">
            <button type="submit"
                class="flex-1 justify-center inline-flex items-center py-2 text-xs font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 shadow-sm transition-all duration-150">
                تطبيق
            </button>
            @if (request()->anyFilled(['search', 'service_type', 'user_id', 'date_from', 'date_to', 'only_me']))
                <a href="{{ url()->current() }}"
                    class="inline-flex items-center justify-center p-2 text-gray-500 hover:text-red-500 bg-gray-100 hover:bg-red-50 dark:bg-slate-800 dark:hover:bg-red-950/30 rounded-xl transition-all duration-150"
                    title="إعادة تعيين">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            @endif
        </div>
    </div>
</form>
