<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Selamat Datang</h2>
        <p class="text-slate-500 mt-2 text-sm">Masuk ke akun SinergiEdu Anda untuk melanjutkan.</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-sm text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" class="mb-1.5" />
            <x-text-input id="email" class="w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Masukkan email Anda" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <x-input-label for="password" value="Kata Sandi" class="mb-0" />
                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition" href="{{ route('password.request') }}">
                        Lupa sandi?
                    </a>
                @endif
            </div>

            <x-text-input id="password" class="w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="Masukkan kata sandi" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 w-4 h-4 transition" name="remember">
                <span class="ms-2 text-sm text-slate-600 font-medium">Ingat Saya</span>
            </label>
        </div>

        <div>
            <x-button type="submit" variant="primary" class="w-full text-base py-3">
                Masuk Sekarang
            </x-button>
        </div>
        
        <div class="mt-6 text-center">
            <p class="text-sm text-slate-500">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-800 transition">Daftar di sini</a>
            </p>
        </div>
    </form>
</x-guest-layout>
