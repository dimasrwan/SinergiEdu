<x-layouts.app>
    <x-slot:title>Edit Pengawas</x-slot:title>

    <div class="w-full">
        <div class="mb-6 flex flex-col items-start gap-4">
            <a href="{{ route('admin.pengawas.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Data Pengawas</h1>
                <p class="mt-1 text-sm text-slate-500">Ubah detail profil dan kredensial akun Pengawas.</p>
            </div>
        </div>

        <x-card padding="none" class="overflow-hidden" x-data="{
            initialSchoolIds: {{ json_encode($assignedSchoolIds) }},
            selectedSchools: {{ json_encode(old('schools', $assignedSchoolIds)) }},
            showConfirmModal: false,
            schoolNamesMap: {{ json_encode($schools->pluck('name', 'id')) }},
            removedSchoolNames: [],
            checkAndSubmit() {
                this.removedSchoolNames = [];
                for (let id of this.initialSchoolIds) {
                    if (!this.selectedSchools.includes(id) && !this.selectedSchools.includes(String(id))) {
                        if (this.schoolNamesMap[id]) {
                            this.removedSchoolNames.push(this.schoolNamesMap[id]);
                        }
                    }
                }
                if (this.removedSchoolNames.length > 0) {
                    this.showConfirmModal = true;
                } else {
                    $refs.editForm.submit();
                }
            }
        }">
            <form x-ref="editForm" action="{{ route('admin.pengawas.update', $pengawas) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 md:p-8 space-y-10">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <!-- Informasi Akun -->
                        <div class="space-y-6">
                            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Informasi Akun</h2>
                            
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Pengawas <span class="text-danger">*</span></label>
                                <x-text-input id="name" name="name" type="text" :value="old('name', $pengawas->user->name)" placeholder="Masukkan nama lengkap Pengawas" required class="w-full" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email Akses <span class="text-danger">*</span></label>
                                <x-text-input id="email" name="email" type="email" :value="old('email', $pengawas->user->email)" placeholder="Masukkan alamat email" required class="w-full" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password Baru (Opsional)</label>
                                <x-password-input id="password" name="password" placeholder="Kosongkan jika tidak ingin diubah" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                                <x-password-input id="password_confirmation" name="password_confirmation" placeholder="Masukkan kembali password baru jika diisi" />
                            </div>
                        </div>

                        <!-- Profil Pengawas -->
                        <div class="space-y-6">
                            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Profil Pengawas</h2>

                            <div>
                                <label for="nip" class="block text-sm font-semibold text-slate-700 mb-1.5">NIP (Nomor Induk Pegawai)</label>
                                <x-text-input id="nip" name="nip" type="text" :value="old('nip', $pengawas->nip)" placeholder="Kosongkan jika tidak ada" class="w-full" />
                                <x-input-error :messages="$errors->get('nip')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor HP</label>
                                <x-text-input id="phone" name="phone" type="text" :value="old('phone', $pengawas->phone)" placeholder="Kosongkan jika tidak ada" class="w-full" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat</label>
                                <x-textarea id="address" name="address" rows="3" placeholder="Kosongkan jika tidak ada">{{ old('address', $pengawas->address) }}</x-textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2 text-xs" />
                            </div>
                        </div>
                    </div>

                    <!-- Sekolah yang Diawasi -->
                    <div class="space-y-4">
                        <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Sekolah yang Diawasi <span class="text-danger">*</span></h2>
                        <p class="text-sm text-slate-500">Pilih minimal satu sekolah yang akan diawasi oleh pengawas ini. Pengawas hanya dapat mengakses data sekolah yang dipilih.</p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($schools as $school)
                                <label class="flex items-start gap-3 p-3 border rounded-lg cursor-pointer transition hover:bg-slate-50 min-h-[44px]" :class="selectedSchools.includes('{{ $school->id }}') || selectedSchools.includes({{ $school->id }}) ? 'border-accent bg-accent/5' : 'border-slate-200'">
                                    <input type="checkbox" name="schools[]" value="{{ $school->id }}" x-model="selectedSchools" class="mt-0.5 rounded border-slate-300 text-accent focus:ring-accent w-5 h-5">
                                    <div>
                                        <div class="text-sm font-semibold text-slate-900">{{ $school->name }}</div>
                                        @if($school->npsn)
                                            <div class="text-xs text-slate-500">NPSN: {{ $school->npsn }}</div>
                                        @endif
                                        <template x-if="initialSchoolIds.includes({{ $school->id }}) && (!selectedSchools.includes('{{ $school->id }}') && !selectedSchools.includes({{ $school->id }}))">
                                            <span class="inline-flex items-center mt-1 px-2 py-0.5 text-[10px] font-bold bg-amber-50 text-amber-700 rounded border border-amber-200">
                                                Penugasan akan dilepas
                                            </span>
                                        </template>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('schools')" class="mt-2 text-xs" />
                        <x-input-error :messages="$errors->get('schools.*')" class="mt-1 text-xs" />
                    </div>
                </div>

                <!-- Footer / Actions -->
                <div class="bg-slate-50 px-6 py-4 md:px-8 flex items-center justify-end gap-3 border-t border-slate-100">
                    <x-button variant="secondary" href="{{ route('admin.pengawas.index') }}">Batal</x-button>
                    <x-button variant="primary" type="button" @click="checkAndSubmit()">Simpan Perubahan</x-button>
                </div>

                <!-- Modal Konfirmasi Perubahan Penugasan -->
                <div x-show="showConfirmModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                    <div class="flex min-h-full items-center justify-center p-4 text-center">
                        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" @click="showConfirmModal = false"></div>

                        <div class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all border border-slate-200">
                            <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mb-4">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900">Perubahan Penugasan</h3>
                            <p class="mt-2 text-sm text-slate-600">Pengawas akan dilepas dari sekolah berikut:</p>
                            
                            <ul class="mt-3 space-y-1.5 bg-amber-50/70 border border-amber-200/80 rounded-xl p-3 text-xs font-semibold text-amber-800">
                                <template x-for="name in removedSchoolNames" :key="name">
                                    <li class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span x-text="name"></span>
                                    </li>
                                </template>
                            </ul>

                            <p class="mt-3 text-xs text-slate-500">Penugasan ke sekolah lain, profil, serta akun Pengawas tidak akan terhapus.</p>

                            <div class="mt-6 flex justify-end gap-3">
                                <x-button variant="secondary" type="button" @click="showConfirmModal = false">Batal</x-button>
                                <x-button variant="primary" type="button" @click="$refs.editForm.submit()">Konfirmasi Perubahan</x-button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
