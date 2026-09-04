<x-layouts.app>
    <x-slot:title>Profil</x-slot:title>

    <div class="max-w-3xl space-y-6 mx-auto">
        <x-page-header title="Profil Saya" description="Perbarui informasi akun dan data kepala sekolah Anda." />

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <x-card padding="lg">
            <form action="{{ route('kepala-sekolah.profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')" />
                        <x-text-input id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required class="w-full" />
                        @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email" value="{{ old('email', Auth::user()->email) }}" required class="w-full" />
                        @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="nip" :value="__('NIP')" />
                        <x-text-input id="nip" name="nip" value="{{ old('nip', $kepsek?->nip) }}" class="w-full" />
                        @error('nip') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <x-input-label for="phone" :value="__('No. Telepon')" />
                        <x-text-input id="phone" name="phone" value="{{ old('phone', $kepsek?->phone) }}" class="w-full" />
                        @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <x-input-label for="address" :value="__('Alamat')" />
                    <textarea id="address" name="address" rows="3" placeholder="Alamat lengkap..."
                        class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white border border-slate-200 rounded-lg text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">{{ old('address', $kepsek?->address) }}</textarea>
                    @error('address') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-6 border-t border-slate-200">
                    <h2 class="text-base font-bold text-slate-900 mb-4">Ganti Password</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <x-input-label for="current_password" :value="__('Password Saat Ini')" />
                            <x-text-input id="current_password" name="current_password" type="password" class="w-full" autocomplete="current-password" />
                            @error('current_password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <x-input-label for="new_password" :value="__('Password Baru')" />
                            <x-text-input id="new_password" name="new_password" type="password" class="w-full" autocomplete="new-password" />
                            @error('new_password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <x-input-label for="new_password_confirmation" :value="__('Konfirmasi Password Baru')" />
                            <x-text-input id="new_password_confirmation" name="new_password_confirmation" type="password" class="w-full" autocomplete="new-password" />
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <x-button variant="primary" type="submit">
                        Simpan Perubahan
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>