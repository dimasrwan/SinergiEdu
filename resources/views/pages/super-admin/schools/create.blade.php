<x-layouts.app>
    <x-slot:title>Tambah Sekolah Baru</x-slot:title>

    <div class="w-full max-w-5xl mx-auto">
        <div class="mb-8 flex flex-col items-start gap-4">
            <a href="{{ route('super_admin.schools.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Manajemen Sekolah
            </a>
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Tambah Sekolah Baru</h1>
                <p class="mt-2 text-sm text-slate-500">Daftarkan institusi baru ke dalam platform SinergiEdu.</p>
            </div>
        </div>

        <x-card padding="none" class="overflow-hidden border border-slate-200/60 shadow-sm rounded-2xl bg-white">
            <form action="{{ route('super_admin.schools.store') }}" method="POST" enctype="multipart/form-data" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                @csrf
                <input type="hidden" name="is_active" value="1">

                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <!-- Informasi Sekolah -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 border-b border-slate-100 pb-4 mb-6">
                                <div class="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center text-primary border border-blue-100">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-slate-900">Informasi Sekolah</h2>
                                    <p class="text-[11px] font-medium text-slate-500 mt-0.5">Data identitas utama institusi.</p>
                                </div>
                            </div>
                            
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Sekolah <span class="text-red-500 font-bold ml-0.5">*</span></label>
                                <x-text-input id="name" name="name" type="text" :value="old('name')" placeholder="Contoh: SMP Negeri 2 Banda Aceh" required class="w-full" />
                                <p class="mt-1.5 text-[11px] text-slate-500 font-medium">Nama resmi institusi yang akan ditampilkan di platform.</p>
                                <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="npsn" class="block text-sm font-semibold text-slate-700 mb-1.5">NPSN</label>
                                <x-text-input id="npsn" name="npsn" type="text" :value="old('npsn')" placeholder="Contoh: 10101010" class="w-full" />
                                <p class="mt-1.5 text-[11px] text-slate-500 font-medium">Nomor Pokok Sekolah Nasional.</p>
                                <x-input-error :messages="$errors->get('npsn')" class="mt-2 text-xs" />
                            </div>

                            <div x-data="{ fileName: '', filePreview: '' }">
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Logo Sekolah</label>
                                <div class="relative group cursor-pointer" @click="$refs.fileInput.click()">
                                    <input x-ref="fileInput" id="logo" name="logo" type="file" accept="image/*" class="hidden" 
                                           @change="if($event.target.files.length) { fileName = $event.target.files[0].name; const reader = new FileReader(); reader.onload = (e) => { filePreview = e.target.result }; reader.readAsDataURL($event.target.files[0]); }" />
                                    
                                    <div class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50 group-hover:bg-blue-50/50 group-hover:border-primary/50 transition-colors">
                                        <template x-if="!filePreview">
                                            <div class="text-center">
                                                <svg class="mx-auto h-8 w-8 text-slate-400 group-hover:text-primary transition-colors mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                                </svg>
                                                <p class="text-sm font-semibold text-slate-700 mb-1">Unggah Logo Sekolah</p>
                                                <p class="text-[11px] text-slate-500 font-medium">JPG atau PNG • Maks. 2 MB</p>
                                                <span class="mt-4 inline-block px-4 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 shadow-sm group-hover:border-primary/30 group-hover:text-primary transition-colors">Pilih File</span>
                                            </div>
                                        </template>
                                        <template x-if="filePreview">
                                            <div class="flex items-center gap-4 w-full">
                                                <div class="h-16 w-16 shrink-0 rounded-lg overflow-hidden border border-slate-200 bg-white p-1 shadow-sm">
                                                    <img :src="filePreview" class="h-full w-full object-contain rounded-md" />
                                                </div>
                                                <div class="flex-1 text-left min-w-0">
                                                    <p class="text-sm font-semibold text-slate-700 truncate" x-text="fileName"></p>
                                                    <p class="text-[11px] text-primary font-bold mt-1 hover:underline cursor-pointer uppercase tracking-wider">Ganti Logo</p>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('logo')" class="mt-2 text-xs" />
                            </div>
                        </div>

                        <!-- Kontak & Alamat -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 border-b border-slate-100 pb-4 mb-6">
                                <div class="h-10 w-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-slate-900">Kontak & Alamat</h2>
                                    <p class="text-[11px] font-medium text-slate-500 mt-0.5">Informasi komunikasi dan lokasi sekolah.</p>
                                </div>
                            </div>

                            <div>
                                <label for="email" class="flex items-center text-sm font-semibold text-slate-700 mb-1.5">
                                    <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                    </svg>
                                    Email Resmi
                                </label>
                                <x-text-input id="email" name="email" type="email" :value="old('email')" placeholder="info@sekolah.sch.id" class="w-full" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="phone" class="flex items-center text-sm font-semibold text-slate-700 mb-1.5">
                                    <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.896-1.596-5.213-3.913-6.809-6.81l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                    </svg>
                                    Nomor Telepon
                                </label>
                                <x-text-input id="phone" name="phone" type="text" :value="old('phone')" placeholder="Contoh: 0651100200" class="w-full" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Lengkap</label>
                                <x-textarea id="address" name="address" rows="5" placeholder="Jl. Pendidikan No.2, Banda Aceh" class="w-full resize-y leading-relaxed">{{ old('address') }}</x-textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2 text-xs" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/30 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 sm:gap-4 rounded-b-2xl">
                    <x-button variant="secondary" href="{{ route('super_admin.schools.index') }}" class="w-full sm:w-auto min-w-[120px] justify-center">Batal</x-button>
                    
                    <button type="submit" :disabled="isSubmitting" 
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-primary hover:bg-primary-hover active:bg-blue-900 rounded-xl transition-all shadow-sm shadow-primary/20 disabled:opacity-70 disabled:cursor-not-allowed">
                        <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display: none;">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg x-show="!isSubmitting" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Sekolah'"></span>
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
