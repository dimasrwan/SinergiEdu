<x-layouts.app>
    <x-slot:title>Tulis Evaluasi Baru</x-slot:title>

    <div class="w-full">
        <div class="mb-6">
            <a href="{{ route('pengawas.evaluations.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali ke Daftar
            </a>
            <h1 class="text-2xl font-bold text-slate-950 mt-4">Tulis Evaluasi Baru</h1>
            <p class="text-sm text-slate-500 mt-1">Isi catatan, temuan supervisi, saran, atau penilaian mutu sekolah.</p>
        </div>

        <form action="{{ route('pengawas.evaluations.store') }}" method="POST" class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm space-y-6">
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
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:bg-white transition duration-150">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="inline-flex items-center justify-center px-4 py-4 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition duration-150 shadow-sm shadow-sm-200">
                Simpan & Terbitkan Evaluasi
            </button>
        </form>
    </div>
</x-layouts.app>
