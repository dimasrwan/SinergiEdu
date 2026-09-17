<x-layouts.app>
    <x-slot:title>Buat Aspirasi Komite</x-slot:title>

    <div class="w-full space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Buat Aspirasi Baru</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Sampaikan gagasan, masukan, atau saran komite sekolah.</p>
            </div>
            <div>
                <a href="{{ route('komite.aspirations.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                    &larr; Kembali
                </a>
            </div>
        </div>

        <x-card padding="lg" class="w-full">
            <form action="{{ route('komite.aspirations.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Judul Aspirasi *</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary py-2.5 px-3" placeholder="Contoh: Usulan Peningkatan Sarana Fasilitas Laboratorium">
                    @error('title')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="content" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Isi Aspirasi & Detail Masukan *</label>
                    <textarea name="content" id="content" rows="7" required class="w-full min-h-[160px] sm:min-h-[200px] rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary p-3" placeholder="Jelaskan secara komprehensif latar belakang, saran, atau solusi yang diusulkan oleh Komite Sekolah...">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('komite.aspirations.index') }}" class="w-full sm:w-auto text-center px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="w-full sm:w-auto px-5 py-2.5 text-xs font-bold text-white bg-primary hover:bg-blue-800 rounded-lg shadow-xs transition-colors">
                        Kirim Aspirasi
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
