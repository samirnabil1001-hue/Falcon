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
                <a href="{{ route('potential-customers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 px-4 rounded-lg transition-colors">
                    + Add New Customer
                </a>
                <div class="text-sm font-medium bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-3 py-1 rounded-full">
                    Total: {{ $customers->total() }}
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="flex-1 h-0 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <div class="h-full overflow-auto">
                <table class="w-full min-w-[1000px] border-collapse">
                    <!-- Sticky Header -->
                    <thead class="sticky top-0 z-20 bg-gray-100 dark:bg-gray-700 shadow-sm">
                        <tr>
                            <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">Customer Name</th>
                            <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">Phone</th>
                            <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">Source</th>
                            <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">Status</th>
                            <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">Added By</th>
                            <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">Added At</th>
                            <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($customers as $customer)
                            <tr class="bg-white dark:bg-gray-800 hover:bg-blue-50/30 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="p-3 text-center">
                                    <span class="font-bold text-sm text-gray-900 dark:text-white">{{ $customer->name }}</span>
                                </td>

                                <td class="p-3 text-center text-sm text-gray-600 dark:text-gray-300">
                                    {{ $customer->phone }}
                                </td>

                                <td class="p-3 text-center">
                                    <span class="px-2 py-1 rounded text-[10px] bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                        {{ $customer->source ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="p-3 text-center">
                                    @php
                                        $statusClasses = match($customer->status) {
                                            'new' => 'bg-blue-50 text-blue-700 border-blue-100',
                                            'contacted' => 'bg-amber-50 text-amber-700 border-amber-100',
                                            'converted' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                            'lost' => 'bg-rose-50 text-rose-700 border-rose-100',
                                            default => 'bg-gray-50 text-gray-700 border-gray-100'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide border shadow-sm {{ $statusClasses }}">
                                        {{ $customer->status }}
                                    </span>
                                </td>

                                <td class="p-3 text-center text-sm text-gray-600 dark:text-gray-400">
                                    {{ $customer->creator->name ?? 'System' }} <!-- بفرض وجود علاقة اسمها creator -->
                                </td>

                                <td class="p-3 text-center text-gray-400 text-xs">
                                    {{ \Carbon\Carbon::parse($customer->added_at)->format('M d, Y H:i') }}
                                </td>

                                <td class="p-3 text-center align-middle">
                                    <div class="flex items-center justify-center gap-2">
                                   action
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-10 text-center text-gray-400 italic">No potential customers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Section -->
        <div class="shrink-0 pt-4 mt-2 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
            {{ $customers->links() }}
        </div>

        <!-- Custom Confirmation Modal (UI) -->
        <div x-show="confirmModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50" x-cloak x-transition>
            <div @click.away="confirmModal = false" class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-sm w-full p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h4 class="text-lg font-bold text-center text-gray-900 dark:text-white" x-text="modalTitle"></h4>
                <p class="mt-2 text-sm text-center text-gray-500 dark:text-gray-400" x-text="modalMessage"></p>
                <div class="flex gap-3 mt-6">
                    <button @click="confirmModal = false" class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 transition-colors">Cancel</button>
                    <button @click="document.getElementById(formToSubmit).submit()" :class="confirmColor" class="flex-1 px-4 py-2 text-sm font-medium text-white rounded-lg shadow-sm hover:opacity-90 transition-colors">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>