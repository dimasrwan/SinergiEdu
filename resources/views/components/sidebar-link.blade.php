@props(['active' => false, 'href' => '#'])

@php
$baseClasses = ($active ?? false)
            ? 'relative flex items-center gap-3 px-3.5 py-2.5 min-h-[40px] text-[13px] font-semibold bg-[#123B82] text-white rounded-xl shadow-sm shadow-[#123B82]/20 transition-all duration-200 leading-tight group'
            : 'relative flex items-center gap-3 px-3.5 py-2.5 min-h-[40px] text-[13px] font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 rounded-xl transition-all duration-150 leading-tight group';
@endphp

<a href="{{ $href }}" 
   {{ $attributes->merge(['class' => $baseClasses]) }}>
    @if(isset($icon))
        <div class="{{ $active ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }} shrink-0 transition-colors duration-150 flex items-center justify-center pointer-events-none">
            {{ $icon }}
        </div>
    @endif
    
    <span class="truncate transition-all duration-200">
        {{ $slot }}
    </span>
</a>
