<x-layouts.app>
    <x-slot:title>Tambah Siswa</x-slot:title>

    <div class="w-full">
        <div class="mb-6">
            <a href="{{ route('admin.students.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <x-page-header title="Tambah Siswa Baru" description="Buat akun siswa baru serta tentukan orang tua/wali siswa." />
        </div>

        <x-card padding="lg">
            <form action="{{ route('admin.students.store') }}" method="POST" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Informasi Akun -->
                    <div class="space-y-5">
                        <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Informasi Akun</h2>
                        
                        <div>
                            <x-input-label for="name" :value="__('Nama Siswa')" />
                            <x-text-input id="name" name="name" type="text" :value="old('name')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email Siswa (Akses Login)')" />
                            <x-text-input id="email" name="email" type="email" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        @php $tempPassword = \Illuminate\Support\Str::random(8); @endphp
                        <div x-data="{ pwd: '{{ old('password', $tempPassword) }}' }" class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="password" :value="__('Password Sementara')" />
                                <div class="flex items-center gap-2 mt-1">
                                    <input type="text" id="password" name="password" x-model="pwd" required class="block w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent sm:text-sm font-mono">
                                    <button type="button" @click="navigator.clipboard.writeText(pwd); alert('Password disalin!')" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                                        Salin
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                <p class="mt-1.5 text-xs text-slate-500">Dibuat otomatis. Admin dapat mengubahnya jika perlu.</p>
                            </div>
                            <input type="hidden" name="password_confirmation" :value="pwd">
                        </div>
                    </div>

                    <!-- Informasi Akademik & Biodata -->
                    <div class="space-y-5">
                        <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Profil & Akademik</h2>

                        <div>
                            <x-input-label for="nis" :value="__('NIS')" />
                            <x-text-input id="nis" name="nis" type="text" :value="old('nis')" required />
                            <x-input-error :messages="$errors->get('nis')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="gender" :value="__('Jenis Kelamin')" />
                                <x-select id="gender" name="gender" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </x-select>
                                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="date_of_birth" :value="__('Tanggal Lahir')" />
                                <x-text-input id="date_of_birth" name="date_of_birth" type="date" :value="old('date_of_birth')" required />
                                <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                            </div>
                        </div>


                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <x-input-label for="parent_id" :value="__('Orang Tua / Wali')" class="mb-0" />
                                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 bg-slate-100 px-2 py-0.5 rounded">Opsional</span>
                            </div>
                            @php
                                $parentOptions = collect([['value' => '', 'label' => '-- Kosongkan (Opsional) --']])->concat(
                                    $parents->map(function($p) {
                                        return [
                                            'value' => $p->id, 
                                            'label' => ($p->user->name ?? '-') . ($p->phone ? ' (HP: '.$p->phone.')' : '')
                                        ];
                                    })
                                )->values()->toArray();
                            @endphp
                            <x-searchable-select name="parent_id" :options="$parentOptions" :selected="old('parent_id')" />
                            <x-input-error :messages="$errors->get('parent_id')" class="mt-2" />
                            <p class="mt-1.5 text-xs text-slate-500">Anda dapat mencari berdasarkan nama atau nomor HP.</p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <x-button variant="secondary" href="{{ route('admin.students.index') }}">Batal</x-button>
                    <x-button variant="primary" type="submit">Simpan Data Siswa</x-button>
                </div>
            </form>
        </x-card>
</x-layouts.app>
