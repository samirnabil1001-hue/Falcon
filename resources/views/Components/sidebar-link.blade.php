@props(['active' => false, 'icon' => ''])

@php
// Define the styles for active vs inactive states
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-3 rounded-lg bg-indigo-50 dark:bg-gray-700 text-indigo-700 dark:text-white transition'
            : 'flex items-center px-4 py-3 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <span class="ml-3 italic text-xs text-gray-400">[{{ $icon }}]</span>
    @endif
    
    {{ $slot }}
</a>