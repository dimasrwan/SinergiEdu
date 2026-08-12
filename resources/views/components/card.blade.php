@props(['padding' => 'default'])

@php
    $paddingClasses = [
        'none' => 'p-0',
        'sm' => 'p-4 sm:p-5',
        'md' => 'p-5 sm:p-6',
        'default' => 'p-5 sm:p-6',
        'lg' => 'p-6 sm:p-8',
    ];

    $classes = 'bg-surface border border-slate-200 rounded-xl shadow-card ' . $paddingClasses[$padding];
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
