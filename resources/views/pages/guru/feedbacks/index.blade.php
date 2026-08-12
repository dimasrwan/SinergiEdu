<x-layouts.app>
    <x-slot:title>Feedback Siswa</x-slot:title>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <x-page-header title="Feedback Siswa" description="Berikan umpan balik konstruktif untuk siswa Anda secara personal." />
            <div class="pb-6">
                <x-button variant="primary" as="a" href="{{ route('guru.feedbacks.create') }}">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tulis Feedback Baru
                </x-button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm text-emerald-800 flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($feedbacks as $feedback)
                <x-card padding="none" class="flex flex-col hover:shadow-md transition-shadow duration-300">
                    <div class="p-5 flex-1">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-lg border border-blue-200 shrink-0">
                                    {{ substr($feedback->student->user->name ?? 'S', 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 text-sm line-clamp-1" title="{{ $feedback->student->user->name ?? '-' }}">
                                        {{ $feedback->student->user->name ?? '-' }}
                                    </h3>
                                    <p class="text-xs text-slate-500">{{ $feedback->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <x-badge :variant="$feedback->type === 'positive' ? 'success' : ($feedback->type === 'negative' ? 'danger' : 'secondary')">
                                {{ $feedback->type_label }}
                            </x-badge>
                        </div>
                        
                        <div class="mb-2">
                            <a href="{{ route('guru.feedbacks.show', $feedback) }}" class="text-base font-bold text-slate-800 hover:text-blue-600 transition-colors line-clamp-1">
                                {{ $feedback->title }}
                            </a>
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
                    </div>
                    <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-end gap-3 rounded-b-3xl">
                        <a href="{{ route('guru.feedbacks.edit', $feedback) }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">
                            Edit
                        </a>
                        <form action="{{ route('guru.feedbacks.destroy', $feedback) }}" method="POST" onsubmit="return confirm('Hapus feedback ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800 transition-colors">Hapus</button>
                        </form>
                    </div>
                </x-card>
            @empty
                <div class="col-span-full">
                    <x-card padding="lg" class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Belum Ada Feedback</h3>
                        <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Anda belum pernah memberikan feedback kepada siswa.</p>
                    </x-card>
                </div>
            @endforelse
        </div>
        <div class="mt-6">{{ $feedbacks->links() }}</div>
    </div>
</x-layouts.app>
