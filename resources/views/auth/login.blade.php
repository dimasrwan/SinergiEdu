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

    <!-- Tombol Google SSO -->
    <div class="mb-6">
        <a href="{{ route('auth.google') }}" class="w-full min-h-[44px] py-2.5 px-4 inline-flex items-center justify-center gap-3 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold hover:bg-slate-50 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 active:bg-slate-100 transition shadow-sm">
            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
            <span>Lanjutkan dengan Google</span>
        </a>
    </div>

    <!-- Divider -->
    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-slate-200"></div>
        </div>
        <div class="relative flex justify-center text-xs uppercase">
            <span class="bg-white px-3 text-slate-400 font-medium tracking-wider">atau masuk dengan email</span>
        </div>
    </div>

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
                Belum memiliki akses? Hubungi Administrator Sekolah.
            </p>
        </div>
    </form>
</x-guest-layout>
