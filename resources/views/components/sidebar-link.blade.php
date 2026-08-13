@props(['active' => false, 'href' => '#'])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-3 px-3 py-2.5 min-h-[36px] text-[13px] font-semibold bg-[#123B82] text-white rounded-xl shadow-sm shadow-[#123B82]/20 transition-all duration-200 leading-tight'
            : 'flex items-center gap-3 px-3 py-2.5 min-h-[36px] text-[13px] font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-900 rounded-xl transition duration-150 group leading-tight';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if(isset($icon))
        <div class="{{ $active ? 'text-white' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors duration-150">
            {{ $icon }}
        </div>
    @endif
    <span>{{ $slot }}</span>
</a>
