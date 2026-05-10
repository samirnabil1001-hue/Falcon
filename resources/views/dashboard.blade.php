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
                Orders
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
            <table class="w-full min-w-[600px] border-collapse">

                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-left">
                        <th class="p-3 text-gray-700 dark:text-gray-200">
                            Name
                        </th>

                        <th class="p-3 text-gray-700 dark:text-gray-200">
                            Email
                        </th>

                        <th class="p-3 text-gray-700 dark:text-gray-200">
                            Status
                        </th>
                    </tr>
                </thead>

                <tbody>

                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <td class="p-3 text-gray-800 dark:text-gray-100">
                            John Doe
                        </td>

                        <td class="p-3 text-gray-600 dark:text-gray-300">
                            john@example.com
                        </td>

                        <td class="p-3 text-green-500 font-semibold">
                            Active
                        </td>
                    </tr>

                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <td class="p-3 text-gray-800 dark:text-gray-100">
                            Sarah Smith
                        </td>

                        <td class="p-3 text-gray-600 dark:text-gray-300">
                            sarah@example.com
                        </td>

                        <td class="p-3 text-yellow-500 font-semibold">
                            Pending
                        </td>
                    </tr>

                </tbody>

            </table>
        </div>

    </div>
</x-app-layout>
