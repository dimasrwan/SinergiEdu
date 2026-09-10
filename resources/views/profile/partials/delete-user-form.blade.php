<section class="flex flex-col md:flex-row md:items-start justify-between gap-6">
    <div class="flex-1">
        <h2 class="text-xl font-bold text-slate-900">
            Hapus Akun Permanen
        </h2>
        <p class="mt-2 text-sm text-slate-500 leading-relaxed max-w-2xl">
            Setelah akun Anda dihapus, semua sumber daya dan data di dalamnya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.
        </p>
    </div>

    <div class="shrink-0 mt-2 md:mt-0">
        <button
            type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center px-4 py-2.5 bg-white border border-red-200 rounded-lg font-semibold text-sm text-red-600 hover:bg-red-50 hover:border-red-300 active:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm"
        >
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
            </svg>
            Hapus Akun
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @csrf
            @method('delete')

            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900">
                    Hapus Akun Permanen?
                </h2>
            </div>

            <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                Tindakan ini tidak dapat dibatalkan. Setelah akun dihapus, semua data akan hilang. Silakan masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun secara permanen.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="Kata Sandi" class="mb-1.5 font-semibold text-slate-700" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full"
                    placeholder="Masukkan kata sandi Anda"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-8 flex flex-col-reverse sm:flex-row justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="inline-flex justify-center items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto">
                    Batal
                </button>

                <button type="submit" class="inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:w-auto shadow-sm shadow-red-500/30">
                    Hapus Permanen
                </button>
            </div>
        </form>
    </x-modal>
</section>
