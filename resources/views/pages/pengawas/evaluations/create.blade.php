<x-layouts.app>
    <x-slot:title>Tulis Evaluasi Baru</x-slot:title>

    <div class="w-full">
        <div class="mb-6">
            <a href="{{ route('pengawas.evaluations.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <h1 class="text-2xl font-bold text-slate-950 mt-4">Tulis Evaluasi Baru</h1>
            <p class="text-sm text-slate-500 mt-1">Isi catatan, temuan supervisi, saran, atau penilaian mutu sekolah.</p>
        </div>

        <form action="{{ route('pengawas.evaluations.store') }}" method="POST" class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700 mb-2">Judul Evaluasi</label>
                <x-text-input id="title" name="title" type="text" :value="old('title')" required placeholder="Contoh: Evaluasi Kinerja Akademik Semester Ganjil" />
                @error('title')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="content" class="block text-sm font-semibold text-slate-700 mb-2">Isi / Detail Evaluasi</label>
                <textarea id="content" name="content" rows="8" required placeholder="Tuliskan temuan pengawasan, masukan kurikulum, atau catatan perbaikan..."
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-cyan-500 focus:bg-white transition duration-150">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="inline-flex items-center justify-center px-5 py-3 text-sm font-semibold text-white bg-cyan-600 hover:bg-cyan-700 rounded-xl transition duration-150 shadow-sm shadow-cyan-200">
                Simpan & Terbitkan Evaluasi
            </button>
        </form>
    </div>
</x-layouts.app>
