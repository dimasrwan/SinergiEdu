<x-layouts.app>
    <x-slot:title>Tambah Mata Pelajaran</x-slot:title>

    <div class="w-full">
        <div class="mb-6">
            <a href="{{ route('waka.subjects.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <x-page-header title="Tambah Mata Pelajaran Baru" description="Definisikan nama pelajaran beserta kode uniknya." />
        </div>

        <x-card padding="lg">
            <form action="{{ route('waka.subjects.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="name" :value="__('Nama Mata Pelajaran')" />
                    <x-text-input id="name" name="name" type="text" :value="old('name')" placeholder="Contoh: Matematika Wajib" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="code" :value="__('Kode Mata Pelajaran')" />
                    <x-text-input id="code" name="code" type="text" :value="old('code')" placeholder="Contoh: MTK-WJB" required />
                    <x-input-error :messages="$errors->get('code')" class="mt-2" />
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <x-button variant="secondary" href="{{ route('waka.subjects.index') }}">Batal</x-button>
                    <x-button variant="primary" type="submit">Simpan Mata Pelajaran</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
