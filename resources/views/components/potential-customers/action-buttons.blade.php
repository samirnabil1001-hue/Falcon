<div class="flex items-center gap-1 w-[65px] h-[30px] justify-center">
    @if ($currentStatusValue == \App\Enums\PotentialCustomerStatus::CONTACTED->value)
        <button type="button" @click="showModal = true"
            class="p-1.5 text-emerald-500 hover:text-emerald-600 dark:text-emerald-400 dark:hover:text-emerald-300 rounded-lg hover:bg-emerald-50 dark:hover:bg-slate-700 transition-colors flex items-center justify-center"
            title="إضافة تفاصيل التواصل">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
        </button>
    @else
        <button type="button" disabled
            class="p-1.5 text-gray-300 dark:text-slate-600 rounded-lg cursor-not-allowed flex items-center justify-center"
            title="المتابعة متاحة فقط لحالة تم التواصل">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
        </button>
    @endif

    <a href="{{ route('potential-customers.edit', $customer->id) }}"
        class="p-1.5 text-gray-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center"
        title="Edit Customer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
    </a>
</div>