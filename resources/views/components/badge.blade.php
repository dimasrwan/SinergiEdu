@props(['variant' => 'slate'])

@php
    $baseClasses = 'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold tracking-wide uppercase border';
    
    $variantClasses = [
        'slate' => 'bg-slate-50 text-slate-700 border-slate-200',
        'primary' => 'bg-blue-50 text-primary border-blue-200',
        'accent' => 'bg-sky-50 text-accent border-sky-200',
        'success' => 'bg-emerald-50 text-success border-emerald-200',
        'warning' => 'bg-amber-50 text-warning border-amber-200',
        'danger' => 'bg-red-50 text-danger border-red-200',
    ];

    $classes = $baseClasses . ' ' . $variantClasses[$variant];
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
