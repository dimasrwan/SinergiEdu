<x-layouts.app>
    <x-slot:title>Tambah Pertemuan Pembelajaran</x-slot:title>

    <div class="mx-auto max-w-3xl space-y-6">
        <a href="{{ route('guru.learning-meetings.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800">← Kembali ke pertemuan pembelajaran</a>
        <x-page-header title="Rencana Pertemuan Pembelajaran" description="Topik dan alat/bahan ini menjadi konteks materi dan penilaian siswa pada pertemuan terkait." />

        @if(! $academicYear || ! $semester)
            <div class="rounded-2xl border border-red-100 bg-red-50 p-4 text-sm text-red-800">Tahun ajaran atau semester aktif belum tersedia.</div>
        @else
            <x-card padding="lg">
                <form action="{{ route('guru.learning-meetings.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div><x-input-label for="class_id" value="Kelas" /><x-select id="class_id" name="class_id" class="mt-1" required><option value="">Pilih kelas</option>@foreach($classes as $class)<option value="{{ $class->id }}" @selected(old('class_id') == $class->id)>{{ $class->name }}</option>@endforeach</x-select><x-input-error :messages="$errors->get('class_id')" class="mt-2" /></div>
                        <div><x-input-label for="subject_id" value="Mata Pelajaran" /><x-select id="subject_id" name="subject_id" class="mt-1" required><option value="">Pilih mata pelajaran</option>@foreach($subjects as $subject)<option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>{{ $subject->name }}</option>@endforeach</x-select><x-input-error :messages="$errors->get('subject_id')" class="mt-2" /></div>
                        <div><x-input-label for="meeting_number" value="Nomor Pertemuan" /><x-text-input id="meeting_number" name="meeting_number" type="number" min="1" :value="old('meeting_number')" class="mt-1 w-full" required /><x-input-error :messages="$errors->get('meeting_number')" class="mt-2" /></div>
                        <div><x-input-label for="meeting_date" value="Tanggal Pertemuan" /><x-text-input id="meeting_date" name="meeting_date" type="date" :value="old('meeting_date', now()->format('Y-m-d'))" class="mt-1 w-full" required /><x-input-error :messages="$errors->get('meeting_date')" class="mt-2" /></div>
                    </div>
                    <div><x-input-label for="topic" value="Topik Pembelajaran" /><x-text-input id="topic" name="topic" :value="old('topic')" class="mt-1 w-full" required placeholder="Contoh: Persamaan linear dua variabel" /><x-input-error :messages="$errors->get('topic')" class="mt-2" /></div>
                    <div><x-input-label for="tools_materials" value="Alat / Bahan" /><x-textarea id="tools_materials" name="tools_materials" rows="3" class="mt-1 w-full" placeholder="Contoh: LKPD, proyektor, papan tulis, video pembelajaran.">{{ old('tools_materials') }}</x-textarea><x-input-error :messages="$errors->get('tools_materials')" class="mt-2" /></div>
                    <div><x-input-label for="notes" value="Catatan Perencanaan (opsional)" /><x-textarea id="notes" name="notes" rows="3" class="mt-1 w-full">{{ old('notes') }}</x-textarea><x-input-error :messages="$errors->get('notes')" class="mt-2" /></div>
                    <div class="flex justify-end"><x-primary-button>Simpan Pertemuan</x-primary-button></div>
                </form>
            </x-card>
        @endif
    </div>
</x-layouts.app>
