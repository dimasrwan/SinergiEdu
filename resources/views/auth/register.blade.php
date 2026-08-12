<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Daftar Akun Baru</h2>
        <p class="text-slate-500 mt-2 text-sm">Bergabunglah dengan ekosistem pendidikan SinergiEdu.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nama Lengkap" class="mb-1.5" />
            <x-text-input id="name" class="w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap Anda" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" class="mb-1.5" />
            <x-text-input id="email" class="w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Masukkan email aktif Anda" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Kata Sandi" class="mb-1.5" />
            <x-text-input id="password" class="w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="Buat kata sandi baru" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" class="mb-1.5" />
            <x-text-input id="password_confirmation" class="w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div>
            <x-button type="submit" variant="primary" class="w-full text-base py-3">
                Buat Akun
            </x-button>
        </div>

        <div class="mt-6 text-center">
            <p class="text-sm text-slate-500">
                Sudah memiliki akun? 
                <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-800 transition">Masuk di sini</a>
            </p>
        </div>
    </form>
</x-guest-layout>
