<x-layouts.app>
    <x-slot:title>Edit Admin Sekolah</x-slot:title>

    <div class="w-full">
        <div class="mb-6 flex flex-col items-start gap-4">
            <a href="{{ route('super_admin.schools.show', $school) }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Detail Sekolah
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Admin Sekolah</h1>
                <p class="mt-1 text-sm text-slate-500">Perbarui profil Admin untuk <span class="font-semibold text-slate-700">{{ $school->name }}</span>.</p>
            </div>
        </div>

        <x-card padding="none" class="overflow-hidden">
            <form action="{{ route('super_admin.schools.admins.update', [$school, $admin]) }}" method="POST" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                @csrf
                @method('PUT')

                <div class="p-6 md:p-8 space-y-10">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <!-- Informasi Akun -->
                        <div class="space-y-6">
                            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Informasi Akun Admin</h2>
                            
                            <!-- Error Conflicts -->
                            @if($errors->has('email'))
                                <div class="p-4 bg-red-50 border border-red-100 rounded-xl flex items-start gap-3 shadow-sm mb-4">
                                    <svg class="h-5 w-5 text-red-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    <div>
                                        <h3 class="text-sm font-bold text-red-800">Pembaruan Admin Gagal</h3>
                                        <p class="text-sm text-red-700 mt-0.5">{{ $errors->first('email') }}</p>
                                    </div>
                                </div>
                            @endif

                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-danger">*</span></label>
                                <x-text-input id="name" name="name" type="text" :value="old('name', $admin->name)" placeholder="Masukkan nama lengkap admin" required class="w-full" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email Akses <span class="text-danger">*</span></label>
                                <x-text-input id="email" name="email" type="email" :value="old('email', $admin->email)" placeholder="admin@sekolah.sch.id" required class="w-full" />
                                <p class="mt-1.5 text-xs text-slate-500">Email digunakan untuk login Admin Sekolah.</p>
                                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                            </div>

                            <div class="pt-4 border-t border-slate-100">
                                <h3 class="text-sm font-bold text-slate-800 mb-4">Ubah Password <span class="text-xs font-normal text-slate-500">(Opsional)</span></h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password Baru</label>
                                        <x-password-input id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah" />
                                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Password</label>
                                        <x-password-input id="password_confirmation" name="password_confirmation" placeholder="Masukkan kembali password baru" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info Tambahan -->
                        <div class="space-y-6">
                            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Informasi Target Tenant</h2>
                            
                            <div class="bg-slate-50 border border-slate-100 rounded-xl p-5 space-y-4">
                                <div>
                                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Sekolah</span>
                                    <span class="block font-semibold text-slate-900">{{ $school->name }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Status Admin</span>
                                    @if($admin->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-green-50 text-green-700 text-xs font-bold uppercase tracking-wider border border-green-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 text-slate-500 text-xs font-bold uppercase tracking-wider border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Hak Akses</span>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-wider border border-blue-100">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" /></svg>
                                        Admin Sekolah
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 sm:gap-4">
                    <x-button variant="secondary" href="{{ route('super_admin.schools.show', $school) }}" class="w-full sm:w-auto">Batal</x-button>
                    
                    <button type="submit" :disabled="isSubmitting" 
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-primary hover:bg-primary-hover active:bg-blue-900 rounded-xl transition-all shadow-sm shadow-primary/20 disabled:opacity-70 disabled:cursor-not-allowed">
                        <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display: none;">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
