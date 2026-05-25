<aside id="sidebar"
    class="fixed md:static top-0 right-0 z-50 w-64 h-full bg-white dark:bg-gray-800 shadow-lg transform translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">

    <!-- Logo Section -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
        <h1 class="text-xl font-bold text-gray-800 dark:text-white">فالكون</h1>
        <button id="closeBtn" class="md:hidden text-gray-700 dark:text-gray-300">
            falcon
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="p-4 space-y-2">
        <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
            لوحة التحكم
        </x-sidebar-link>

        <x-sidebar-link :href="route('users.index')" :active="request()->routeIs('users.*')" icon="users">
            الموظفين
        </x-sidebar-link>

        <x-sidebar-link :href="route('potential-customers.index')" :active="request()->routeIs('potential-customers.index')" icon="users">
            العملاء
        </x-sidebar-link>

        <x-sidebar-link :href="route('potential-customer-services.index')" :active="request()->routeIs('potential-customer-services.*')" icon="service">
            الخدمات
        </x-sidebar-link>
        <x-sidebar-link :href="route('customer-follow-ups.index')" :active="request()->routeIs('customer-follow-ups.*')" icon="clock">
            سجلات العملاء
        </x-sidebar-link>

        <x-sidebar-link :href="route('profile.edit')" icon="settings">
            الإعدادت
        </x-sidebar-link>
    </nav>
</aside>
