<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Welcome
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h1 class="text-4xl font-bold mb-4">
                        Laravel Application
                    </h1>

                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">
                        Laravel application is working successfully.
                    </p>

                    @guest
                        <div class="flex gap-4">
                            <a href="{{ route('login') }}"
                               class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Login
                            </a>

                            <a href="{{ route('register') }}"
                               class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                Register
                            </a>
                        </div>
                    @else
                        <div class="flex items-center gap-4">
                            <span class="text-green-500 font-semibold">
                                You are logged in as {{ Auth::user()->name }}
                            </span>

                            <a href="{{ route('dashboard') }}"
                               class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                Go to Dashboard
                            </a>
                        </div>
                    @endguest

                </div>
            </div>

        </div>
    </div>
</x-app-layout>