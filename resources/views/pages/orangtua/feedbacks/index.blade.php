<x-layouts.app>
    <x-slot:title>Feedback Anak</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Feedback Anak" description="Lihat umpan balik yang diberikan guru kepada anak Anda." />

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($feedbacks as $feedback)
                <x-card padding="none" class="flex flex-col hover:shadow-md transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 font-bold text-lg border border-slate-200 shrink-0">
                                    {{ substr($feedback->student->user->name ?? 'A', 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 text-sm line-clamp-1" title="{{ $feedback->student->user->name ?? '-' }}">
                                        {{ $feedback->student->user->name ?? '-' }}
                                    </h3>
                                    <p class="text-xs text-slate-500">{{ $feedback->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            <x-badge :variant="$feedback->type === 'positive' ? 'success' : ($feedback->type === 'negative' ? 'danger' : 'secondary')">
                                {{ $feedback->type_label }}
                            </x-badge>
                        </div>
                        
                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-slate-800 line-clamp-2">
                                {{ $feedback->title }}
                            </h3>
                        </div>

                        <!-- Message Bubble Style -->
                        <div class="bg-slate-50 border {{ $feedback->type === 'positive' ? 'border-emerald-100' : ($feedback->type === 'negative' ? 'border-red-100' : 'border-slate-100') }} rounded-2xl p-4 text-slate-700 text-sm leading-relaxed whitespace-pre-wrap relative mb-4">
                            <div class="absolute -top-2 left-6 w-4 h-4 {{ $feedback->type === 'positive' ? 'bg-emerald-50 border-t border-l border-emerald-100' : ($feedback->type === 'negative' ? 'bg-red-50 border-t border-l border-red-100' : 'bg-slate-50 border-t border-l border-slate-100') }} transform rotate-45 z-0"></div>
                            <div class="relative z-10">
                                {{ $feedback->message }}
                            </div>
                        </div>

                        <div class="flex items-center gap-4 text-xs text-slate-500 pt-3 border-t border-slate-100">
                            <div class="flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                Guru: <span class="font-semibold text-slate-700">{{ $feedback->teacher->user->name ?? '-' }}</span>
                            </div>
                            @if($feedback->subject)
                                <div class="flex items-center gap-1.5">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                    </svg>
                                    Mapel: <span class="font-semibold text-slate-700">{{ $feedback->subject->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </x-card>
            @empty
                <div class="col-span-full">
                    <x-card padding="lg" class="text-center py-16 border border-slate-100 bg-white">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 mb-4 ring-8 ring-slate-50/50">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 011.037-.443 48.282 48.282 0 005.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Belum Ada Feedback</h3>
                        <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Anak Anda belum menerima feedback apapun dari guru.</p>
                    </x-card>
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $feedbacks->links() }}</div>
    </div>
</x-layouts.app>
