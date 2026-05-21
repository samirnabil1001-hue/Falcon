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
                    User Management
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Manage system users and their roles</p>
            </div>
            <div
                class="text-sm font-medium bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-3 py-1 rounded-full">
                Total: {{ $users->total() }}
            </div>
        </div>

        <!-- Table Container -->
        <div
            class="flex-1 h-0 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <div class="h-full overflow-auto">
                <table class="w-full min-w-[1000px] border-collapse">
                    <!-- Sticky Header -->
                    <thead class="sticky top-0 z-20 bg-gray-100 dark:bg-gray-700 shadow-sm">
                        <tr>
                            <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">User</th>
                            <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">Email</th>
                            <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">Role</th>
                            <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">Status</th>
                            <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">Verification</th>
                            <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">Joined At</th>
                            @if (auth()->user()->role === \App\Enums\UserRole::CEO)
                                <th class="p-3 text-center text-gray-700 dark:text-gray-200 uppercase text-[11px] font-bold tracking-wider">Actions</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($users as $user)
                            <tr class="transition-colors {{ !$user->is_active ? 'bg-gray-100/50 dark:bg-gray-900/40 grayscale-[0.6]' : 'bg-white dark:bg-gray-800 hover:bg-blue-50/30 dark:hover:bg-gray-700/50' }}">

                                <td class="p-3 text-center">
                                    <span class="font-bold text-sm {{ !$user->is_active ? 'text-gray-400 line-through' : 'text-gray-900 dark:text-white' }}">
                                        {{ $user->name }}
                                    </span>
                                </td>

                                <td class="p-3 text-center text-sm {{ !$user->is_active ? 'text-gray-400 italic' : 'text-gray-600 dark:text-gray-300' }}">
                                    {{ $user->email }}
                                </td>

                                <td class="p-3 text-center">
                                    @php
                                        $role = $user->role?->value ?? 'normal';
                                        $roleClasses = !$user->is_active
                                            ? 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400'
                                            : match ($role) {
                                                'CEO' => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                                'TeamLead' => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                                default => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                            };
                                    @endphp

                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide shadow-sm border border-transparent {{ $roleClasses }}">
                                        {{ $role }}
                                    </span>
                                </td>

                                <td class="p-3 text-center">
                                    @if ($user->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide border shadow-sm bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800">
                                            <span class="w-1.5 h-1.5 me-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide border shadow-sm bg-slate-100 text-slate-500 border-slate-200 dark:bg-slate-800 dark:text-slate-500 dark:border-slate-700">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="p-3 text-center">
                                    @if ($user->hasVerifiedEmail())
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border shadow-sm {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800' : 'bg-gray-100 text-gray-500 border-gray-200 dark:bg-gray-800 dark:text-gray-500 dark:border-gray-700' }}">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border shadow-sm bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Pending
                                        </span>
                                    @endif
                                </td>

                                <td class="p-3 text-center text-gray-400 text-xs">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>

                                @if (auth()->user()->role === \App\Enums\UserRole::CEO)
                                    <!-- Actions -->
                                    <td class="p-3 text-center align-middle">
                                        <div class="flex items-center justify-center gap-1.5">

                                            <!-- Update Role -->
                                            <form dir="ltr" id="role-form-{{ $user->id }}" action="{{ route('users.update-role', $user->id) }}" method="POST" class="m-0 flex items-center">
                                                @csrf @method('PATCH')
                                                <div class="relative flex items-center">
                                                    <select name="role"
                                                        @change="openConfirm('Change Role', 'Are you sure you want to change the role for {{ $user->name }}?', 'role-form-{{ $user->id }}', 'bg-blue-600')"
                                                        {{ !$user->is_active ? 'disabled' : '' }}
                                                        class="h-8 w-24 text-[11px] font-bold uppercase rounded-md ps-2 pe-6 border-gray-200 dark:border-gray-600 focus:ring-1 transition-all py-0 leading-none {{ !$user->is_active ? 'bg-gray-100 text-gray-400' : 'bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 cursor-pointer hover:border-blue-400' }}">
                                                        <option value="" disabled selected>{{ $user->role->name ?? 'SELECT' }}</option>
                                                        @foreach (App\Enums\UserRole::cases() as $roleOption)
                                                            @if ($user->role?->value !== $roleOption->value)
                                                                <option value="{{ $roleOption->value }}">{{ $roleOption->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </form>

                                            <!-- Toggle Status (Icon) -->
                                            <form id="status-form-{{ $user->id }}" action="{{ route('users.toggle-status', $user->id) }}" method="POST" class="m-0 flex items-center">
                                                @csrf @method('PATCH')
                                                <button type="button"
                                                    title="{{ $user->is_active ? 'Deactivate User' : 'Activate User' }}"
                                                    @click="openConfirm('{{ $user->is_active ? 'Deactivate' : 'Activate' }} User', 'Do you want to change status for {{ $user->name }}?', 'status-form-{{ $user->id }}', '{{ $user->is_active ? 'bg-amber-500' : 'bg-emerald-600' }}')"
                                                    class="h-8 w-8 rounded-md shadow-sm border transition-all active:scale-95 flex items-center justify-center {{ $user->is_active ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 border-amber-200 dark:border-amber-800/50 hover:bg-amber-500 hover:text-white' : 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 border-emerald-200 dark:border-emerald-800/50 hover:bg-emerald-600 hover:text-white' }}">
                                                    
                                                    @if($user->is_active)
                                                        <!-- Deactivate Icon (Eye Off / Block) -->
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                        </svg>
                                                    @else
                                                        <!-- Activate Icon (Check / CheckCircle) -->
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>

                                            <!-- Delete (Icon) -->
                                            <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="m-0 flex items-center">
                                                @csrf @method('DELETE')
                                                <button type="button"
                                                    title="Delete User"
                                                    @click="openConfirm('Delete User', 'This action is permanent. Are you sure you want to delete {{ $user->name }}?', 'delete-form-{{ $user->id }}', 'bg-rose-600')"
                                                    {{ !$user->is_active ? 'disabled' : '' }}
                                                    class="h-8 w-8 rounded-md shadow-sm border transition-all active:scale-95 flex items-center justify-center bg-rose-50 dark:bg-rose-900/20 text-rose-600 border-rose-200 dark:border-rose-800/50 hover:bg-rose-600 hover:text-white {{ !$user->is_active ? 'opacity-40 cursor-not-allowed' : '' }}">
                                                    <!-- Trash Icon -->
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-10 text-center text-gray-400 italic">No users found in the system.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Section -->
        <div class="shrink-0 pt-4 mt-2 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
            {{ $users->links() }}
        </div>

        <!-- Custom Confirmation Modal (UI) -->
        <div x-show="confirmModal"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 overflow-y-auto" x-cloak
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100">

            <div @click.away="confirmModal = false"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-sm w-full p-6 transform transition-all">

                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h4 class="text-lg font-bold text-center text-gray-900 dark:text-white" x-text="modalTitle"></h4>
                <p class="mt-2 text-sm text-center text-gray-500 dark:text-gray-400" x-text="modalMessage"></p>

                <div class="flex gap-3 mt-6">
                    <button @click="confirmModal = false"
                        class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button @click="document.getElementById(formToSubmit).submit()" :class="confirmColor"
                        class="flex-1 px-4 py-2 text-sm font-medium text-white rounded-lg shadow-sm hover:opacity-90 transition-colors">
                        Confirm
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {}
        });
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>