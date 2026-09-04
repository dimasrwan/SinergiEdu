<x-layouts.app>
    <x-slot:title>Tulis Evaluasi Sekolah</x-slot:title>

    <div class="max-w-3xl space-y-6 mx-auto">
        <div>
            <a href="{{ route('kepala-sekolah.evaluasi.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-primary gap-1.5 transition-colors mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Kembali ke Daftar Evaluasi
            </a>
            <x-page-header title="Tulis Evaluasi Sekolah" description="Dokumentasikan hasil evaluasi untuk perbaikan mutu sekolah." />
        </div>

        <x-card padding="lg">
            <form action="{{ route('kepala-sekolah.evaluasi.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="title" :value="__('Judul')" />
                    <x-text-input id="title" name="title" value="{{ old('title') }}" required placeholder="Judul evaluasi" class="w-full" />
                    @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <x-input-label for="content" :value="__('Isi Evaluasi')" />
                    <textarea id="content" name="content" rows="8" required placeholder="Tuliskan hasil evaluasi secara mendetail..."
                        class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">{{ old('content') }}</textarea>
                    @error('content') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <x-button variant="primary" type="submit">
                        Simpan Evaluasi
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>