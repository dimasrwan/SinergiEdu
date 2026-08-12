<section>
    <header class="mb-6">
        <h2 class="text-xl font-bold text-slate-900">
            Perbarui Kata Sandi
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Pastikan akun Anda menggunakan kata sandi panjang dan acak agar tetap aman.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="max-w-xl space-y-5">
            <div>
                <x-input-label for="update_password_current_password" value="Kata Sandi Saat Ini" class="mb-1.5" />
                <x-text-input id="update_password_current_password" name="current_password" type="password" class="w-full" autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password" value="Kata Sandi Baru" class="mb-1.5" />
                <x-text-input id="update_password_password" name="password" type="password" class="w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password_confirmation" value="Konfirmasi Kata Sandi" class="mb-1.5" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
            <x-button type="submit" variant="primary">Ubah Sandi</x-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-medium text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100"
                >Sandi berhasil diperbarui.</p>
            @endif
        </div>
    </form>
</section>
