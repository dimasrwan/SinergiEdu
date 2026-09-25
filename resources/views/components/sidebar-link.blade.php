@props(['active' => false, 'href' => '#'])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-3 px-3.5 py-2.5 min-h-[40px] text-[13px] font-semibold bg-[#123B82] text-white rounded-xl shadow-sm shadow-[#123B82]/20 transition-all duration-200 leading-tight'
            : 'flex items-center gap-3 px-3.5 py-2.5 min-h-[40px] text-[13px] font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 rounded-xl transition-all duration-150 group leading-tight';
@endphp

<a href="{{ $href }}" 
   {{ $attributes->merge(['class' => $classes]) }}
   :class="sidebarMini ? 'lg:justify-center lg:px-0' : 'lg:justify-start lg:px-3.5'"
   title="{{ strip_tags($slot) }}">
    @if(isset($icon))
        <div class="{{ $active ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }} shrink-0 transition-colors duration-150 flex items-center justify-center">
            {{ $icon }}
        </div>
    @endif
    <span class="truncate transition-all duration-200" 
          :class="sidebarMini ? 'lg:hidden' : 'inline'">
        {{ $slot }}
    </span>
</a>
