<x-app-layout>
    <!-- الحاوية الرئيسية -->
    <div x-data="{
        confirmModal: false,
        modalTitle: '',
        modalMessage: '',
        formToSubmit: null,
        confirmColor: 'bg-blue-600',
        openConfirm(title, message, formId, color = 'bg-blue-600') {
            this.modalTitle = title;
            this.modalMessage = message;
            this.formToSubmit = formId;
            this.confirmColor = color;
            this.confirmModal = true;
        }
    }"
        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 md:p-6 h-[calc(100vh-12rem)] lg:h-[calc(100vh-7rem)] flex flex-col overflow-hidden">

        <!-- Header Section -->
        <div class="flex items-center justify-between mb-4 shrink-0">
            <div>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
                    Potential Customers Management
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Manage and track your potential leads</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('potential-customers.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 px-4 rounded-lg transition-colors">
                    + Add New Customer
                </a>
                <div
                    class="text-sm font-medium bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-3 py-1 rounded-full">
                    Total: {{ $customers->total() }}
                </div>
            </div>
        </div>

        <!-- 🛠️ شريط الفلترة والبحث (مربوط بالـ Controller) -->
        <form action="{{ url()->current() }}" method="GET"
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 mb-4 shrink-0 bg-gray-50 dark:bg-gray-900/40 p-3 rounded-xl border border-gray-100 dark:border-gray-700">
            <!-- احتفاظ بقيم الترتيب الحالية عند البحث أو الفلترة -->
            <input type="hidden" name="sort_by" value="{{ request('sort_by', 'added_at') }}">
            <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">

            <!-- البحث بالاسم أو الهاتف -->
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by name or phone..."
                    class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white pl-8 focus:ring-blue-500 focus:border-blue-500">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <div
                class="flex items-center gap-2 md:col-span-2 bg-white dark:bg-gray-850 p-2 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-1 flex-1">
                    <label class="text-[10px] font-bold text-gray-500 uppercase shrink-0">From:</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        onchange="this.form.submit()"
                        class="w-full text-xs rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white p-1 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="flex items-center gap-1 flex-1">
                    <label class="text-[10px] font-bold text-gray-500 uppercase shrink-0">To:</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" onchange="this.form.submit()"
                        class="w-full text-xs rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white p-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <!-- فلتر المصدر -->
            <div>
                <select name="source" onchange="this.form.submit()"
                    class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Sources</option>
                    <option value="Facebook" {{ request('source') == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                    <option value="Instagram" {{ request('source') == 'Instagram' ? 'selected' : '' }}>Instagram
                    </option>
                    <option value="Website" {{ request('source') == 'Website' ? 'selected' : '' }}>Website</option>
                    <option value="WhatsApp" {{ request('source') == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                    <option value="Referral" {{ request('source') == 'Referral' ? 'selected' : '' }}>Referral</option>
                    <option value="Other" {{ request('source') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <!-- فلتر الحالة -->
            <div>
                <select name="status" onchange="this.form.submit()"
                    class="w-full text-xs rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Statuses</option>
                    <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                    <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted
                    </option>
                    <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Converted
                    </option>
                    <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                </select>
            </div>

            <!-- أزرار التحكم -->
            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 text-gray-700 dark:text-gray-200 text-xs font-semibold py-2 px-3 rounded-lg transition-colors">
                    Apply Filter
                </button>
                @if (request()->has('search') || request()->has('source') || request()->has('status'))
                    <a href="{{ route('potential-customers.index') }}"
                        class="bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 hover:bg-red-100 text-xs font-semibold py-2 px-3 rounded-lg flex items-center justify-center transition-colors">
                        Clear
                    </a>
                @endif
            </div>
        </form>

        <!-- Table Container -->
        <div
            class="flex-1 h-0 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <div class="h-full overflow-auto">
                <table class="w-full min-w-[1000px] border-collapse">
                    <!-- Sticky Header -->
                    <thead class="sticky top-0 z-20 bg-gray-100 dark:bg-gray-700 shadow-sm">
                        <tr>
                            <!-- ترتيب بالاسم -->
                            <th
                                class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'name' ? 'desc' : 'asc']) }}"
                                    class="flex items-center justify-center gap-1 hover:text-blue-500">
                                    Customer Name
                                    @if (request('sort_by') === 'name')
                                        <span>{{ request('sort_order') === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th
                                class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">
                                Phone
                            </th>
                            <!-- ترتيب بالمصدر -->
                            <th
                                class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'source', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'source' ? 'desc' : 'asc']) }}"
                                    class="flex items-center justify-center gap-1 hover:text-blue-500">
                                    Source
                                    @if (request('sort_by') === 'source')
                                        <span>{{ request('sort_order') === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>
                            <!-- ترتيب بالحالة -->
                            <th
                                class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'status' ? 'desc' : 'asc']) }}"
                                    class="flex items-center justify-center gap-1 hover:text-blue-500">
                                    Status
                                    @if (request('sort_by') === 'status')
                                        <span>{{ request('sort_order') === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>
                            @if (auth()->user()->isCEO())
                                <th
                                    class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">
                                    Added By
                                </th>
                            @endif
                            <!-- ترتيب بالتاريخ -->
                            <th
                                class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'added_at', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'added_at' ? 'desc' : 'asc']) }}"
                                    class="flex items-center justify-center gap-1 hover:text-blue-500">
                                    Added At
                                    @if (request('sort_by', 'added_at') === 'added_at')
                                        <span>{{ request('sort_order', 'desc') === 'asc' ? '▲' : '▼' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th
                                class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($customers as $customer)
                            <tr
                                class="bg-white dark:bg-gray-800 hover:bg-blue-50/30 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="p-3 text-center">
                                    <span
                                        class="font-bold text-sm text-gray-900 dark:text-white">{{ $customer->name }}</span>
                                </td>

                                <td class="p-3 text-center text-sm text-gray-600 dark:text-gray-300">
                                    {{ $customer->phone }}
                                </td>

                                <td class="p-3 text-center">
                                    <span
                                        class="px-2 py-1 rounded text-[10px] bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                        {{ $customer->source ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="p-3 text-center">
                                    @php
                                        $statusClasses = match ($customer->status) {
                                            'new' => 'bg-blue-50 text-blue-700 border-blue-100',
                                            'contacted' => 'bg-amber-50 text-amber-700 border-amber-100',
                                            'converted' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                            'lost' => 'bg-rose-50 text-rose-700 border-rose-100',
                                            default => 'bg-gray-50 text-gray-700 border-gray-100',
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide border shadow-sm {{ $statusClasses }}">
                                        {{ $customer->status }}
                                    </span>
                                </td>

                                @if (auth()->user()->isCEO())
                                    <td class="p-3 text-center text-sm text-gray-600 dark:text-gray-400">
                                        {{ $customer->creator->name ?? 'System' }}
                                    </td>
                                @endif

                                <td class="p-3 text-center text-gray-400 text-xs">
                                    {{ \Carbon\Carbon::parse($customer->added_at)->format('M d, Y H:i') }}
                                </td>

                                <td class="p-3 text-center align-middle">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Actions placeholder -->
                                        <span class="text-xs text-gray-400">Edit / Delete</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->isCEO() ? '7' : '6' }}"
                                    class="p-10 text-center text-gray-400 italic">
                                    No potential customers found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Section -->
        <div class="shrink-0 pt-4 mt-2 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
            <!-- استخدام appends للحفاظ على فلاتر البحث عند التنقل بين الصفحات -->
            {{ $customers->appends(request()->query())->links() }}
        </div>

        <!-- Custom Confirmation Modal (UI) -->
        <div x-show="confirmModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50"
            x-cloak x-transition>
            <div @click.away="confirmModal = false"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-sm w-full p-6">
                <div
                    class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h4 class="text-lg font-bold text-center text-gray-900 dark:text-white" x-text="modalTitle"></h4>
                <p class="mt-2 text-sm text-center text-gray-500 dark:text-gray-400" x-text="modalMessage"></p>
                <div class="flex gap-3 mt-6">
                    <button @click="confirmModal = false"
                        class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 transition-colors">Cancel</button>
                    <button @click="document.getElementById(formToSubmit).submit()" :class="confirmColor"
                        class="flex-1 px-4 py-2 text-sm font-medium text-white rounded-lg shadow-sm hover:opacity-90 transition-colors">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>
