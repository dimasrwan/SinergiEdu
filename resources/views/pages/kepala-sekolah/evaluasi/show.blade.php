<x-layouts.app>
    <x-slot:title>Detail Evaluasi</x-slot:title>

    <div class="max-w-3xl space-y-6 mx-auto">
        <div>
            <a href="{{ route('kepala-sekolah.evaluasi.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-primary gap-1.5 transition-colors mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Kembali ke Daftar Evaluasi
            </a>
            <x-page-header :title="$evaluation->title" :description="'Oleh ' . ($evaluation->user?->name ?? 'Pengawas') . ' • ' . $evaluation->created_at->format('d M Y H:i')" />
        </div>

        <x-card>
            <div class="prose prose-sm max-w-none text-slate-700 whitespace-pre-line">{{ $evaluation->content }}</div>
        </x-card>
    </div>
</x-layouts.app>