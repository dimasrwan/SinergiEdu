<x-layouts.app>
    <x-slot:title>Feedback Strategis</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Feedback Strategis" description="Umpan balik top-down dari kepala sekolah kepada guru, waka, dan pengawas.">
            <x-slot:actions>
                <x-button variant="primary" href="{{ route('kepala-sekolah.feedback.create') }}">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Kirim Feedback
                </x-button>
            </x-slot:actions>
        </x-page-header>

        <div class="space-y-4">
            @forelse($feedbacks as $feedback)
                <a href="{{ route('kepala-sekolah.feedback.show', $feedback) }}" class="block p-5 bg-white border border-slate-200 rounded-xl hover:border-primary hover:shadow-sm transition group">
                    <div class="flex items-center justify-between gap-4 mb-2">
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $feedback->priority_color }}">{{ $feedback->priority_label }}</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700">{{ $feedback->category_label }}</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $feedback->status_color }}">{{ $feedback->status_label }}</span>
                        </div>
                        <span class="text-xs text-slate-400">{{ $feedback->created_at->diffForHumans() }}</span>
                    </div>
                    <h3 class="font-bold text-slate-900 group-hover:text-primary transition">{{ $feedback->title }}</h3>
                    <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $feedback->message }}</p>
                    <p class="text-xs text-slate-400 mt-3">
                        Penerima: <span class="font-semibold text-slate-600">{{ $feedback->recipient?->name ?? 'Umum (' . ($feedback->recipient_role ?? '-') . ')' }}</span>
                    </p>
                </a>
            @empty
                <x-card>
                    <p class="text-sm text-slate-500">Belum ada feedback. Klik "Kirim Feedback" untuk membuat yang baru.</p>
                </x-card>
            @endforelse
        </div>

        <div>
            {{ $feedbacks->links() }}
        </div>
    </div>
</x-layouts.app>