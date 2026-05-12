<aside id="sidebar"
    class="fixed md:static top-0 right-0 z-50 w-64 h-full bg-white dark:bg-gray-800 shadow-lg transform translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">

    <!-- Logo Section -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
        <h1 class="text-xl font-bold text-gray-800 dark:text-white">{{ config('app.name') }}</h1>
        <button id="closeBtn" class="md:hidden text-gray-700 dark:text-gray-300">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="p-4 space-y-2">
        <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
            Dashboard
        </x-sidebar-link>
        
        <x-sidebar-link :href="route('users.index')" :active="request()->routeIs('users.*')" icon="users">
            Users
        </x-sidebar-link>

        <x-sidebar-link href="#" icon="briefcase"> Clients </x-sidebar-link>
        <x-sidebar-link href="#" icon="settings"> Settings </x-sidebar-link>
    </nav>
</aside>