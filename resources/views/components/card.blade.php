@props(['padding' => 'default'])

@php
    $paddingClasses = [
        'none' => 'p-0',
        'sm' => 'p-3.5 sm:p-4',
        'md' => 'p-4 sm:p-5 lg:p-6',
        'default' => 'p-4 sm:p-5 lg:p-6',
        'lg' => 'p-5 sm:p-6 lg:p-8',
    ];

    $classes = 'bg-surface border border-slate-200 rounded-xl shadow-card min-w-0 max-w-full box-border ' . $paddingClasses[$padding];
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>

