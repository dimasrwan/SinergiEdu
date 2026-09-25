<x-layouts.app>
    <x-slot:title>Edit Laporan Kinerja</x-slot:title>

    <div class="w-full">
        <div class="mb-6">
            <a href="{{ route('pengawas.reports.index') }}"
                class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali ke Daftar
            </a>
            <h1 class="text-2xl font-bold text-slate-950 mt-4">Edit Laporan Kinerja</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui detail laporan kinerja sekolah atau guru.</p>
        </div>

        <form action="{{ route('pengawas.reports.update', $report) }}" method="POST"
            class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-semibold text-slate-700 mb-2">Judul Laporan Kinerja
                        <span class="text-red-500">*</span></label>
                    <x-text-input id="title" name="title" type="text" :value="old('title', $report->title)" required
                        class="w-full" />
                    @error('title')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="teacher_id" class="block text-sm font-semibold text-slate-700 mb-2">Sasaran Guru
                        (Opsional)</label>
                    <x-searchable-select id="teacher_id" name="teacher_id" placeholder="– Seluruh Sekolah / Bukan Guru Spesifik –" :selected="old('teacher_id', $report->teacher_id)" :options="$teachers->map(fn($t) => ['value' => $t->id, 'label' => $t->user?->name])->toArray()" />
                    @error('teacher_id')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="class_id" class="block text-sm font-semibold text-slate-700 mb-2">Sasaran Kelas
                        (Opsional)</label>
                    <x-select id="class_id" name="class_id" placeholder="– Seluruh Sekolah / Bukan Kelas Spesifik –" :selected="old('class_id', $report->class_id)" :options="$classrooms->map(fn($c) => ['value' => $c->id, 'label' => $c->name])->toArray()" />
                    @error('class_id')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="content" class="block text-sm font-semibold text-slate-700 mb-2">Isi Laporan / Hasil
                    Evaluasi Kinerja <span class="text-red-500">*</span></label>
                <textarea id="content" name="content" rows="10" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:bg-white transition duration-150 resize-none">{{ old('content', $report->content) }}</textarea>
                @error('content')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="recommendations" class="block text-sm font-semibold text-slate-700 mb-2">Rekomendasi Tindak
                    Lanjut</label>
                <textarea id="recommendations" name="recommendations" rows="5"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:bg-white transition duration-150 resize-none">{{ old('recommendations', $report->recommendations) }}</textarea>
                @error('recommendations')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-primary hover:bg-blue-800 rounded-lg transition shadow-sm">
                    Simpan Laporan
                </button>
                <a href="{{ route('pengawas.reports.index') }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>