<x-app-layout>
    <!-- Main Wrapper Component -->
    <div x-data="{
        confirmModal: false,
        modalTitle: '',
        modalMessage: '',
        formToSubmit: null,
        confirmColor: 'bg-indigo-600',
        pendingStatusValue: null,
        openConfirm(title, message, formId, color = 'bg-indigo-600') {
            this.modalTitle = title;
            this.modalMessage = message;
            this.formToSubmit = formId;
            this.confirmColor = color;
            this.confirmModal = true;
        },
        handleStatusChange(event, formId) {
            let select = event.target;
            let originalValue = select.getAttribute('data-original-value');
    
            if (select.value === originalValue) {
                return;
            }
    
            this.pendingStatusValue = select.value;
    
            let statusLabel = select.options[select.selectedIndex].text;
    
            // فتح مودال التأكيد لـ Alpine
            this.openConfirm(
                'تغيير حالة العميل',
                `هل أنت متأكد من تغيير حالة هذا العميل إلى (${statusLabel})؟`,
                formId,
                'bg-amber-600'
            );
    
            // إرجاع قيمة الـ select مؤقتاً لشكلها الأصلي حتى يضغط المستخدم على زر 'تأكيد'
            select.value = originalValue;
        },
        submitPendingForm() {
            let form = document.getElementById(this.formToSubmit);
            // إرسال القيمة الجديدة المختارة مع الفورم عند التأكيد
            let select = form.querySelector('select[name=&quot;status&quot;]');
            select.value = this.pendingStatusValue;
            form.submit();
        }
    }"
        class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 p-5 md:p-6 h-[calc(100vh-12rem)] lg:h-[calc(100vh-7rem)] flex flex-col overflow-hidden transition-colors duration-300">

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5 shrink-0">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                    Potential Customers Management
                </h3>
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">Seamlessly manage, route, and convert your
                    incoming leads</p>
            </div>
            <div class="flex items-center gap-3 self-end sm:self-auto">
                <div
                    class="text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 px-3 py-2 rounded-xl border border-indigo-100 dark:border-indigo-900/30">
                    Total Leads: <span class="font-bold">{{ $customers->total() }}</span>
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

        <!-- Filter & Search Panel -->
        <form action="{{ url()->current() }}" method="GET"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-5 bg-gray-50 dark:bg-slate-800/50 p-4 rounded-2xl border border-gray-100 dark:border-slate-800">

            <input type="hidden" name="sort_by" value="{{ request('sort_by', 'added_at') }}">
            <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">

            <!-- Search Field -->
            <div class="relative sm:col-span-2 lg:col-span-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by name or phone..."
                    class="w-full text-xs rounded-xl border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-900 dark:text-gray-100 pl-9 pr-4 py-2.5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">

                <div
                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-slate-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Date Range -->
            <div
                class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 lg:col-span-2 bg-white dark:bg-slate-800 px-3 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all">
                <!-- From -->
                <div class="flex items-center gap-2 flex-1 cursor-pointer"
                    onclick="this.querySelector('input').showPicker()">
                    <span
                        class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider select-none">From</span>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                        max="{{ now()->format('Y-m-d') }}" onchange="handleDateFromChange(this)"
                        class="w-full text-xs bg-transparent border-0 text-gray-800 dark:text-gray-200 p-0 focus:ring-0 cursor-pointer dynamic-date-input">
                </div>

                <div class="hidden sm:block h-4 w-[1px] bg-gray-200 dark:bg-slate-700 mx-1"></div>

                <!-- To -->
                <div class="flex items-center gap-2 flex-1 cursor-pointer"
                    onclick="this.querySelector('input').showPicker()">
                    <span
                        class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider select-none">To</span>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                        max="{{ now()->format('Y-m-d') }}" onchange="handleDateToChange(this)"
                        class="w-full text-xs bg-transparent border-0 text-gray-800 dark:text-gray-200 p-0 focus:ring-0 cursor-pointer dynamic-date-input">
                </div>
            </div>

            <script>
                // عند تحميل الصفحة، نضبط القيود بناءً على القيم الحالية لو موجودة
                window.addEventListener('DOMContentLoaded', () => {
                    const fromInput = document.getElementById('date_from');
                    const toInput = document.getElementById('date_to');

                    if (fromInput.value) {
                        toInput.min = fromInput.value;
                    }
                    if (toInput.value) {
                        fromInput.max = toInput.value;
                    }
                });

                function handleDateFromChange(input) {
                    const toInput = document.getElementById('date_to');

                    if (input.value) {
                        // تاريخ "إلى" لا يمكن أن يكون قبل تاريخ "من"
                        toInput.min = input.value;
                    } else {
                        toInput.removeAttribute('min');
                    }

                    // إرسال الفورم تلقائياً كما كان في كودك الأصلي
                    input.form.submit();
                }

                function handleDateToChange(input) {
                    const fromInput = document.getElementById('date_from');

                    if (input.value) {
                        // تاريخ "من" لا يمكن أن يكون بعد تاريخ "إلى"
                        fromInput.max = input.value;
                    } else {
                        // إذا مسح تاريخ "إلى"، أقصى حد يرجع لتاريخ اليوم (كما في Laravel)
                        fromInput.max = "{{ now()->format('Y-m-d') }}";
                    }

                    // إرسال الفورم تلقائياً كما كان في كودك الأصلي
                    input.form.submit();
                }
            </script>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <select name="source" onchange="this.form.submit()"
                    class="w-full text-xs rounded-xl border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all cursor-pointer py-2.5">
                    <option value="">All Sources</option>
                    @foreach (App\Enums\PotentialCustomerSource::cases() as $source)
                        <option value="{{ $source->value }}"
                            {{ request('source') == $source->value ? 'selected' : '' }}>
                            {{ $source->value }}
                        </option>
                    @endforeach
                </select>

                <select name="status" onchange="this.form.submit()"
                    class="w-full text-xs rounded-xl border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all cursor-pointer py-2.5">
                    <option value="">All Statuses</option>
                    @foreach (App\Enums\PotentialCustomerStatus::cases() as $status)
                        <option value="{{ $status->value }}"
                            {{ request('status') == $status->value ? 'selected' : '' }}>
                            {{ ucfirst($status->value) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-2 sm:col-span-2 lg:col-span-4 justify-end mt-1">
                @if (request()->has('search') ||
                        request()->has('source') ||
                        request()->has('status') ||
                        request()->has('date_from') ||
                        request()->has('date_to'))
                    <a href="{{ route('potential-customers.index') }}"
                        class="w-full sm:w-auto bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-950/40 text-rose-600 dark:text-rose-400 text-xs font-semibold py-2 px-4 rounded-xl flex items-center justify-center transition-colors">
                        Clear Filters
                    </a>
                @endif

                <button type="submit"
                    class="w-full sm:w-auto bg-gray-200 hover:bg-indigo-600 hover:text-white dark:bg-slate-700 dark:text-gray-200 dark:hover:bg-indigo-600 text-gray-700 text-xs font-semibold py-2 px-5 rounded-xl transition-all shadow-sm">
                    Apply Layout Filters
                </button>
            </div>
        </form>

        <!-- Dynamic Responsive Table Data Layer -->
        <div
            class="flex-1 h-0 overflow-hidden rounded-xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
            <div class="h-full overflow-auto custom-scrollbar">
                <table class="w-full min-w-[1000px] border-collapse text-left">
                    <!-- Table Head Elements -->
                    <thead
                        class="sticky top-0 z-20 bg-gray-50/90 dark:bg-slate-800/90 backdrop-blur-md border-b border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-300">
                        <tr>
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'name' ? 'desc' : 'asc']) }}"
                                    class="inline-flex items-center justify-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    Customer Name
                                    @if (request('sort_by') === 'name')
                                        <span class="text-xs">{{ request('sort_order') === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">Phone</th>
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'source', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'source' ? 'desc' : 'asc']) }}"
                                    class="inline-flex items-center justify-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    Source
                                    @if (request('sort_by') === 'source')
                                        <span class="text-xs">{{ request('sort_order') === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'status' ? 'desc' : 'asc']) }}"
                                    class="inline-flex items-center justify-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    Status
                                    @if (request('sort_by') === 'status')
                                        <span class="text-xs">{{ request('sort_order') === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>
                            @if (auth()->user()->isCEO())
                                <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">Added By</th>
                            @endif
                            <th class="p-4 text-center uppercase text-[10px] font-bold tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'added_at', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'added_at' ? 'desc' : 'asc']) }}"
                                    class="inline-flex items-center justify-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    Added At
                                    @if (request('sort_by', 'added_at') === 'added_at')
                                        <span
                                            class="text-xs">{{ request('sort_order', 'desc') === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>
                            <!-- تحديث رأس عمود الأكشن بعرض ثابت وضخم -->
                            <th
                                class="p-4 text-center uppercase text-[10px] font-bold tracking-wider w-48 min-w-[190px]">
                                Actions</th>
                        </tr>
                    </thead>

                    <!-- Data Body Elements -->
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                        @forelse($customers as $customer)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors group">
                                <td class="p-4 text-center whitespace-nowrap">
                                    <span
                                        class="font-semibold text-sm text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $customer->name }}</span>
                                </td>

                                <td
                                    class="p-4 text-center whitespace-nowrap text-xs font-medium text-gray-600 dark:text-slate-300">
                                    {{ $customer->phone }}
                                </td>

                                <td class="p-4 text-center whitespace-nowrap">
                                    @if ($customer->source)
                                        @php
                                            $sourceEnum =
                                                $customer->source instanceof \App\Enums\PotentialCustomerSource
                                                    ? $customer->source
                                                    : \App\Enums\PotentialCustomerSource::tryFrom($customer->source);

                                            $colorClass = match ($sourceEnum) {
                                                \App\Enums\PotentialCustomerSource::FACEBOOK
                                                    => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800/30',
                                                \App\Enums\PotentialCustomerSource::INSTAGRAM
                                                    => 'bg-pink-50 text-pink-700 border-pink-200 dark:bg-pink-900/20 dark:text-pink-400 dark:border-pink-800/30',
                                                \App\Enums\PotentialCustomerSource::WHATSAPP
                                                    => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800/30',
                                                \App\Enums\PotentialCustomerSource::WEBSITE
                                                    => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-900/20 dark:text-indigo-400 dark:border-indigo-800/30',
                                                \App\Enums\PotentialCustomerSource::REFERRAL
                                                    => 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-900/20 dark:text-purple-400 dark:border-purple-800/30',
                                                default
                                                    => 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
                                            };
                                        @endphp

                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-medium border {{ $colorClass }}">
                                            {{ $sourceEnum?->label() ?? $customer->source }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-medium bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-500 border border-transparent">
                                            غير محدد
                                        </span>
                                    @endif
                                </td>

                                <td class="p-4 text-center whitespace-nowrap">
                                    @php
                                        $statusEnum =
                                            $customer->status instanceof \App\Enums\PotentialCustomerStatus
                                                ? $customer->status
                                                : \App\Enums\PotentialCustomerStatus::tryFrom($customer->status);

                                        $statusClasses = match ($statusEnum) {
                                            \App\Enums\PotentialCustomerStatus::NEW
                                                => 'bg-blue-50 text-blue-700 border-blue-200/60 dark:bg-blue-950/40 dark:text-blue-400 dark:border-blue-900/40',
                                            \App\Enums\PotentialCustomerStatus::CONTACTED
                                                => 'bg-amber-50 text-amber-700 border-amber-200/60 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-900/40',
                                            \App\Enums\PotentialCustomerStatus::CONFIRMED
                                                => 'bg-emerald-50 text-emerald-700 border-emerald-200/60 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/40',
                                            \App\Enums\PotentialCustomerStatus::CANCELLED
                                                => 'bg-rose-50 text-rose-700 border-rose-200/60 dark:bg-rose-950/40 dark:text-rose-400 dark:border-rose-900/40',
                                            default
                                                => 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700',
                                        };

                                        $dotClasses = match ($statusEnum) {
                                            \App\Enums\PotentialCustomerStatus::NEW => 'bg-blue-500',
                                            \App\Enums\PotentialCustomerStatus::CONTACTED => 'bg-amber-500',
                                            \App\Enums\PotentialCustomerStatus::CONFIRMED => 'bg-emerald-500',
                                            \App\Enums\PotentialCustomerStatus::CANCELLED => 'bg-rose-500',
                                            default => 'bg-gray-400',
                                        };

                                        $currentStatusValue = is_object($customer->status)
                                            ? $customer->status->value
                                            : $customer->status;

                                        $isLocked = in_array($currentStatusValue, [
                                            \App\Enums\PotentialCustomerStatus::CONFIRMED->value,
                                            \App\Enums\PotentialCustomerStatus::CANCELLED->value,
                                        ]);
                                    @endphp

                                    <span dir="rtl"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide border shadow-sm {{ $statusClasses }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dotClasses }}"></span>
                                        {{ $statusEnum?->label() ?? ($customer->status ?? 'غير محدد') }}
                                    </span>
                                </td>

                                @if (auth()->user()->isCEO())
                                    <td
                                        class="p-4 text-center whitespace-nowrap text-xs font-medium text-gray-600 dark:text-slate-400">
                                        {{ $customer->creator->name ?? 'System' }}
                                    </td>
                                @endif

                                <td
                                    class="p-4 text-center whitespace-nowrap text-gray-500 dark:text-slate-400 text-xs">
                                    {{ \Carbon\Carbon::parse($customer->added_at)->format('M d, Y • H:i') }}
                                </td>

                                <td class="p-4 text-center whitespace-nowrap align-middle w-56 min-w-[220px]"
                                    x-data="{
                                        showModal: false,
                                        currentStatus: '{{ $currentStatusValue }}',
                                        showCurrentLabel(e) {
                                            let opt = e.target.querySelector('option[disabled]:checked');
                                            if (opt && !opt.text.includes('(الحالية)')) {
                                                opt.text = opt.text + ' (الحالية)';
                                            }
                                        },
                                        hideCurrentLabel(e) {
                                            let opt = e.target.querySelector('option[disabled]:checked');
                                            if (opt) {
                                                opt.text = opt.text.replace(' (الحالية)', '').trim();
                                            }
                                        },
                                        checkStatus(e) {
                                            this.hideCurrentLabel(e);
                                            if (e.target.value === '{{ \App\Enums\PotentialCustomerStatus::CONTACTED->value }}') {
                                                this.showModal = true;
                                                e.target.value = e.target.getAttribute('data-original-value');
                                            } else {
                                                handleStatusChange(e, 'status-form-{{ $customer->id }}');
                                            }
                                        }
                                    }">

                                    <div class="grid grid-cols-[1fr_auto] items-center gap-2 max-w-[200px] mx-auto">
                                        <!-- حاوية السلكت المفرملة والسهم على اليسار -->
                                        <div class="w-full">
                                            <form id="status-form-{{ $customer->id }}"
                                                action="{{ route('potential-customers.update-status', $customer->id) }}"
                                                method="POST" class="m-0">
                                                @csrf
                                                @method('PATCH')

                                                <select name="status" x-on:focus="showCurrentLabel($event)"
                                                    x-on:blur="hideCurrentLabel($event)"
                                                    x-on:change="checkStatus($event)"
                                                    data-original-value="{{ $currentStatusValue }}" dir="rtl"
                                                    {{ $isLocked ? 'disabled' : '' }}
                                                    class="w-full text-xs border border-gray-300 dark:border-slate-600 rounded-lg pl-8 pr-2 py-1 bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-200 text-right focus:ring-2 focus:ring-indigo-500 appearance-none bg-no-repeat bg-[left_0.5rem_center] disabled:opacity-60 disabled:cursor-not-allowed transition-all"
                                                    style="background-size: 0.65em auto; height: 30px;">

                                                    @if ($currentStatusValue == \App\Enums\PotentialCustomerStatus::NEW->value)
                                                        <option
                                                            value="{{ \App\Enums\PotentialCustomerStatus::NEW->value }}"
                                                            selected disabled class="text-gray-400 font-normal">
                                                            {{ \App\Enums\PotentialCustomerStatus::NEW->label() }}
                                                        </option>
                                                        <option
                                                            value="{{ \App\Enums\PotentialCustomerStatus::CONTACTED->value }}">
                                                            {{ \App\Enums\PotentialCustomerStatus::CONTACTED->label() }}
                                                        </option>
                                                    @elseif($currentStatusValue == \App\Enums\PotentialCustomerStatus::CONTACTED->value)
                                                        <option
                                                            value="{{ \App\Enums\PotentialCustomerStatus::CONTACTED->value }}"
                                                            selected disabled class="text-gray-400 font-normal">
                                                            {{ \App\Enums\PotentialCustomerStatus::CONTACTED->label() }}
                                                        </option>
                                                        <option
                                                            value="{{ \App\Enums\PotentialCustomerStatus::CONFIRMED->value }}">
                                                            {{ \App\Enums\PotentialCustomerStatus::CONFIRMED->label() }}
                                                        </option>
                                                        <option
                                                            value="{{ \App\Enums\PotentialCustomerStatus::CANCELLED->value }}">
                                                            {{ \App\Enums\PotentialCustomerStatus::CANCELLED->label() }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $currentStatusValue }}" selected disabled>
                                                            {{ $statusEnum?->label() ?? $currentStatusValue }}
                                                        </option>
                                                    @endif
                                                </select>
                                            </form>
                                        </div>

                                        <!-- أزرار التحكم الجانبية -->
                                        <div class="flex items-center gap-1 w-[65px] h-[30px] justify-center">
                                            @if ($currentStatusValue == \App\Enums\PotentialCustomerStatus::CONTACTED->value)
                                                <button type="button" @click="showModal = true"
                                                    class="p-1.5 text-emerald-500 hover:text-emerald-600 dark:text-emerald-400 dark:hover:text-emerald-300 rounded-lg hover:bg-emerald-50 dark:hover:bg-slate-700 transition-colors flex items-center justify-center"
                                                    title="إضافة تفاصيل التواصل">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                    </svg>
                                                </button>
                                            @endif

                                            <a href="{{ route('potential-customers.edit', $customer->id) }}"
                                                class="p-1.5 text-gray-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center"
                                                title="Edit Customer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- نافذة البوب أب (Pop-up Modal) -->
                                    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto"
                                        style="display: none;" aria-labelledby="modal-title" role="dialog"
                                        aria-modal="true">

                                        <div
                                            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                            <!-- غطاء الخلفية الشفاف الخلفي (تم إزالة حدث الإغلاق عند الضغط هنا لحمايته) -->
                                            <div x-show="showModal" x-transition:enter="ease-out duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="ease-in duration-200"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                                class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-slate-900 dark:bg-opacity-80 transition-opacity"
                                                aria-hidden="true"></div>

                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                                aria-hidden="true">&#8203;</span>

                                            <!-- محتوى الـ Pop-up الداخلي -->
                                            <div x-show="showModal" x-transition:enter="ease-out duration-300"
                                                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                x-transition:leave="ease-in duration-200"
                                                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full w-full relative"
                                                dir="rtl">

                                                <!-- زر الـ X للإغلاق في أعلى اليسار -->
                                                <button type="button" @click="showModal = false"
                                                    class="absolute top-4 left-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>

                                                <form
                                                    action="{{ route('potential-customers.update-status', $customer->id) }}"
                                                    method="POST" class="m-0">
                                                    @csrf
                                                    @method('PATCH')

                                                    <input type="hidden" name="status"
                                                        value="{{ \App\Enums\PotentialCustomerStatus::CONTACTED->value }}">

                                                    <div
                                                        class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                        <div class="sm:flex sm:items-start">
                                                            <div class="mt-3 text-center sm:mt-0 sm:text-right w-full">
                                                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-slate-100"
                                                                    id="modal-title">
                                                                    تحديث حالة العميل وتفاصيل التواصل
                                                                </h3>

                                                                <div
                                                                    class="mt-4 space-y-4 text-sm text-gray-500 dark:text-slate-400">

                                                                    <!-- 1. حقل السبب (Reason) -->
                                                                    <div>
                                                                        <label
                                                                            class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">السبب
                                                                            (Reason)
                                                                        </label>
                                                                        <div class="relative">
                                                                            <select name="reason" required
                                                                                class="w-full text-sm border border-gray-300 dark:border-slate-600 rounded-lg pl-10 pr-3 py-2 bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 appearance-none bg-no-repeat bg-[left_0.75rem_center]"
                                                                                style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"%236b7280\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19 9l-7 7-7-7\"/></svg>'); background-size: 1.25em;">

                                                                                <option value="" disabled
                                                                                    selected>اختر السبب...</option>

                                                                                @foreach (\App\Enums\PotentialCustomerReason::cases() as $reason)
                                                                                    <option
                                                                                        value="{{ $reason->value }}">
                                                                                        {{ $reason->label() }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <!-- 2. حقل تاريخ ووقت المتابعة (يفتح عند الضغط في أي مكان) -->
                                                                    <div>
                                                                        <label
                                                                            class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">تاريخ
                                                                            ووقت المتابعة القادم</label>
                                                                        <input type="datetime-local"
                                                                            name="next_follow_up_date"
                                                                            onclick="this.showPicker()"
                                                                            class="w-full text-sm border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 text-right cursor-pointer">
                                                                    </div>

                                                                    <!-- 3. حقل الملاحظات -->
                                                                    <div>
                                                                        <label
                                                                            class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">ملاحظات</label>
                                                                        <textarea name="notes" rows="3" placeholder="أضف أي تفاصيل أخرى هنا..."
                                                                            class="w-full text-sm border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500"></textarea>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- أزرار التحكم بأسفل المودال -->
                                                    <div
                                                        class="bg-gray-50 dark:bg-slate-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                                                        <button type="submit"
                                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm transition-colors">
                                                            حفظ وتحديث
                                                        </button>
                                                        <button type="button" @click="showModal = false"
                                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-slate-500 shadow-sm px-4 py-2 bg-white dark:bg-slate-800 text-base font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                                                            إلغاء
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->isCEO() ? '7' : '6' }}"
                                    class="p-12 text-center text-gray-400 dark:text-slate-500 italic text-sm">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <svg class="w-8 h-8 text-gray-300 dark:text-slate-700" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        No potential lead logs matching filter arrays were found.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Custom Pagination Links Component -->
        <div
            class="shrink-0 pt-4 mt-2 border-t border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 dynamic-pagination">
            {{ $customers->appends(request()->query())->links() }}
        </div>

        <!-- Alpine.js Confirmation Modal Layout -->
        <div x-show="confirmModal"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div @click.away="confirmModal = false"
                class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-sm w-full p-6 border border-gray-100 dark:border-slate-700 transform transition-all">

                <div
                    class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-rose-50 dark:bg-rose-950/30 rounded-full">
                    <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h4 class="text-base font-bold text-center text-gray-900 dark:text-white" x-text="modalTitle"></h4>
                <p class="mt-2 text-xs text-center text-gray-500 dark:text-slate-400 leading-relaxed"
                    x-text="modalMessage"></p>

                <div class="flex gap-3 mt-5">
                    <button @click="confirmModal = false"
                        class="flex-1 px-4 py-2 text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600 rounded-xl transition-colors">
                        Cancel Action
                    </button>
                    <button @click="submitPendingForm()" :class="confirmColor"
                        class="flex-1 px-4 py-2 text-xs font-semibold text-white rounded-xl shadow-sm hover:opacity-95 transition-colors">
                        Confirm Action
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Supplementary Structural Utilities CSS -->
    <style>
        [x-cloak] {
            display: none !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
        }
    </style>
</x-app-layout>
