@props(['active' => false, 'href' => '#'])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-3 px-4 py-3 text-sm font-semibold bg-[#123B82] text-white rounded-xl shadow-md shadow-[#123B82]/20 transition-all duration-200'
            : 'flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-900 rounded-xl transition duration-150 group';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if(isset($icon))
        <div class="{{ $active ? 'text-white' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors duration-150">
            {{ $icon }}
        </div>
    @endif
    <span>{{ $slot }}</span>
</a>
