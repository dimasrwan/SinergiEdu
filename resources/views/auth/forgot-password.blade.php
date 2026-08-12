<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-slate-900 tracking-tight mb-2">Lupa Kata Sandi?</h2>
        <p class="text-sm text-slate-500 leading-relaxed">
            Tidak masalah. Cukup beri tahu kami alamat email Anda, dan kami akan mengirimkan tautan pengaturan ulang kata sandi melalui email agar Anda dapat memilih sandi yang baru.
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-sm text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" class="mb-1.5" />
            <x-text-input id="email" class="w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="Masukkan email Anda" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-button type="submit" variant="primary" class="w-full text-base py-3">
                Kirim Tautan Pemulihan
            </x-button>
        </div>
        
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition flex items-center justify-center gap-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Login
            </a>
        </div>
    </form>
</x-guest-layout>
