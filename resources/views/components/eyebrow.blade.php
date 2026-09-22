@props([
    'variant' => 'light', // 'light' (landing page: #123B82 text) or 'dark' (login panel: #119FEA text)
])

@php
    $baseClasses = "inline-flex items-center gap-2 text-xs font-bold tracking-[0.1em] uppercase";
    $textClasses = match($variant) {
        'dark' => "text-[#119FEA]",
        default => "text-[#123B82]",
    };
@endphp

<div {{ $attributes->merge(['class' => "$baseClasses $textClasses"]) }}>
    <span class="w-[2.5px] h-4 rounded-full bg-[#119FEA] shrink-0 inline-block"></span>
    <span>{{ $slot }}</span>
</div>
