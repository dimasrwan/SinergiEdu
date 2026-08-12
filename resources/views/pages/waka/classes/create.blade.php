<x-layouts.app>
    <x-slot:title>Tambah Kelas</x-slot:title>

    <div class="w-full">
        <div class="mb-6">
            <a href="{{ route('waka.classes.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <x-page-header title="Tambah Kelas Baru" description="Buat nama kelas dan tentukan tingkatannya." />
        </div>

        <x-card padding="lg">
            <form action="{{ route('waka.classes.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="name" :value="__('Nama Kelas')" />
                    <x-text-input id="name" name="name" type="text" :value="old('name')" placeholder="Contoh: XI-IPA-1" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="grade_level" :value="__('Tingkat Kelas')" />
                    <x-select id="grade_level" name="grade_level" required>
                        <option value="">-- Pilih Tingkat Kelas --</option>
                        <option value="10" {{ old('grade_level') == '10' ? 'selected' : '' }}>10 (Sepuluh)</option>
                        <option value="11" {{ old('grade_level') == '11' ? 'selected' : '' }}>11 (Sebelas)</option>
                        <option value="12" {{ old('grade_level') == '12' ? 'selected' : '' }}>12 (Duabelas)</option>
                    </x-select>
                    <x-input-error :messages="$errors->get('grade_level')" class="mt-2" />
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <x-button variant="secondary" href="{{ route('waka.classes.index') }}">Batal</x-button>
                    <x-button variant="primary" type="submit">Simpan Kelas</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
