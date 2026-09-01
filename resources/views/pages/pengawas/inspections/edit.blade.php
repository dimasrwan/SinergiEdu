<x-layouts.app>
    <x-slot:title>Edit & Catat Hasil Inspeksi</x-slot:title>

    <div class="w-full">
        <div class="mb-6">
            <a href="{{ route('pengawas.inspections.index') }}"
                class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <h1 class="text-2xl font-bold text-slate-950 mt-4">Edit & Catat Hasil Inspeksi</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui detail jadwal supervisi atau tuliskan temuan pasca inspeksi.
            </p>
        </div>

        <form action="{{ route('pengawas.inspections.update', $inspection) }}" method="POST"
            class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-semibold text-slate-700 mb-2">Agenda / Judul Inspeksi
                        <span class="text-red-500">*</span></label>
                    <x-text-input id="title" name="title" type="text" :value="old('title', $inspection->title)" required
                        class="w-full" />
                    @error('title')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="teacher_id" class="block text-sm font-semibold text-slate-700 mb-2">Guru yang Diawasi
                        <span class="text-red-500">*</span></label>
                    <select id="teacher_id" name="teacher_id" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" @selected(old('teacher_id', $inspection->teacher_id) == $teacher->id)>{{ $teacher->user?->name }}</option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="class_id" class="block text-sm font-semibold text-slate-700 mb-2">Kelas
                        Kunjungan</label>
                    <select id="class_id" name="class_id"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                        <option value="">Seluruh Sekolah</option>
                        @foreach($classrooms as $cls)
                            <option value="{{ $cls->id }}" @selected(old('class_id', $inspection->class_id) == $cls->id)>
                                {{ $cls->name }}</option>
                        @endforeach
                    </select>
                    @error('class_id')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="inspection_date" class="block text-sm font-semibold text-slate-700 mb-2">Waktu Kunjungan
                        <span class="text-red-500">*</span></label>
                    <input id="inspection_date" type="datetime-local" name="inspection_date" required
                        value="{{ old('inspection_date', $inspection->inspection_date->format('Y-m-d\TH:i')) }}"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                    @error('inspection_date')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-700 mb-2">Status <span
                            class="text-red-500">*</span></label>
                    <select id="status" name="status" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                        <option value="scheduled" @selected(old('status', $inspection->status) === 'scheduled')>Terjadwal
                        </option>
                        <option value="in_progress" @selected(old('status', $inspection->status) === 'in_progress')>Sedang
                            Berjalan</option>
                        <option value="completed" @selected(old('status', $inspection->status) === 'completed')>Selesai
                        </option>
                        <option value="cancelled" @selected(old('status', $inspection->status) === 'cancelled')>Dibatalkan
                        </option>
                    </select>
                    @error('status')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi / Rencana
                    Fokus Supervisi</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-cyan-500 focus:bg-white transition duration-150 resize-none">{{ old('description', $inspection->description) }}</textarea>
                @error('description')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="border-t border-slate-100 pt-5">
                <label for="notes" class="block text-sm font-bold text-slate-800 mb-2">📝 Catatan Hasil Supervisi /
                    Temuan Lapangan</label>
                <textarea id="notes" name="notes" rows="6"
                    placeholder="Tuliskan temuan pembelajaran, evaluasi metode mengajar guru, kesiapan siswa, rekomendasi perbaikan, serta tindak lanjut yang disarankan..."
                    class="w-full px-4 py-3 bg-blue-50/20 border border-blue-100 rounded-2xl text-slate-800 text-sm focus:outline-none focus:border-primary focus:bg-white transition duration-150 resize-none">{{ old('notes', $inspection->notes) }}</textarea>
                @error('notes')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-primary hover:bg-blue-800 rounded-xl transition shadow-sm">
                    Simpan Perubahan
                </button>
                <a href="{{ route('pengawas.inspections.index') }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>