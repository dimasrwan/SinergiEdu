<x-layouts.app>
    <x-slot:title>Aspirasi & Masukan Komite</x-slot:title>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Aspirasi & Masukan Komite</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftar masukan dan gagasan yang disampaikan kepada sekolah.</p>
            </div>
            <a href="{{ route('komite.aspirations.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-xs sm:text-sm font-bold text-white bg-primary hover:bg-blue-800 rounded-xl shadow-xs transition-colors self-start sm:self-auto">
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Buat Aspirasi
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center justify-between">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <x-card padding="none" class="overflow-hidden">
            <div class="divide-y divide-slate-100">
                @forelse($aspirations as $aspiration)
                    <div class="p-4 sm:p-5 hover:bg-slate-50/80 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="space-y-1 min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-sm font-bold text-slate-900">
                                    <a href="{{ route('komite.aspirations.show', $aspiration) }}" class="hover:text-primary transition-colors">
                                        {{ $aspiration->title }}
                                    </a>
                                </h3>
                                @if($aspiration->status === 'pending')
                                    <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-bold uppercase tracking-wider border border-amber-200">
                                        Menunggu Tanggapan
                                    </span>
                                @elseif($aspiration->status === 'processed')
                                    <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-wider border border-blue-200">
                                        Sedang Diproses
                                    </span>
                                @elseif($aspiration->status === 'resolved')
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider border border-emerald-200">
                                        Selesai
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider border border-slate-200">
                                        {{ ucfirst($aspiration->status) }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">{{ $aspiration->content }}</p>
                            <p class="text-[11px] text-slate-400 font-medium">Dikirim pada: {{ $aspiration->created_at->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        <a href="{{ route('komite.aspirations.show', $aspiration) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors shrink-0 self-start sm:self-auto">
                            Lihat Detail &rarr;
                        </a>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                        </svg>
                        <h3 class="text-base font-bold text-slate-900">Belum Ada Aspirasi</h3>
                        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Sampaikan masukan, saran, atau aspirasi komite sekolah untuk ditindaklanjuti oleh manajemen sekolah.</p>
                        <div class="mt-4">
                            <a href="{{ route('komite.aspirations.create') }}" class="inline-flex items-center px-4 py-2 text-xs font-bold text-white bg-primary hover:bg-blue-800 rounded-xl transition-colors">
                                + Buat Aspirasi Pertama
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($aspirations->hasPages())
                <div class="px-4 py-3 border-t border-slate-100 bg-slate-50">
                    {{ $aspirations->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
