<x-layouts.app>
    <x-slot:title>Tambah Pengawas</x-slot:title>

    <div class="w-full">
        <div class="mb-6 flex flex-col items-start gap-4">
            <a href="{{ route('admin.pengawas.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Tambah Pengawas Baru</h1>
                <p class="mt-1 text-sm text-slate-500">Buat akun Pengawas baru beserta data profilnya.</p>
            </div>
        </div>

        <x-card padding="none" class="overflow-hidden">
            <form action="{{ route('admin.pengawas.store') }}" method="POST">
                @csrf

                <div class="p-6 md:p-8 space-y-10">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <!-- Informasi Akun -->
                        <div class="space-y-6">
                            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Informasi Akun</h2>
                            
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Pengawas <span class="text-danger">*</span></label>
                                <x-text-input id="name" name="name" type="text" :value="old('name')" placeholder="Masukkan nama lengkap Pengawas" required class="w-full" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email Pengawas (Akses Login) <span class="text-danger">*</span></label>
                                <x-text-input id="email" name="email" type="email" :value="old('email')" placeholder="Masukkan alamat email" required class="w-full" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password <span class="text-danger">*</span></label>
                                <x-password-input id="password" name="password" required placeholder="Buat password minimal 8 karakter" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Password <span class="text-danger">*</span></label>
                                <x-password-input id="password_confirmation" name="password_confirmation" required placeholder="Masukkan kembali password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs" />
                            </div>
                        </div>

                        <!-- Profil Pengawas -->
                        <div class="space-y-6">
                            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Profil Pengawas</h2>

                            <div>
                                <label for="nip" class="block text-sm font-semibold text-slate-700 mb-1.5">NIP (Nomor Induk Pegawai)</label>
                                <x-text-input id="nip" name="nip" type="text" :value="old('nip')" placeholder="Kosongkan jika tidak ada" class="w-full" />
                                <x-input-error :messages="$errors->get('nip')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor HP</label>
                                <x-text-input id="phone" name="phone" type="text" :value="old('phone')" placeholder="Kosongkan jika tidak ada" class="w-full" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat</label>
                                <x-textarea id="address" name="address" rows="3" placeholder="Kosongkan jika tidak ada">{{ old('address') }}</x-textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2 text-xs" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer / Actions -->
                <div class="bg-slate-50 px-6 py-4 md:px-8 flex items-center justify-end gap-3 border-t border-slate-100">
                    <x-button variant="secondary" href="{{ route('admin.pengawas.index') }}">Batal</x-button>
                    <x-button variant="primary" type="submit">Simpan Pengawas</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
