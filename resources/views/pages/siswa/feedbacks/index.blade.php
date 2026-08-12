<x-layouts.app>
    <x-slot:title>Feedback dari Guru</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Feedback dari Guru" description="Baca umpan balik yang diberikan oleh guru-guru Anda." />

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($feedbacks as $feedback)
                <x-card padding="none" class="flex flex-col hover:shadow-md transition-shadow duration-300">
                    <a href="{{ route('siswa.feedbacks.show', $feedback) }}" class="block p-5 flex-1">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 font-bold text-lg border border-slate-200 shrink-0">
                                    {{ substr($feedback->teacher->user->name ?? 'G', 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 text-sm line-clamp-1" title="{{ $feedback->teacher->user->name ?? '-' }}">
                                        {{ $feedback->teacher->user->name ?? '-' }}
                                    </h3>
                                    <p class="text-xs text-slate-500">{{ $feedback->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <x-badge :variant="$feedback->type === 'positive' ? 'success' : ($feedback->type === 'negative' ? 'danger' : 'secondary')">
                                {{ $feedback->type_label }}
                            </x-badge>
                        </div>
                        
                        <div class="mb-2">
                            <h3 class="text-base font-bold text-slate-800 hover:text-blue-600 transition-colors line-clamp-1">
                                {{ $feedback->title }}
                            </h3>
                        </div>
                        <p class="text-sm text-slate-600 line-clamp-3 mb-4">
                            {{ $feedback->message }}
                        </p>
                        @if($feedback->subject)
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-50 text-xs font-medium text-slate-600 border border-slate-200">
                                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                </svg>
                                {{ $feedback->subject->name }}
                            </div>
                        @endif
                    </a>
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
                        <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Anda belum menerima feedback apapun dari guru-guru Anda.</p>
                    </x-card>
                </div>
            @endforelse
        </div>
        <div class="mt-6">{{ $feedbacks->links() }}</div>
    </div>
</x-layouts.app>
