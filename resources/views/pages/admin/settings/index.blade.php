<x-layouts.app>
    <x-slot:title>Pengaturan Sistem</x-slot:title>

    <div class="w-full">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Pengaturan Sistem</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola identitas dasar sekolah/madrasah dan konfigurasi sistem SinergiEdu.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm text-emerald-800 flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <x-card padding="none" class="overflow-hidden">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                @csrf
                @method('PUT')

                <div class="p-6 md:p-8 space-y-10">
                    
                    <!-- Profil Sekolah -->
                    <div class="space-y-6">
                        <div class="border-b border-slate-100 pb-3">
                            <h2 class="text-lg font-bold text-slate-900">Profil Sekolah / Madrasah</h2>
                            <p class="text-sm text-slate-500 mt-0.5">Informasi ini digunakan sebagai identitas sekolah/madrasah pada laporan dan antarmuka sistem.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Logo Upload Section -->
                            <div class="md:col-span-2 flex flex-col sm:flex-row items-start gap-6 bg-slate-50 p-6 rounded-2xl border border-slate-100" x-data="logoPreview()">
                                <div class="shrink-0 relative group">
                                    <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-xl bg-white border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden shadow-sm relative">
                                        <!-- Current/Preview Logo -->
                                        <template x-if="imageUrl">
                                            <img :src="imageUrl" alt="Logo Preview" class="w-full h-full object-contain p-2" />
                                        </template>
                                        
                                        <!-- No Logo State -->
                                        <template x-if="!imageUrl">
                                            <div class="text-slate-400 flex flex-col items-center">
                                                <svg class="h-8 w-8 mb-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                </svg>
                                                <span class="text-[10px] font-semibold uppercase tracking-wider">Logo</span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <div class="flex-1 space-y-3 w-full">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-900">Logo Sekolah/Madrasah</label>
                                        <p class="text-xs text-slate-500 mt-1">JPG, PNG, atau SVG • Maks. 2 MB</p>
                                    </div>
                                    <div class="relative">
                                        <input type="file" id="school_logo" name="school_logo" accept="image/jpeg,image/png,image/jpg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="fileChosen" />
                                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 focus-within:ring-2 focus-within:ring-accent focus-within:border-accent transition cursor-pointer shadow-sm">
                                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                                            <span x-text="fileName ? fileName : 'Pilih File Logo'">Pilih File Logo</span>
                                        </div>
                                    </div>
                                    <x-input-error :messages="$errors->get('school_logo')" class="mt-1 text-xs" />
                                </div>
                            </div>

                            <script>
                                function logoPreview() {
                                    return {
                                        imageUrl: '{{ $setting->school_logo ? Storage::url($setting->school_logo) : '' }}',
                                        fileName: '',
                                        fileChosen(event) {
                                            const file = event.target.files[0];
                                            if (!file) return;
                                            this.fileName = file.name;
                                            const reader = new FileReader();
                                            reader.onload = (e) => this.imageUrl = e.target.result;
                                            reader.readAsDataURL(file);
                                        }
                                    }
                                }
                            </script>

                            <!-- Fields -->
                            <div class="md:col-span-2">
                                <label for="school_name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Sekolah/Madrasah <span class="text-danger">*</span></label>
                                <x-text-input id="school_name" name="school_name" type="text" :value="old('school_name', $setting->school_name)" placeholder="Misal: SMA Negeri 1 Sinergi" required class="w-full" />
                                <x-input-error :messages="$errors->get('school_name')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="school_npsn" class="block text-sm font-semibold text-slate-700 mb-1.5">NPSN</label>
                                <x-text-input id="school_npsn" name="school_npsn" type="text" :value="old('school_npsn', $setting->school_npsn)" placeholder="Nomor Pokok Sekolah Nasional" class="w-full" />
                                <x-input-error :messages="$errors->get('school_npsn')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="school_phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Telepon</label>
                                <x-text-input id="school_phone" name="school_phone" type="text" :value="old('school_phone', $setting->school_phone)" placeholder="Misal: (021) 1234567" class="w-full" />
                                <x-input-error :messages="$errors->get('school_phone')" class="mt-2 text-xs" />
                            </div>

                            <div class="md:col-span-2">
                                <label for="school_email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email Sekolah</label>
                                <x-text-input id="school_email" name="school_email" type="email" :value="old('school_email', $setting->school_email)" placeholder="Misal: info@sekolah.sch.id" class="w-full" />
                                <x-input-error :messages="$errors->get('school_email')" class="mt-2 text-xs" />
                            </div>

                            <div class="md:col-span-2">
                                <label for="school_address" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Lengkap</label>
                                <x-textarea id="school_address" name="school_address" rows="3" placeholder="Masukkan alamat lengkap sekolah/madrasah">{{ old('school_address', $setting->school_address) }}</x-textarea>
                                <x-input-error :messages="$errors->get('school_address')" class="mt-2 text-xs" />
                            </div>

                        </div>
                    </div>

                    <!-- Informasi Sistem -->
                    <div class="space-y-6">
                        <div class="border-b border-slate-100 pb-3">
                            <h2 class="text-lg font-bold text-slate-900">Informasi Sistem</h2>
                            <p class="text-sm text-slate-500 mt-0.5">Pengaturan teknis platform yang hanya bersifat informasi (*Read Only*).</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Aplikasi</span>
                                    <span class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                                        <svg class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" /></svg>
                                        SinergiEdu
                                    </span>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Versi</span>
                                    <span class="text-sm font-semibold text-slate-900">v1.0.0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer / Actions -->
                <div class="bg-slate-50 px-6 py-5 md:px-8 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 sm:gap-4 border-t border-slate-100">
                    <x-button variant="secondary" href="{{ route('admin.dashboard') }}" class="w-full sm:w-auto">Batal</x-button>
                    <x-button variant="primary" type="submit" class="w-full sm:w-auto justify-center" x-bind:disabled="isSubmitting" x-bind:class="{ 'opacity-70 cursor-not-allowed': isSubmitting }">
                        <span x-show="!isSubmitting">Simpan Perubahan</span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Menyimpan...
                        </span>
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
