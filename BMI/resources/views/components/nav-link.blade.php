@props(['active', 'activeClass' => null])

@php
$defaultActive = 'nav-item active flex items-center px-4 py-3 text-sm font-medium text-gray-900 bg-teal-50 rounded-xl';
$defaultInactive = 'nav-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-xl transition-all duration-200';

$classes = ($active ?? false)
            ? ($activeClass ?? $defaultActive)
            : $defaultInactive;
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
