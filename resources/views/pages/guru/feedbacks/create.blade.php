<x-layouts.app>
    <x-slot:title>Tulis Feedback Baru</x-slot:title>

    <div class="max-w-3xl space-y-6 mx-auto">
        <div>
            <a href="{{ route('guru.feedbacks.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-blue-600 gap-1.5 transition-colors mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Kembali ke Daftar Feedback
            </a>
            <x-page-header title="Tulis Feedback Baru" description="Berikan umpan balik untuk siswa Anda secara personal." />
        </div>

        <x-card padding="lg">
            <form action="{{ route('guru.feedbacks.store') }}" method="POST" class="space-y-6">
            @csrf

                <div>
                    <x-input-label for="student_id" :value="__('Pilih Siswa')" />
                    <x-select id="student_id" name="student_id" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>{{ $student->user->name }}</option>
                        @endforeach
                    </x-select>
                    @error('student_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <x-input-label for="subject_id" :value="__('Mata Pelajaran (Opsional)')" />
                    <x-select id="subject_id" name="subject_id">
                        <option value="">-- Umum / Tanpa Mapel --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </x-select>
                    @error('subject_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <x-input-label for="type" :value="__('Tipe Feedback')" />
                <div class="flex gap-4 mt-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <x-radio name="type" value="positive" :checked="old('type', 'positive') === 'positive'" />
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 group-hover:bg-emerald-200 transition-colors">Positif</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <x-radio name="type" value="neutral" :checked="old('type') === 'neutral'" />
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-800 group-hover:bg-slate-200 transition-colors">Netral</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <x-radio name="type" value="negative" :checked="old('type') === 'negative'" />
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 group-hover:bg-red-200 transition-colors">Negatif</span>
                    </label>
                </div>
                @error('type') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <x-input-label for="title" :value="__('Judul')" />
                <x-text-input id="title" name="title" value="{{ old('title') }}" required placeholder="Contoh: Peningkatan yang luar biasa di kelas!" />
                @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <x-input-label for="message" :value="__('Isi Feedback')" />
                <textarea id="message" name="message" rows="5" required placeholder="Tuliskan umpan balik Anda secara mendetail..."
                    class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">{{ old('message') }}</textarea>
                @error('message') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <x-button variant="primary" type="submit">
                    Kirim Feedback
                </x-button>
            </div>
        </form>
        </x-card>
    </div>
</x-layouts.app>
