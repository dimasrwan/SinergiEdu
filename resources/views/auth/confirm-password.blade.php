<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-slate-900 tracking-tight mb-2">Konfirmasi Sandi</h2>
        <p class="text-sm text-slate-500 leading-relaxed">
            Ini adalah area aman dalam aplikasi. Harap konfirmasi kata sandi Anda sebelum melanjutkan.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Kata Sandi" class="mb-1.5" />
            <x-text-input id="password" class="w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="Masukkan kata sandi Anda" autofocus />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-button type="submit" variant="primary" class="w-full text-base py-3">
                Konfirmasi
            </x-button>
        </div>
    </form>
</x-guest-layout>
