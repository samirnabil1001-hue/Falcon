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