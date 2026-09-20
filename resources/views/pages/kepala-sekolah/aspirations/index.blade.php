<x-layouts.app>
    <x-slot:title>Aspirasi Komite</x-slot:title>

    <div class="space-y-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Aspirasi Komite Sekolah</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftar masukan dan gagasan yang disampaikan oleh Komite Sekolah.</p>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <x-card padding="none" class="overflow-hidden">
            <div class="divide-y divide-slate-100">
                @forelse($aspirations as $aspiration)
                    <div class="p-4 sm:p-5 hover:bg-slate-50/80 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="space-y-1 min-w-0 flex-1">
                            <h3 class="text-sm font-bold text-slate-900">
                                <a href="{{ route('kepala-sekolah.aspirations.show', $aspiration) }}" class="hover:text-primary transition-colors">
                                    {{ $aspiration->title }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-500">Oleh: {{ $aspiration->user->name }}</p>
                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">{{ $aspiration->content }}</p>
                            <p class="text-[11px] text-slate-400 font-medium">Dikirim pada: {{ $aspiration->created_at->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        <a href="{{ route('kepala-sekolah.aspirations.show', $aspiration) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors shrink-0">
                            Lihat &rarr;
                        </a>
                    </div>
                @empty
                    <div class="p-12 text-center text-slate-500 text-xs">Belum ada aspirasi dari komite sekolah.</div>
                @endforelse
            </div>
        </x-card>
    </div>
</x-layouts.app>
