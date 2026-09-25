<x-layouts.app>
    <x-slot:title>Feedback: {{ $feedback->title }}</x-slot:title>

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="mb-4">
            <a href="{{ route('siswa.feedbacks.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-primary gap-1.5 transition mb-3">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Kembali ke Daftar Feedback
            </a>
        </div>

        @php
            $isPositive = $feedback->type === 'positive';
            $isNegative = $feedback->type === 'negative';
            
            if ($isPositive) {
                $badgeColor = 'bg-emerald-50 text-emerald-700';
                $bubbleBg = 'bg-emerald-50/50';
                $bubbleBorder = 'border-emerald-100';
            } elseif ($isNegative) {
                $badgeColor = 'bg-rose-50 text-rose-700';
                $bubbleBg = 'bg-rose-50/50';
                $bubbleBorder = 'border-rose-100';
            } else {
                $badgeColor = 'bg-slate-100 text-slate-700';
                $bubbleBg = 'bg-slate-50';
                $bubbleBorder = 'border-slate-200';
            }
        @endphp

        <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 shadow-sm">
            <div class="mb-8 border-b border-slate-100 pb-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $badgeColor }}">
                        {{ $feedback->type_label ?? $feedback->type }}
                    </span>
                    <span class="text-xs font-semibold text-slate-500">{{ $feedback->created_at->format('d M Y, H:i') }}</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900 leading-tight mb-2">{{ $feedback->title }}</h1>
                @if($feedback->subject)
                    <p class="text-sm font-bold text-primary">{{ $feedback->subject->name }}</p>
                @endif
            </div>

            <div class="flex flex-col sm:flex-row gap-6 items-start">
                <!-- Avatar Sender -->
                <div class="flex flex-col items-center gap-2 shrink-0 sm:w-24">
                    <div class="h-14 w-14 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xl ring-4 ring-slate-50">
                        {{ strtoupper(substr($feedback->teacher->user->name ?? 'G', 0, 2)) }}
                    </div>
                    <div class="text-center">
                        <p class="text-[13px] font-bold text-slate-900 line-clamp-1" title="{{ $feedback->teacher->user->name ?? '-' }}">{{ $feedback->teacher->user->name ?? '-' }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Guru Pengirim</p>
                    </div>
                </div>

                <!-- Message Bubble -->
                <div class="flex-1 w-full relative pt-2 sm:pt-0">
                    <!-- Decorative triangle -->
                    <div class="hidden sm:block absolute top-6 -left-2 w-4 h-4 {{ $bubbleBg }} border-t border-l {{ $bubbleBorder }} transform -rotate-45"></div>
                    
                    <div class="{{ $bubbleBg }} border {{ $bubbleBorder }} rounded-2xl p-6 text-[15px] text-slate-700 leading-relaxed whitespace-pre-wrap relative z-10 shadow-sm">
                        {{ $feedback->message }}
                    </div>
                    
                    <!-- Decorative triangle mobile -->
                    <div class="sm:hidden absolute -top-2 left-6 w-4 h-4 {{ $bubbleBg }} border-t border-l {{ $bubbleBorder }} transform rotate-45"></div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
