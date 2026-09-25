<x-layouts.app>
    <x-slot:title>Edit Guru</x-slot:title>

    <div class="w-full">
        <div class="mb-6 flex flex-col items-start gap-4">
            <a href="{{ route('admin.teachers.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Data Guru</h1>
                <p class="mt-1 text-sm text-slate-500">Ubah detail profil, kredensial akun, serta penugasan kelas/mapel Guru.</p>
            </div>
        </div>

        <x-card padding="none" class="overflow-hidden">
            <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST" x-data="teacherForm()" @submit="submitForm($event)">
                @csrf
                @method('PUT')

                <div class="p-6 md:p-8 space-y-10">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <!-- Informasi Akun -->
                        <div class="space-y-6">
                            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Informasi Akun</h2>
                            
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-danger">*</span></label>
                                <x-text-input id="name" name="name" type="text" :value="old('name', $teacher->user->name)" placeholder="Masukkan nama lengkap guru" required class="w-full" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email Akses <span class="text-danger">*</span></label>
                                <x-text-input id="email" name="email" type="email" :value="old('email', $teacher->user->email)" placeholder="Masukkan alamat email guru" required class="w-full" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password Baru (Opsional)</label>
                                <x-password-input id="password" name="password" placeholder="Kosongkan jika tidak ingin diubah" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
                            </div>
                        </div>

                        <!-- Profil Guru -->
                        <div class="space-y-6">
                            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Profil Kepegawaian</h2>

                            <div>
                                <label for="nip" class="block text-sm font-semibold text-slate-700 mb-1.5">NIP (Nomor Induk Pegawai) <span class="text-danger">*</span></label>
                                <x-text-input id="nip" name="nip" type="text" :value="old('nip', $teacher->nip)" placeholder="Masukkan NIP atau nomor identitas unik" required class="w-full" />
                                <x-input-error :messages="$errors->get('nip')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor HP / WhatsApp</label>
                                <x-text-input id="phone" name="phone" type="text" :value="old('phone', $teacher->phone)" placeholder="Contoh: 081234567890" class="w-full" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Tempat Tinggal</label>
                                <x-textarea id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap tempat tinggal guru">{{ old('address', $teacher->address) }}</x-textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2 text-xs" />
                            </div>
                        </div>
                    </div>

                <!-- Footer Actions -->
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 sm:gap-4">
                    <x-button variant="secondary" href="{{ route('admin.teachers.index') }}" class="w-full sm:w-auto">Batal</x-button>
                    
                    <button type="submit" 
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-primary hover:bg-primary-hover active:bg-blue-900 rounded-lg transition-all shadow-sm shadow-primary/20">
                        Perbarui Data Guru
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
