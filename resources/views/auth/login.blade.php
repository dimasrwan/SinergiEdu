<x-guest-layout title="Masuk ke Sistem — SinergiEdu">
    <div class="mb-8">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0B1733] tracking-tight">Selamat Datang</h2>
        <p class="text-[#64748B] mt-2 text-sm">Masuk ke akun SinergiEdu Anda untuk melanjutkan.</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 p-4 bg-[#EAF6FF] border border-[#DCE8F3] rounded-xl text-sm font-medium text-[#123B82]">
            {{ session('status') }}
        </div>
    @endif

    <!-- Tombol Google SSO -->
    <div class="mb-6">
        <a href="{{ route('auth.google') }}" class="w-full min-h-[44px] py-3 px-4 inline-flex items-center justify-center gap-3 rounded-xl border border-[#DCE8F3] bg-white text-[#0B1733] text-sm font-bold hover:bg-[#F5FAFF] hover:border-[#123B82] focus:outline-none focus:ring-2 focus:ring-[#123B82] active:bg-[#EAF6FF] transition shadow-xs">
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
            <div class="w-full border-t border-[#DCE8F3]"></div>
        </div>
        <div class="relative flex justify-center text-xs uppercase">
            <span class="bg-white px-3 text-[#64748B] font-semibold tracking-wider">atau masuk dengan email</span>
        </div>
    </div>

    <!-- Form Login Email & Password -->
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-[#0B1733] mb-1.5">Alamat Email</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   autocomplete="username" 
                   placeholder="Masukkan alamat email Anda"
                   class="w-full min-h-[44px] px-4 rounded-xl border border-[#DCE8F3] bg-white text-[#0B1733] placeholder-[#94A3B8] text-sm focus:outline-none focus:ring-2 focus:ring-[#123B82] focus:border-[#123B82] transition" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-semibold text-[#0B1733]">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-[#123B82] hover:text-[#119FEA] transition" href="{{ route('password.request') }}">
                        Lupa sandi?
                    </a>
                @endif
            </div>

            <div x-data="{ show: false }" class="relative">
                <input id="password" 
                       :type="show ? 'text' : 'password'" 
                       name="password" 
                       required 
                       autocomplete="current-password" 
                       placeholder="Masukkan kata sandi Anda"
                       class="w-full min-h-[44px] pl-4 pr-11 rounded-xl border border-[#DCE8F3] bg-white text-[#0B1733] placeholder-[#94A3B8] text-sm focus:outline-none focus:ring-2 focus:ring-[#123B82] focus:border-[#123B82] transition" />
                <button type="button" 
                        @click="show = !show" 
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#94A3B8] hover:text-[#123B82] focus:outline-none transition-colors"
                        tabindex="-1"
                        aria-label="Lihat kata sandi">
                    <!-- Eye Off (Mata dicoret) saat password tersembunyi (!show) -->
                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                    <!-- Eye (Mata terbuka) saat password ditampilkan (show) -->
                    <svg x-cloak x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-[#DCE8F3] text-[#123B82] shadow-xs focus:ring-[#123B82] w-4 h-4 transition cursor-pointer">
                <span class="ms-2 text-sm text-[#0B1733] font-medium">Ingat Saya</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full min-h-[44px] py-3.5 px-6 rounded-xl bg-[#123B82] hover:bg-[#0F3170] active:scale-[0.98] text-white font-bold text-base transition shadow-md shadow-[#123B82]/20 focus:outline-none focus:ring-2 focus:ring-[#123B82]">
                Masuk Sekarang
            </button>
        </div>
        
        <!-- Public Registration Disabled Note -->
        <div class="mt-6 text-center">
            <p class="text-xs sm:text-sm text-[#64748B]">
                Belum memiliki akses? Hubungi Administrator Sekolah.
            </p>
        </div>
    </form>
</x-guest-layout>
