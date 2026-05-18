<x-app-layout>
    <div class="bg-slate-50 dark:bg-slate-950 min-h-screen text-right" dir="rtl">
        <div class="mx-auto bg-white dark:bg-slate-900 shadow-xl rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800/80 transition-colors duration-300">

            <x-follow-ups.header :totalCount="$customers->total()" />

            <x-follow-ups.alert-messages 
                :success="session('success')" 
                :error="$errors->first('error')" 
            />

            <x-follow-ups.filter-panel 
                :search="request('search')"
                :status="request('status')"
                :sortBy="request('sort_by', 'created_at')"
                :sortOrder="request('sort_order', 'desc')"
            />

            <!-- جدول البيانات المطور -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-right text-slate-600 dark:text-slate-300">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => request('sort_by') === 'name' && request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" 
                                   class="inline-flex items-center gap-1 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">
                                    العميل
                                    @if (request('sort_by') === 'name')
                                        <span class="text-xs">{{ request('sort_order') === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-4 font-semibold">رقم الهاتف</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_order' => request('sort_by') === 'status' && request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" 
                                   class="inline-flex items-center gap-1 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">
                                    الحالة الحالية
                                    @if (request('sort_by') === 'status')
                                        <span class="text-xs">{{ request('sort_order') === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'follow_ups_count', 'sort_order' => request('sort_by') === 'follow_ups_count' && request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" 
                                   class="inline-flex items-center gap-1 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">
                                    إجمالي المتابعات
                                    @if (request('sort_by') === 'follow_ups_count')
                                        <span class="text-xs">{{ request('sort_order') === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_by') === 'created_at' && request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" 
                                   class="inline-flex items-center gap-1 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">
                                    آخر إجراء مسجل
                                    @if (request('sort_by', 'created_at') === 'created_at')
                                        <span class="text-xs">{{ request('sort_order', 'desc') === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @forelse($customers as $customer)
                            <tr class="bg-white dark:bg-slate-900 hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition-colors duration-200">
                                
                                <!-- اسم العميل -->
                                <td class="px-6 py-4 font-medium text-slate-900 dark:text-white whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-violet-50 dark:bg-violet-950/50 text-violet-600 dark:text-violet-400 flex items-center justify-center font-bold text-sm border border-violet-100/50 dark:border-violet-900/30">
                                            {{ mb_substr($customer->name, 0, 1) }}
                                        </div>
                                        <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $customer->name }}</span>
                                    </div>
                                </td>

                                <!-- رقم الهاتف -->
                                <td class="px-6 py-4 whitespace-nowrap text-left text-slate-600 dark:text-slate-400 font-mono tracking-wide" dir="ltr">
                                    {{ $customer->phone }}
                                </td>

                                <!-- الحالة الحالية -->
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <x-follow-ups.status-badge :status="$customer->status" />
                                </td>

                                <!-- عدد المتابعات التراكمي -->
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 rounded-lg border border-slate-200/60 dark:border-slate-700/60">
                                        {{ $customer->follow_ups_count }} إجراءات
                                    </span>
                                </td>

                                <!-- تفاصيل آخر خطوة -->
                                <td class="px-6 py-4 text-center text-xs max-w-xs truncate">
                                    @if ($customer->followUps->first())
                                        <div class="text-slate-700 dark:text-slate-300 font-medium truncate">
                                            {{ $customer->followUps->first()->reason ?? 'بدون سبب معين' }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 flex items-center justify-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $customer->followUps->first()->created_at?->diffForHumans() }}
                                        </div>
                                    @else
                                        <span class="text-slate-300 dark:text-slate-600 italic font-normal">لا توجد متابعات سابقة</span>
                                    @endif
                                </td>

                                <!-- الإجراءات -->
                                <td class="px-6 py-4 text-center whitespace-nowrap" x-data="{ openModal: false }">
                                    @php
                                        $isContacted = $customer->status === \App\Enums\PotentialCustomerStatus::CONTACTED ||
                                                       $customer->status === 'contacted' ||
                                                       (is_object($customer->status) && $customer->status->value === 'contacted');
                                    @endphp

                                    <div class="inline-flex rounded-xl shadow-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-0.5 gap-1">
                                        
                                        <button @click="openModal = true"
                                            @if(!$isContacted) disabled @endif
                                            class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all flex items-center gap-1.5 shadow-sm 
                                            {{ $isContacted 
                                                ? 'bg-violet-600 hover:bg-violet-700 text-white shadow-violet-200 dark:shadow-none cursor-pointer' 
                                                : 'bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-600 cursor-not-allowed shadow-none' }}">
                                            <svg class="w-3.5 h-3.5 {{ $isContacted ? 'text-white' : 'text-slate-300 dark:text-slate-700' }}" 
                                                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                            </svg>
                                            إجراء متابعة
                                        </button>

                                        <a href="{{ route('customer-follow-ups.show', $customer->id) }}"
                                            class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-600 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-300 text-xs font-semibold rounded-lg transition-all flex items-center gap-1.5 border border-slate-200/40 dark:border-slate-700/40">
                                            <svg class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            عرض السجل
                                        </a>
                                    </div>

                                    @if($isContacted)
                                        <x-follow-ups.follow-up-modal :customer="$customer" />
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-slate-400 dark:text-slate-500 font-medium">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-2xl">
                                            <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5" />
                                            </svg>
                                        </div>
                                        <span class="text-sm">لا يوجد أي عملاء محتملين مسجلين في النظام حالياً.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- الترقيم والصفحات للجدول -->
            <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800/60">
                {{ $customers->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</x-app-layout>