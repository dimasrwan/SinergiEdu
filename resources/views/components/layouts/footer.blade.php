@props(['variant' => 'light', 'as' => 'footer'])

@php
    $isDark = $variant === 'dark';
    $tag = $as;
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $isDark 
    ? 'bg-[#0B1733] text-[#AFC4DA] py-6 border-t border-[#123B82]/40 text-xs sm:text-sm shrink-0' 
    : 'bg-white border-t border-slate-200/50 py-4 sm:py-6 mt-auto shrink-0 text-xs sm:text-sm text-slate-500']) }}>
    <div class="px-4 sm:px-6 lg:px-8 text-center">
        <p class="font-medium {{ $isDark ? 'text-[#AFC4DA]' : 'text-slate-500' }} inline-flex flex-wrap items-center justify-center gap-x-1.5 gap-y-1">
            <span>Copyright &copy; {{ date('Y') }} SinergiEdu. developed by</span>
            <a href="https://ideolog.tech/" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="font-bold focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $isDark 
                   ? 'text-white hover:text-[#119FEA] focus:ring-[#119FEA]' 
                   : 'text-slate-700 hover:text-blue-600 focus:ring-blue-500' }} transition-colors rounded-sm inline-block">IdeologTech</a>
        </p>
    </div>
</{{ $tag }}>
