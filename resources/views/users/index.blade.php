<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-white">
            Users List
        </h2>
    </x-slot>

    <!-- Top Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            <h3 class="text-gray-500 dark:text-gray-400 text-sm">
                Total Users
            </h3>

            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                {{ $users->count() }}
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            <h3 class="text-gray-500 dark:text-gray-400 text-sm">
                Verified Users
            </h3>

            <p class="text-3xl font-bold text-green-500 mt-2">
                {{ $users->whereNotNull('email_verified_at')->count() }}
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            <h3 class="text-gray-500 dark:text-gray-400 text-sm">
                Unverified Users
            </h3>

            <p class="text-3xl font-bold text-red-500 mt-2">
                {{ $users->whereNull('email_verified_at')->count() }}
            </p>
        </div>

    </div>

    <!-- Users Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 md:p-6">

        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
            Current Users
        </h3>

        <p class="text-gray-600 dark:text-gray-300 mb-6">
            All registered users in the system.
        </p>

        <!-- Responsive Table -->
        <div class="overflow-x-auto">

            <table class="w-full min-w-[900px] border-collapse">

                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-left">

                        <th class="p-3 text-gray-700 dark:text-gray-200">
                            ID
                        </th>

                        <th class="p-3 text-gray-700 dark:text-gray-200">
                            Name
                        </th>

                        <th class="p-3 text-gray-700 dark:text-gray-200">
                            Email
                        </th>

                        <th class="p-3 text-gray-700 dark:text-gray-200">
                            Role
                        </th>

                        <th class="p-3 text-gray-700 dark:text-gray-200">
                            Verification
                        </th>

                        <th class="p-3 text-gray-700 dark:text-gray-200">
                            Created At
                        </th>

                    </tr>
                </thead>

                <tbody>

                    @forelse($users as $user)

                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">

                            <td class="p-3 text-gray-800 dark:text-gray-100">
                                {{ $user->id }}
                            </td>

                            <td class="p-3 text-gray-800 dark:text-gray-100">
                                {{ $user->name }}
                            </td>

                            <td class="p-3 text-gray-600 dark:text-gray-300">
                                {{ $user->email }}
                            </td>

                            <td class="p-3">

                                @php
                                    $role = $user->role?->value ?? 'normal';
                                @endphp

                                @if($role === 'CEO')
                                    <span class="text-red-500 font-semibold">
                                        CEO
                                    </span>

                                @elseif($role === 'TeamLead')
                                    <span class="text-blue-500 font-semibold">
                                        Team Lead
                                    </span>

                                @elseif($role === 'Agent')
                                    <span class="text-purple-500 font-semibold">
                                        Agent
                                    </span>

                                @else
                                    <span class="text-gray-500 font-semibold">
                                        Normal
                                    </span>
                                @endif

                            </td>

                            <td class="p-3">

                                @if($user->hasVerifiedEmail())

                                    <span class="text-green-500 font-semibold">
                                        Verified
                                    </span>

                                @else

                                    <span class="text-yellow-500 font-semibold">
                                        Pending
                                    </span>

                                @endif

                            </td>

                            <td class="p-3 text-gray-600 dark:text-gray-300">
                                {{ $user->created_at->format('Y-m-d') }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-500 dark:text-gray-400">
                                No users found
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>