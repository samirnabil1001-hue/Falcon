<x-app-layout>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 md:p-6">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
            User Management
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px] border-collapse">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-xs font-bold">User
                        </th>
                        <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-xs font-bold">Email
                        </th>
                        <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-xs font-bold">Role
                        </th>
                        <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-xs font-bold">Account
                            Status</th>
                        <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-xs font-bold">
                            Verification</th>
                        <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-xs font-bold">Joined
                            At</th>
                        <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-xs font-bold">Actions
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr
                            class="border-b transition-all 
                            {{ !$user->is_active
                                ? 'bg-gray-100/50 dark:bg-gray-900/50 border-gray-300 dark:border-gray-800 grayscale-[0.8]'
                                : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:bg-blue-50/30 dark:hover:bg-gray-700' }}">

                            <!-- User Name -->
                            <td class="p-3 text-center">
                                <div class="flex flex-col items-center">
                                    <span
                                        class="font-bold {{ !$user->is_active ? 'text-gray-400 line-through decoration-red-400' : 'text-gray-900 dark:text-white' }}">
                                        {{ $user->name }}
                                    </span>
                                    {{-- @if (!$user->is_active)
                                        <span class="text-[9px] text-red-500 font-bold uppercase tracking-widest">Disabled</span>
                                    @endif --}}
                                </div>
                            </td>

                            <!-- Email -->
                            <td
                                class="p-3 text-center {{ !$user->is_active ? 'text-gray-400 italic' : 'text-gray-600 dark:text-gray-300' }}">
                                {{ $user->email }}
                            </td>

                            <!-- Role -->
                            <td class="p-3 text-center">
                                @php $role = $user->role?->value ?? 'normal'; @endphp
                                <span
                                    class="px-2 py-1 rounded text-[10px] font-bold uppercase shadow-sm
                                    {{ !$user->is_active
                                        ? 'bg-gray-300 text-gray-600 dark:bg-gray-700 dark:text-gray-500'
                                        : ($role === 'CEO'
                                            ? 'bg-red-500 text-white'
                                            : ($role === 'TeamLead'
                                                ? 'bg-blue-500 text-white'
                                                : 'bg-gray-500 text-white')) }}">
                                    {{ $role }}
                                </span>
                            </td>

                            <!-- Account Status -->
                            <td class="p-3 text-center" dir="ltr">
                                @if ($user->is_active)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                        <span class="w-2 h-2 me-2 bg-green-500 rounded-full animate-pulse"></span>
                                        Active
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-200 text-gray-500 border border-gray-300">
                                        <span class="w-2 h-2 me-2 bg-gray-400 rounded-full"></span> Inactive
                                    </span>
                                @endif
                            </td>

                            <!-- Verification -->
                            <td class="p-3 text-center" dir="ltr">
                                @if ($user->hasVerifiedEmail())
                                    <span
                                        class="{{ !$user->is_active ? 'text-gray-400' : 'text-green-500' }} text-xs font-bold flex items-center justify-center">
                                        <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Verified
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs italic">Pending</span>
                                @endif
                            </td>

                            <!-- Joined At -->
                            <td class="p-3 text-center text-gray-400 text-xs">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="p-3 text-center">
                                <div class="flex items-center justify-center gap-2 ">
                                    <form class=" {{ !$user->is_active ? 'hidden' : '' }}" dir="ltr" action="{{ route('users.update-role', $user->id) }}"
                                        method="POST" id="role-form-{{ $user->id }}">
                                        @csrf
                                        @method('PATCH')

                                        <div class="relative inline-block w-full min-w-[130px]">
                                            <select name="role" onchange="this.form.submit()"
                                                {{ !$user->is_active ? 'disabled' : '' }}
                                                class="  appearance-none w-full text-[11px] font-bold uppercase tracking-wider rounded-lg ps-3 pe-8 py-1.5 cursor-pointer transition-all duration-200 border-0 focus:ring-2 focus:ring-offset-2
                                                    {{ !$user->is_active
                                                        ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                                        : ($user->role?->value === 'CEO'
                                                            ? 'bg-red-50 text-red-600 focus:ring-red-500'
                                                            : ($user->role?->value === 'TeamLead'
                                                                ? 'bg-blue-50 text-blue-600 focus:ring-blue-500'
                                                                : ($user->role?->value === 'Agent'
                                                                    ? 'bg-emerald-50 text-emerald-600 focus:ring-emerald-500'
                                                                    : 'bg-gray-50 text-gray-600 focus:ring-gray-500'))) }} shadow-sm hover:shadow-md">

                                                @foreach (App\Enums\UserRole::cases() as $role)
                                                    <option value="{{ $role->value }}"
                                                        {{ $user->role === $role ? 'selected' : '' }}
                                                        class="bg-white text-gray-800 normal-case font-medium">
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <div
                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 
                                                        {{ !$user->is_active ? 'text-gray-400' : ($user->role?->value === 'CEO' ? 'text-red-500' : ($user->role?->value === 'TeamLead' ? 'text-blue-500' : 'text-emerald-500')) }}">
                                                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </form>
                                    <form action="{{ route('users.toggle-status', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to {{ $user->is_active ? 'Deactivate' : 'Activate' }} this user?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="px-4 py-1.5 text-xs font-bold text-white rounded-md shadow-md transition-all
                                            {{ $user->is_active
                                                ? 'bg-amber-500 hover:bg-amber-600 shadow-amber-100 dark:shadow-none'
                                                : 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-200 dark:shadow-none' }}">
                                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete {{ $user->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-4 py-1.5 text-xs font-bold text-white bg-rose-600 rounded-md shadow-md hover:bg-rose-700 shadow-rose-200 dark:shadow-none transition-all {{ !$user->is_active ? 'opacity-50' : '' }}">
                                            Delete
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-10 text-center text-gray-500 italic font-medium">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                    No users found in the system.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
