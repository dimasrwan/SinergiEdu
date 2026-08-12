<x-layouts.app>
    <x-slot:title>Tambah Semester</x-slot:title>

    <div class="w-full">
        <div class="mb-6">
            <a href="{{ route('waka.semesters.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <x-page-header title="Tambah Semester Baru" description="Buat data semester baru dan hubungkan ke tahun ajaran yang sesuai." />
        </div>

        <x-card padding="lg">
            <form action="{{ route('waka.semesters.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="name" :value="__('Nama Semester')" />
                    <x-text-input id="name" name="name" type="text" :value="old('name')" placeholder="Contoh: Ganjil / Genap" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="academic_year_id" :value="__('Tahun Ajaran')" />
                    <x-select id="academic_year_id" name="academic_year_id" required>
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>{{ $year->year }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error :messages="$errors->get('academic_year_id')" class="mt-2" />
                </div>

                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <x-checkbox id="is_active" name="is_active" value="1" />
                        <span class="text-sm text-slate-700">Set sebagai semester aktif</span>
                    </label>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <x-button variant="secondary" href="{{ route('waka.semesters.index') }}">Batal</x-button>
                    <x-button variant="primary" type="submit">Simpan Semester</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
