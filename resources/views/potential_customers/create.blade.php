{{-- resources/views/potential-customers/create.blade.php --}}

<x-app-layout>
    <div dir="rtl" class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8 text-right">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-950 dark:text-white tracking-tight">
                    إضافة عميل محتمل
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    إنشاء وحفظ ملف تعريف جديد في خطة العملاء الخاصة بك.
                </p>
            </div>

            <a href="{{ route('potential-customers.index') }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700/50 transition-all duration-200">
                <span>رجوع</span>
                <svg class="w-4 h-4 ms-2 transform rtl:-scale-x-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200/80 dark:border-gray-800 overflow-hidden">
            
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                    تفاصيل العميل الجديد
                </h3>
            </div>

            <div class="p-6">
                <x-potential-customers.form />
            </div>

        </div>
    </div>
</x-app-layout>