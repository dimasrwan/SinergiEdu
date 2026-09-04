<x-layouts.app>
    <x-slot:title>Edit Tahun Ajaran - {{ $academicYear->year }}</x-slot:title>

    <div class="w-full">
        <!-- Header -->
        <div class="mb-6 flex flex-col items-start gap-4">
            <a href="{{ route('admin.academic-years.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Tahun Ajaran</h1>
                <p class="mt-1 text-sm text-slate-500">Perbarui periode atau ubah status keaktifan tahun ajaran.</p>
            </div>
        </div>

        <x-card padding="none" class="overflow-hidden">
            <form action="{{ route('admin.academic-years.update', $academicYear) }}" method="POST">
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

                    <div class="space-y-6">
                        <div>
                            <label for="year" class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun Ajaran <span class="text-danger">*</span></label>
                            <x-text-input id="year" name="year" type="text" :value="old('year', $academicYear->year)" required class="w-full" />
                        </div>

                        <div class="p-4 {{ $academicYear->is_active ? 'bg-green-50/50 border border-green-100' : 'bg-blue-50/50 border border-blue-100' }} rounded-xl">
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <div class="flex items-center h-5 mt-0.5">
                                    <input id="is_active" name="is_active" type="checkbox" value="1" class="w-4 h-4 text-primary bg-white border-slate-300 rounded focus:ring-accent focus:ring-2" {{ old('is_active', $academicYear->is_active) ? 'checked' : '' }}>
                                </div>
                                <div class="flex-1">
                                    <span class="block text-sm font-bold text-slate-900 group-hover:text-primary transition-colors">
                                        {{ $academicYear->is_active ? 'Tahun Ajaran Aktif' : 'Jadikan Tahun Ajaran Aktif' }}
                                    </span>
                                    <span class="block text-xs text-slate-500 mt-0.5">Periode ini akan menjadi konteks akademik utama sistem.</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 sm:gap-4">
                    <x-button variant="secondary" href="{{ route('admin.academic-years.index') }}" class="w-full sm:w-auto">Batal</x-button>
                    <x-button variant="primary" type="submit" class="w-full sm:w-auto justify-center">Perbarui Tahun Ajaran</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
