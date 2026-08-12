<x-layouts.app>
    <x-slot:title>Edit Mata Pelajaran - {{ $subject->name }}</x-slot:title>

    <div class="w-full max-w-2xl">
        <!-- Header -->
        <div class="mb-6 flex flex-col items-start gap-4">
            <a href="{{ route('admin.subjects.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Mata Pelajaran</h1>
                <p class="mt-1 text-sm text-slate-500">Perbarui informasi identitas master mata pelajaran.</p>
            </div>
        </div>

        <x-card padding="none" class="overflow-hidden">
            <form action="{{ route('admin.subjects.update', $subject) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 md:p-8 space-y-6">
                    <!-- Validation Errors Global -->
                    @if($errors->any())
                        <div class="p-4 bg-red-50 border border-red-100 rounded-xl">
                            <div class="flex items-start gap-3">
                                <svg class="h-5 w-5 text-danger mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <div>
                                    <h3 class="text-sm font-bold text-red-800">Terdapat Kesalahan</h3>
                                    <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-5">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                            <x-text-input id="name" name="name" type="text" :value="old('name', $subject->name)" required class="w-full" />
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-semibold text-slate-700 mb-1.5">Kode Mata Pelajaran <span class="text-danger">*</span></label>
                            <x-text-input id="code" name="code" type="text" :value="old('code', $subject->code)" required class="w-full" />
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 sm:gap-4">
                    <x-button variant="secondary" href="{{ route('admin.subjects.index') }}" class="w-full sm:w-auto">Batal</x-button>
                    <x-button variant="primary" type="submit" class="w-full sm:w-auto justify-center">Perbarui Mata Pelajaran</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
