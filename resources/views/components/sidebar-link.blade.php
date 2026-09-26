@props(['active' => false, 'href' => '#'])

@php
$baseClasses = ($active ?? false)
            ? 'relative flex items-center gap-3 py-2.5 min-h-[40px] text-[13px] font-semibold bg-[#123B82] text-white rounded-xl shadow-sm shadow-[#123B82]/20 transition-all duration-200 leading-tight group'
            : 'relative flex items-center gap-3 py-2.5 min-h-[40px] text-[13px] font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 rounded-xl transition-all duration-150 leading-tight group';
$label = trim(strip_tags((string)$slot));
@endphp

<a href="{{ $href }}" 
   {{ $attributes->merge(['class' => $baseClasses]) }}
   :class="sidebarMini ? 'lg:justify-center lg:px-0 lg:w-full' : 'lg:justify-start lg:px-3.5 px-3.5'"
   x-data="{ showTooltip: false }"
   @mouseenter="if (sidebarMini && window.innerWidth >= 1024) showTooltip = true"
   @mouseleave="showTooltip = false">
    @if(isset($icon))
        <div class="{{ $active ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }} shrink-0 transition-colors duration-150 flex items-center justify-center pointer-events-none">
            {{ $icon }}
        </div>
    @endif
    
    <span class="truncate transition-all duration-200" 
          :class="sidebarMini ? 'lg:hidden' : 'inline'">
        {{ $slot }}
    </span>

    <!-- Tooltip for Mini Sidebar State -->
    <template x-if="sidebarMini">
        <div x-show="showTooltip"
             x-cloak
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-x-1"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-1"
             class="hidden lg:flex fixed left-[76px] z-50 items-center px-3 py-1.5 text-xs font-semibold text-white bg-slate-900/95 backdrop-blur-xs rounded-lg shadow-xl pointer-events-none whitespace-nowrap border border-slate-700/50">
            <span>{{ $label }}</span>
        </div>
    </template>
</a>
