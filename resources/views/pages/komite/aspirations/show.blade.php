<x-layouts.app>
    <x-slot:title>Detail Aspirasi Komite</x-slot:title>

    <div class="space-y-6 max-w-3xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Detail Aspirasi</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftar informasi aspirasi dan tanggapan dari sekolah.</p>
            </div>
            <a href="{{ route('komite.aspirations.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
                &larr; Kembali
            </a>
        </div>

        <x-card padding="md" class="space-y-6">
            <div class="pb-4 border-b border-slate-100">
                <div class="flex items-center justify-between gap-2 mb-2">
                    <span class="text-xs text-slate-400 font-medium">Dikirim pada: {{ $aspiration->created_at->translatedFormat('d M Y, H:i') }}</span>
                    @if($aspiration->status === 'pending')
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 text-xs font-bold border border-amber-200">
                            Menunggu Tanggapan
                        </span>
                    @elseif($aspiration->status === 'processed')
                        <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200">
                            Sedang Diproses
                        </span>
                    @elseif($aspiration->status === 'resolved')
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                            Selesai
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">
                            {{ ucfirst($aspiration->status) }}
                        </span>
                    @endif
                </div>
                <h2 class="text-lg sm:text-xl font-bold text-slate-900">{{ $aspiration->title }}</h2>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Isi Aspirasi</label>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-sm text-slate-800 leading-relaxed whitespace-pre-line">
                    {{ $aspiration->content }}
                </div>
            </div>

            <!-- Tanggapan Sekolah -->
            <div class="pt-4 border-t border-slate-100">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Tanggapan Sekolah</label>
                @if($aspiration->responses->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($aspiration->responses as $response)
                            <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-200 text-sm text-slate-800 leading-relaxed">
                                <p class="text-xs font-bold text-primary mb-1">{{ $response->user->name }}</p>
                                <div class="whitespace-pre-line text-slate-700">
                                    {{ $response->message }}
                                </div>
                                <p class="text-[11px] text-slate-400 mt-2 font-medium">{{ $response->created_at->translatedFormat('d M Y, H:i') }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/60 text-xs text-slate-500 italic">
                        Belum ada tanggapan resmi dari pihak sekolah.
                    </div>
                @endif
            </div>
        </x-card>
    </div>
</x-layouts.app>
