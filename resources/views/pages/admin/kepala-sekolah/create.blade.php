<x-layouts.app>
    <x-slot:title>Tambah Kepala Sekolah/Madrasah</x-slot:title>

    <div class="w-full">
        <div class="mb-6 flex flex-col items-start gap-4">
            <a href="{{ route('admin.kepala-sekolah.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Tambah Kepala Sekolah/Madrasah</h1>
                <p class="mt-1 text-sm text-slate-500">Buat akun Kepala Sekolah/Madrasah baru beserta data profilnya.</p>
            </div>
        </div>

        <x-card padding="none" class="overflow-hidden">
            <form action="{{ route('admin.kepala-sekolah.store') }}" method="POST">
                @csrf

                <div class="p-6 md:p-8 space-y-10">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <!-- Informasi Akun -->
                        <div class="space-y-6">
                            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Informasi Akun</h2>
                            
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Kepala Sekolah/Madrasah <span class="text-danger">*</span></label>
                                <x-text-input id="name" name="name" type="text" :value="old('name')" placeholder="Masukkan nama lengkap" required class="w-full" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email (Akses Login) <span class="text-danger">*</span></label>
                                <x-text-input id="email" name="email" type="email" :value="old('email')" placeholder="Masukkan alamat email" required class="w-full" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                            </div>

                            @php $tempPassword = \Illuminate\Support\Str::random(8); @endphp
                            <div x-data="{ pwd: '{{ old('password', $tempPassword) }}' }" class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password Sementara <span class="text-danger">*</span></label>
                                    <div class="flex items-center gap-2">
                                        <input type="text" id="password" name="password" x-model="pwd" required class="block w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent sm:text-sm font-mono">
                                        <button type="button" @click="navigator.clipboard.writeText(pwd); alert('Password disalin!')" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                                            Salin
                                        </button>
                                    </div>
                                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
                                    <p class="mt-1.5 text-xs text-slate-500">Dibuat otomatis. Admin dapat mengubahnya jika perlu.</p>
                                </div>
                                <input type="hidden" name="password_confirmation" :value="pwd">
                            </div>
                        </div>

                        <!-- Profil -->
                        <div class="space-y-6">
                            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Profil</h2>

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
                    <x-button variant="secondary" href="{{ route('admin.kepala-sekolah.index') }}">Batal</x-button>
                    <x-button variant="primary" type="submit">Simpan Data</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
