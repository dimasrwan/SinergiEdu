<x-layouts.app>
    <x-slot:title>Tambah Sekolah Baru</x-slot:title>

    <div class="w-full">
        <div class="mb-6 flex flex-col items-start gap-4">
            <a href="{{ route('super_admin.schools.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Manajemen Sekolah
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Tambah Sekolah Baru</h1>
                <p class="mt-1 text-sm text-slate-500">Daftarkan institusi baru ke dalam platform SinergiEdu.</p>
            </div>
        </div>

        <x-card padding="none" class="overflow-hidden">
            <form action="{{ route('super_admin.schools.store') }}" method="POST" enctype="multipart/form-data" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                @csrf

                <div class="p-6 md:p-8 space-y-10">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <!-- Informasi Sekolah -->
                        <div class="space-y-6">
                            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Informasi Sekolah</h2>
                            
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Sekolah <span class="text-danger">*</span></label>
                                <x-text-input id="name" name="name" type="text" :value="old('name')" placeholder="Contoh: SMA Negeri 1 Jakarta" required class="w-full" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="npsn" class="block text-sm font-semibold text-slate-700 mb-1.5">NPSN</label>
                                <x-text-input id="npsn" name="npsn" type="text" :value="old('npsn')" placeholder="Nomor Pokok Sekolah Nasional" class="w-full" />
                                <x-input-error :messages="$errors->get('npsn')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="logo" class="block text-sm font-semibold text-slate-700 mb-1.5">Logo Sekolah</label>
                                <input id="logo" name="logo" type="file" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors bg-slate-50 border border-slate-200 rounded-xl cursor-pointer" />
                                <p class="mt-1.5 text-xs text-slate-500">Maks. 2MB. Format: JPG, PNG.</p>
                                <x-input-error :messages="$errors->get('logo')" class="mt-2 text-xs" />
                            </div>
                        </div>

                        <!-- Kontak & Alamat -->
                        <div class="space-y-6">
                            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Kontak & Alamat</h2>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email Resmi</label>
                                <x-text-input id="email" name="email" type="email" :value="old('email')" placeholder="info@sekolah.sch.id" class="w-full" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Telepon</label>
                                <x-text-input id="phone" name="phone" type="text" :value="old('phone')" placeholder="Contoh: (021) 1234567" class="w-full" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Lengkap</label>
                                <x-textarea id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap sekolah">{{ old('address') }}</x-textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2 text-xs" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 sm:gap-4">
                    <x-button variant="secondary" href="{{ route('super_admin.schools.index') }}" class="w-full sm:w-auto">Batal</x-button>
                    
                    <button type="submit" :disabled="isSubmitting" 
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-primary hover:bg-primary-hover active:bg-blue-900 rounded-xl transition-all shadow-sm shadow-primary/20 disabled:opacity-70 disabled:cursor-not-allowed">
                        <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display: none;">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Sekolah'"></span>
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
