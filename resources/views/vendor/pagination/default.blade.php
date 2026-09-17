@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex flex-col sm:flex-row items-center justify-between gap-3 px-4 py-3 bg-white border-t border-slate-200/80">
        {{-- Counter Info --}}
        <div class="text-xs sm:text-sm text-slate-600 text-center sm:text-left">
            Menampilkan
            @if ($paginator->firstItem())
                <span class="font-semibold text-slate-900">{{ $paginator->firstItem() }}</span>
                sampai
                <span class="font-semibold text-slate-900">{{ $paginator->lastItem() }}</span>
            @else
                <span class="font-semibold text-slate-900">{{ $paginator->count() }}</span>
            @endif
            dari
            <span class="font-semibold text-slate-900">{{ $paginator->total() }}</span>
            hasil
        </div>

        {{-- Page Buttons Container --}}
        <div class="flex items-center gap-1 flex-wrap justify-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="Halaman sebelumnya" class="inline-flex items-center justify-center min-w-[38px] h-[38px] sm:min-w-[36px] sm:h-[36px] px-2.5 text-xs font-semibold text-slate-300 bg-slate-50 border border-slate-200 rounded-lg cursor-not-allowed select-none">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya" class="inline-flex items-center justify-center min-w-[38px] h-[38px] sm:min-w-[36px] sm:h-[36px] px-2.5 text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span aria-disabled="true" class="inline-flex items-center justify-center min-w-[38px] h-[38px] sm:min-w-[36px] sm:h-[36px] px-2 text-xs font-semibold text-slate-400 select-none">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex items-center justify-center min-w-[38px] h-[38px] sm:min-w-[36px] sm:h-[36px] px-3 text-xs font-bold text-white bg-primary border border-primary rounded-lg shadow-xs select-none">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" aria-label="Ke halaman {{ $page }}" class="inline-flex items-center justify-center min-w-[38px] h-[38px] sm:min-w-[36px] sm:h-[36px] px-3 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman selanjutnya" class="inline-flex items-center justify-center min-w-[38px] h-[38px] sm:min-w-[36px] sm:h-[36px] px-2.5 text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            @else
                <span aria-disabled="true" aria-label="Halaman selanjutnya" class="inline-flex items-center justify-center min-w-[38px] h-[38px] sm:min-w-[36px] sm:h-[36px] px-2.5 text-xs font-semibold text-slate-300 bg-slate-50 border border-slate-200 rounded-lg cursor-not-allowed select-none">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
