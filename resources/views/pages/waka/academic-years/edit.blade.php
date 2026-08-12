<x-layouts.app>
    <x-slot:title>Edit Tahun Ajaran</x-slot:title>

    <div class="w-full">
        <div class="mb-6">
            <a href="{{ route('waka.academic-years.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <x-page-header title="Edit Tahun Ajaran" description="Ubah nama atau status keaktifan tahun ajaran." />
        </div>

        <x-card padding="lg">
            <form action="{{ route('waka.academic-years.update', $academicYear) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="year" :value="__('Tahun Ajaran')" />
                    <x-text-input id="year" name="year" type="text" :value="old('year', $academicYear->year)" placeholder="Contoh: 2026/2027" required />
                    <x-input-error :messages="$errors->get('year')" class="mt-2" />
                </div>

                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <x-checkbox id="is_active" name="is_active" value="1" :checked="$academicYear->is_active" />
                        <span class="text-sm text-slate-700">Set sebagai tahun ajaran aktif</span>
                    </label>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <x-button variant="secondary" href="{{ route('waka.academic-years.index') }}">Batal</x-button>
                    <x-button variant="primary" type="submit">Perbarui Tahun Ajaran</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
