<section>
    <header class="mb-6">
        <h2 class="text-xl font-bold text-slate-900">
            Informasi Pribadi
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Perbarui informasi nama dan alamat email akun Anda.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="name" value="Nama Lengkap" class="mb-1.5" />
                <x-text-input id="name" name="name" type="text" class="w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" value="Alamat Email" class="mb-1.5" />
                <x-text-input id="email" name="email" type="email" class="w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-3 p-3 bg-orange-50 border border-orange-200 rounded-xl">
                        <p class="text-sm text-orange-800">
                            Email Anda belum diverifikasi.

                            <button form="send-verification" class="mt-1 font-semibold underline text-orange-600 hover:text-orange-900 rounded-md focus:outline-none transition">
                                Klik di sini untuk mengirim ulang tautan verifikasi.
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-emerald-600">
                                Tautan verifikasi baru telah dikirimkan ke alamat email Anda.
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-100">
            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-medium text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100"
                >Berhasil disimpan.</p>
            @endif

            <x-button type="submit" variant="primary">Simpan Perubahan</x-button>
        </div>
    </form>
</section>
