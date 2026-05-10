<x-app-layout>
    <x-slot:title>Logs</x-slot:title>

    <div class="container mx-auto py-8">
        <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md">
            <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">User</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Method</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">URL</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">IP Address</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                    @foreach ($logs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-medium {{ $log->user ? 'text-blue-800' : 'text-red-500 italic' }}">
                                    {{ $log->user?->name ?? 'Guest' }}
                                    </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $color = match ($log->method) {
                                        'POST' => 'text-blue-700 bg-blue-50',
                                        'DELETE' => 'text-red-700 bg-red-50',
                                        'PUT', 'PATCH' => 'text-yellow-700 bg-yellow-50',
                                        default => 'text-green-700 bg-green-50',
                                    };
                                @endphp
                                <span
                                    class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-semibold {{ $color }}">
                                    {{ $log->method }}
                                </span>
                            </td>
                            <td class="px-6 py-4 italic text-gray-400">
                                {{ $log->url }}
                            </td>
                            <td class="px-6 py-4">
                                <code
                                    class="text-xs bg-gray-100 p-1 rounded text-gray-600">{{ $log->ip_address }}</code>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">

                                    <div class="font-medium text-gray-700">
                                        {{ $log->created_at->format('Y-m-d H:i') }}
                                    </div>

                                    <div class="text-gray-400 text-xs ">
                                        {{ $log->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $logs->onEachSide(1)->links() }}
        </div>
    </div>
</x-app-layout>
