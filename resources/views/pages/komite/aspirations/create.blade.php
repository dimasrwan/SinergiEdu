<x-layouts.app>
    <x-slot:title>Buat Aspirasi Komite</x-slot:title>

    <div class="space-y-6 max-w-3xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Buat Aspirasi Baru</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Sampaikan gagasan, masukan, atau saran komite sekolah.</p>
            </div>
            <a href="{{ route('komite.aspirations.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
                &larr; Kembali
            </a>
        </div>

        <x-card padding="md">
            <form action="{{ route('komite.aspirations.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Judul Aspirasi *</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary" placeholder="Contoh: Usulan Peningkatan Sarana Fasilitas Laboratorium">
                    @error('title')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="content" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Isi Aspirasi & Detail Masukan *</label>
                    <textarea name="content" id="content" rows="6" required class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary" placeholder="Jelaskan secara komprehensif latar belakang, saran, atau solusi yang diusulkan oleh Komite Sekolah...">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <a href="{{ route('komite.aspirations.index') }}" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-primary hover:bg-blue-800 rounded-lg shadow-xs transition-colors">
                        Kirim Aspirasi
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
