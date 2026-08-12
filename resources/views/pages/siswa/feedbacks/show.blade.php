<x-layouts.app>
    <x-slot:title>Feedback: {{ $feedback->title }}</x-slot:title>

    <div class="w-full">
        <div class="mb-6">
            <a href="{{ route('siswa.feedbacks.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Kembali
            </a>
        </div>

        <x-card padding="none" class="overflow-hidden mb-6">
            <div class="bg-slate-50/50 p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <x-badge :variant="$feedback->type === 'positive' ? 'success' : ($feedback->type === 'negative' ? 'danger' : 'secondary')">
                            {{ $feedback->type_label }}
                        </x-badge>
                        <span class="text-xs font-semibold text-slate-500">{{ $feedback->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <h1 class="text-2xl font-bold text-slate-950">{{ $feedback->title }}</h1>
                </div>
            </div>

            <div class="p-6">
                <!-- Metadata Thread Style -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-6 mb-8 text-sm">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                            {{ substr($feedback->teacher->user->name ?? 'G', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">{{ $feedback->teacher->user->name ?? '-' }}</p>
                            <p class="text-xs text-slate-500">Guru Pengirim</p>
                        </div>
                    </div>
                    <div class="hidden sm:block text-slate-300">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold">
                            {{ substr(auth()->user()->name ?? 'S', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500">Siswa Penerima (Anda)</p>
                        </div>
                    </div>
                </div>

                @if($feedback->subject)
                    <div class="mb-6 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-50/50 text-blue-700 text-xs font-semibold border border-blue-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                        Mata Pelajaran: {{ $feedback->subject->name }}
                    </div>
                @endif

                <!-- Message Bubble -->
                <div class="bg-slate-50 border {{ $feedback->type === 'positive' ? 'border-emerald-100 bg-emerald-50/30' : ($feedback->type === 'negative' ? 'border-red-100 bg-red-50/30' : 'border-slate-100') }} rounded-2xl p-6 text-slate-700 text-sm leading-relaxed whitespace-pre-wrap relative">
                    <!-- Decorative element for chat bubble style -->
                    <div class="absolute -top-3 left-8 w-6 h-6 {{ $feedback->type === 'positive' ? 'bg-emerald-50/30 border-t border-l border-emerald-100' : ($feedback->type === 'negative' ? 'bg-red-50/30 border-t border-l border-red-100' : 'bg-slate-50 border-t border-l border-slate-100') }} transform rotate-45"></div>
                    <div class="relative z-10">
                        {{ $feedback->message }}
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</x-layouts.app>
