<x-layouts.app>
    <x-slot:title>Edit Siswa</x-slot:title>

    <div class="w-full">
        <div class="mb-6">
            <a href="{{ route('admin.students.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <x-page-header title="Edit Data Siswa" description="Perbarui profil siswa, biodata dasar, serta orang tua/wali siswa." />
        </div>

        <x-card padding="lg">
            <form action="{{ route('admin.students.update', $student) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Informasi Akun -->
                    <div class="space-y-5">
                        <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Informasi Akun</h2>
                        
                        <div>
                            <x-input-label for="name" :value="__('Nama Siswa')" />
                            <x-text-input id="name" name="name" type="text" :value="old('name', $student->user->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email Siswa (Akses Login)')" />
                            <x-text-input id="email" name="email" type="email" :value="old('email', $student->user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Password Baru (Opsional)')" />
                            <x-text-input id="password" name="password" type="password" placeholder="Kosongkan jika tidak ingin diubah" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Informasi Akademik & Biodata -->
                    <div class="space-y-5">
                        <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Profil & Akademik</h2>

                        <div>
                            <x-input-label for="nis" :value="__('NIS')" />
                            <x-text-input id="nis" name="nis" type="text" :value="old('nis', $student->nis)" required />
                            <x-input-error :messages="$errors->get('nis')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="gender" :value="__('Jenis Kelamin')" />
                                <x-select id="gender" name="gender" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </x-select>
                                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="date_of_birth" :value="__('Tanggal Lahir')" />
                                <x-text-input id="date_of_birth" name="date_of_birth" type="date" :value="old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '')" required />
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
                            <x-searchable-select name="parent_id" :options="$parentOptions" :selected="old('parent_id', $student->parent_id)" />
                            <x-input-error :messages="$errors->get('parent_id')" class="mt-2" />
                            <p class="mt-1.5 text-xs text-slate-500">Pilih kosongkan untuk menghapus kaitan dengan wali saat ini.</p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <x-button variant="secondary" href="{{ route('admin.students.index') }}">Batal</x-button>
                    <x-button variant="primary" type="submit">Perbarui Data Siswa</x-button>
                </div>
            </form>
        </x-card>
</x-layouts.app>
