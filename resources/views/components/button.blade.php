@props(['variant' => 'primary', 'size' => 'md', 'type' => 'button', 'href' => null])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold tracking-tight transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:active:scale-100';
    
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-xs rounded-md gap-1.5',
        'md' => 'px-4 py-2 text-sm rounded-lg gap-2',
        'lg' => 'px-6 py-3 text-base rounded-xl gap-2',
    ];

    $variantClasses = [
        'primary' => 'bg-primary text-white hover:bg-primary-hover shadow-sm focus:ring-primary',
        'secondary' => 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 hover:text-slate-900 shadow-sm focus:ring-slate-200',
        'accent' => 'bg-accent text-white hover:bg-accent-hover shadow-sm focus:ring-accent',
        'danger' => 'bg-danger text-white hover:bg-red-700 shadow-sm focus:ring-danger',
        'ghost' => 'bg-transparent text-slate-600 hover:bg-slate-100 hover:text-slate-900 focus:ring-slate-200',
    ];

    $classes = $baseClasses . ' ' . $sizeClasses[$size] . ' ' . $variantClasses[$variant];
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
