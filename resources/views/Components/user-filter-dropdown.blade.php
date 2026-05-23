@props(['users'])

<div class="relative" id="custom-dropdown-container">
    <input type="hidden" name="user_id" id="hidden-user-id" value="{{ request('user_id') }}">
    
    <button type="button" id="dropdown-btn" onclick="toggleDropdown()"
        class="w-full text-right flex justify-between items-center text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all cursor-pointer py-2.5 px-3 disabled:opacity-50 disabled:cursor-not-allowed">
        <span id="dropdown-label">
            @if(request('user_id') && $users->firstWhere('id', request('user_id')))
                {{ $users->firstWhere('id', request('user_id'))->name }}
            @else
                جميع الموظفين
            @endif
        </span>
        <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" id="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div id="dropdown-menu" class="hidden absolute z-50 mt-1 w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-lg overflow-hidden">
        <!-- خانة البحث الداخلية -->
        <div class="p-2 border-b border-slate-100 dark:border-slate-700">
            <input type="text" id="dropdown-search" oninput="filterDropdownOptions()" placeholder="ابحث عن موظف (أحمد، عبدالله...)"
                class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 px-3 py-1.5 focus:outline-none focus:border-violet-500">
        </div>
        <!-- خيارات الموظفين مع الـ Scroll -->
        <div id="options-list" class="max-h-[190px] overflow-y-auto text-sm text-slate-700 dark:text-slate-200">
            <div onclick="selectUser('', 'جميع الموظفين')" class="option-item px-3 py-2 hover:bg-violet-50 dark:hover:bg-violet-950/40 cursor-pointer transition-colors" data-name="">
                جميع الموظفين
            </div>
            @if($users)
                @foreach ($users as $user)
                    @if($user->id !== auth()->id())
                        <div onclick="selectUser('{{ $user->id }}', '{{ $user->name }}')" 
                             class="option-item px-3 py-2 hover:bg-violet-50 dark:hover:bg-violet-950/40 cursor-pointer transition-colors {{ request('user_id') == $user->id ? 'bg-violet-500/10 font-medium text-violet-600' : '' }}" 
                             data-name="{{ $user->name }}">
                            {{ $user->name }}
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>