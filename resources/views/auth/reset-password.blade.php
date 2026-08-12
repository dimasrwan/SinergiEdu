<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Atur Ulang Sandi</h2>
        <p class="text-slate-500 mt-2 text-sm">Silakan buat kata sandi baru untuk akun Anda.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" class="mb-1.5" />
            <x-text-input id="email" class="w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Kata Sandi Baru" class="mb-1.5" />
            <x-text-input id="password" class="w-full" type="password" name="password" required autocomplete="new-password" placeholder="Masukkan kata sandi baru" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" class="mb-1.5" />
            <x-text-input id="password_confirmation" class="w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi baru" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div>
            <x-button type="submit" variant="primary" class="w-full text-base py-3">
                Simpan Kata Sandi Baru
            </x-button>
        </div>
    </form>
</x-guest-layout>
