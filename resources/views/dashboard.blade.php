<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-white">
            Dashboard
        </h2>
    </x-slot>

    <!-- Top Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            <h3 class="text-gray-500 dark:text-gray-400 text-sm">
                Total Users
            </h3>

            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                1,245
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            <h3 class="text-gray-500 dark:text-gray-400 text-sm">
                clients
            </h3>

            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                320
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            <h3 class="text-gray-500 dark:text-gray-400 text-sm">
                Revenue
            </h3>

            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                $8,420
            </p>
        </div>

    </div>

    <!-- Content -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 md:p-6">

        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
            Welcome Back
        </h3>

        <p class="text-gray-600 dark:text-gray-300 mb-6">
            This is your responsive dashboard layout.
        </p>

        <!-- Responsive Table -->
        <div class="overflow-x-auto">
           
        </div>

    </div>
</x-app-layout>
