<section>
    <header class="mb-6">
        <h2 class="text-xl font-bold text-slate-900">
            Perbarui Kata Sandi
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Pastikan akun Anda menggunakan kata sandi panjang dan acak agar tetap aman. Gunakan minimal 8 karakter dan kombinasi yang aman.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Current Password -->
            <div x-data="{ show: false }">
                <x-input-label for="update_password_current_password" value="Kata Sandi Saat Ini" class="mb-1.5" />
                <div class="relative">
                    <x-text-input id="update_password_current_password" name="current_password" ::type="show ? 'text' : 'password'" class="w-full pr-10" autocomplete="current-password" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" aria-label="Toggle password visibility">
                        <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.535-3.029m5.858-2.43A9.954 9.954 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.02 10.02 0 01-4.132 5.411m0 0L21 21m-2.121-2.121L3 3" />
                        </svg>
                        <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <!-- New Password -->
            <div x-data="{ show: false }">
                <x-input-label for="update_password_password" value="Kata Sandi Baru" class="mb-1.5" />
                <div class="relative">
                    <x-text-input id="update_password_password" name="password" ::type="show ? 'text' : 'password'" class="w-full pr-10" autocomplete="new-password" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" aria-label="Toggle password visibility">
                        <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.535-3.029m5.858-2.43A9.954 9.954 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.02 10.02 0 01-4.132 5.411m0 0L21 21m-2.121-2.121L3 3" />
                        </svg>
                        <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div x-data="{ show: false }">
                <x-input-label for="update_password_password_confirmation" value="Konfirmasi Kata Sandi" class="mb-1.5" />
                <div class="relative">
                    <x-text-input id="update_password_password_confirmation" name="password_confirmation" ::type="show ? 'text' : 'password'" class="w-full pr-10" autocomplete="new-password" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" aria-label="Toggle password visibility">
                        <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.535-3.029m5.858-2.43A9.954 9.954 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.02 10.02 0 01-4.132 5.411m0 0L21 21m-2.121-2.121L3 3" />
                        </svg>
                        <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-100">
            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-medium text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100"
                >Sandi berhasil diperbarui.</p>
            @endif

            <x-button type="submit" variant="primary">Ubah Sandi</x-button>
        </div>
    </form>
</section>
