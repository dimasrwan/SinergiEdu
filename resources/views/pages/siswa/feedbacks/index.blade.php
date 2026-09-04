<x-layouts.app>
    <x-slot:title>Feedback dari Guru</x-slot:title>

    <div class="w-full space-y-6">
        
        <!-- Header -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 mb-1">Pesan & Umpan Balik</h1>
                <p class="text-slate-500 text-sm max-w-xl">Baca pesan, evaluasi, dan apresiasi dari guru-guru Anda.</p>
            </div>
            <div class="shrink-0 bg-slate-50 border border-slate-100 px-4 py-2 rounded-xl text-center">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Total Feedback</span>
                <span class="block text-sm font-bold text-primary">{{ $feedbacks->total() }} Pesan</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($feedbacks as $feedback)
                @php
                    $isPositive = $feedback->type === 'positive';
                    $isNegative = $feedback->type === 'negative';
                    
                    if ($isPositive) {
                        $borderColor = 'border-l-4 border-l-emerald-400 border-y-slate-200 border-r-slate-200';
                        $badgeColor = 'bg-emerald-50 text-emerald-700';
                    } elseif ($isNegative) {
                        $borderColor = 'border-l-4 border-l-rose-400 border-y-slate-200 border-r-slate-200';
                        $badgeColor = 'bg-rose-50 text-rose-700';
                    } else {
                        $borderColor = 'border border-slate-200';
                        $badgeColor = 'bg-slate-100 text-slate-700';
                    }
                @endphp
                <a href="{{ route('siswa.feedbacks.show', $feedback) }}" class="block bg-white {{ $borderColor }} rounded-2xl p-5 flex flex-col h-full shadow-sm hover:shadow-md transition group">
                    <div class="flex items-start justify-between mb-4 gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="h-10 w-10 shrink-0 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr($feedback->teacher->user->name ?? 'G', 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-slate-900 text-sm truncate group-hover:text-primary transition-colors">
                                    {{ $feedback->teacher->user->name ?? '-' }}
                                </h3>
                                <p class="text-[11px] text-slate-500 truncate">{{ $feedback->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $badgeColor }}">
                            {{ $feedback->type_label ?? $feedback->type }}
                        </span>
                    </div>
                    
                    @if($feedback->subject)
                        <span class="inline-flex text-[10px] font-bold text-primary bg-blue-50 px-2 py-0.5 rounded-md uppercase tracking-wider mb-2 w-fit">
                            {{ $feedback->subject->name }}
                        </span>
                    @endif
                    
                    <h4 class="text-[15px] font-bold text-slate-800 mb-1.5">{{ $feedback->title }}</h4>
                    <p class="text-[13px] text-slate-600 line-clamp-3 flex-grow leading-relaxed">
                        "{{ $feedback->message }}"
                    </p>
                </a>
            @empty
                <div class="col-span-full">
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl py-12 px-6 flex flex-col items-center text-center max-w-2xl mx-auto">
                        <div class="h-16 w-16 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center mb-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 011.037-.443 48.282 48.282 0 005.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Feedback</h3>
                        <p class="text-sm text-slate-500">Anda belum menerima pesan atau umpan balik apapun dari guru-guru Anda.</p>
                    </div>
                </div>
            @endforelse
        </div>
        
        @if($feedbacks->hasPages())
            <div class="mt-8">
                {{ $feedbacks->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
