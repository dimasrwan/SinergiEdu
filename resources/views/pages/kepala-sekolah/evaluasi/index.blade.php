<x-layouts.app>
    <x-slot:title>Evaluasi Sekolah</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Evaluasi Sekolah" description="Catatan evaluasi internal untuk pengembangan mutu sekolah.">
            <x-slot:actions>
                <x-button variant="primary" href="{{ route('kepala-sekolah.evaluasi.create') }}">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Tulis Evaluasi
                </x-button>
            </x-slot:actions>
        </x-page-header>

        <div class="space-y-4">
            @forelse($evaluations as $evaluation)
                <a href="{{ route('kepala-sekolah.evaluasi.show', $evaluation) }}" class="block p-5 bg-white border border-slate-200 rounded-xl hover:border-primary hover:shadow-md transition group">
                    <div class="flex items-center justify-between gap-4 mb-2">
                        <span class="text-xs text-slate-400">{{ $evaluation->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <h3 class="font-bold text-slate-900 group-hover:text-primary transition">{{ $evaluation->title }}</h3>
                    <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $evaluation->content }}</p>
                    <p class="text-xs text-slate-400 mt-3">Oleh: <span class="font-semibold text-slate-600">{{ $evaluation->user?->name ?? 'Pengawas' }}</span></p>
                </a>
            @empty
                <x-card>
                    <p class="text-sm text-slate-500">Belum ada evaluasi. Klik "Tulis Evaluasi" untuk membuat yang baru.</p>
                </x-card>
            @endforelse
        </div>

        <div>
            {{ $evaluations->links() }}
        </div>
    </div>
</x-layouts.app>