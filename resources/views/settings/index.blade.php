<x-layouts.app>
    <x-slot:title>Pengaturan</x-slot:title>
    
    <x-slot:sidebar>
        <div class="px-4 py-4 space-y-4">
            <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 text-sm font-semibold text-slate-600 hover:text-blue-700 hover:bg-blue-50 px-3 py-2 rounded-lg transition">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Dasbor
            </a>
            <div class="border-t border-slate-100 pt-4">
                <span class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Preferensi</span>
                <a href="#tampilan" class="flex items-center gap-3 text-sm font-semibold text-slate-700 bg-slate-50 px-3 py-2 rounded-lg transition">
                    Tampilan
                </a>
                <a href="#notifikasi" class="flex items-center gap-3 text-sm font-semibold text-slate-600 hover:bg-slate-50 px-3 py-2 rounded-lg transition">
                    Notifikasi
                </a>
            </div>
        </div>
    </x-slot:sidebar>

    <div class="w-full">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Pengaturan</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola preferensi akun dan pengalaman Anda di SinergiEdu.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200/60 flex items-start gap-3">
                <svg class="h-5 w-5 text-emerald-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h3 class="text-sm font-bold text-emerald-800">Berhasil</h3>
                    <p class="text-sm text-emerald-700 mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <form action="{{ route('settings.preferences.update') }}" method="POST" x-data="{ submitting: false }" @submit="submitting = true">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Theme Settings -->
                <div id="tampilan" class="scroll-mt-24">
                    <div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-2">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" />
                            </svg>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Tampilan</h2>
                                <p class="text-sm text-slate-500 mt-0.5">Atur tampilan aplikasi sesuai preferensi Anda.</p>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Light -->
                                <label class="relative flex cursor-pointer rounded-2xl border p-5 focus:outline-none transition-colors"
                                       :class="{'border-blue-500 ring-1 ring-blue-500 bg-blue-50/50': '{{ old('theme', $preferences->theme) }}' === 'light', 'border-slate-200 bg-white hover:bg-slate-50': '{{ old('theme', $preferences->theme) }}' !== 'light'}">
                                    <input type="radio" name="theme" value="light" class="sr-only" 
                                           {{ old('theme', $preferences->theme) === 'light' ? 'checked' : '' }}
                                           onchange="this.closest('.grid').querySelectorAll('label').forEach(l => { l.classList.remove('border-blue-500', 'ring-1', 'ring-blue-500', 'bg-blue-50/50'); l.classList.add('border-slate-200', 'bg-white', 'hover:bg-slate-50'); }); this.closest('label').classList.remove('border-slate-200', 'bg-white', 'hover:bg-slate-50'); this.closest('label').classList.add('border-blue-500', 'ring-1', 'ring-blue-500', 'bg-blue-50/50');">
                                    <span class="flex flex-1 flex-col items-start">
                                        <svg class="h-6 w-6 mb-3 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                        </svg>
                                        <span class="block text-sm font-semibold text-slate-900">Terang</span>
                                        <span class="mt-1 block text-sm text-slate-500">Gunakan tampilan terang SinergiEdu.</span>
                                    </span>
                                </label>

                                <!-- Dark -->
                                <label class="relative flex rounded-2xl border p-5 focus:outline-none transition-colors border-slate-200 bg-slate-50 opacity-60 cursor-not-allowed">
                                    <input type="radio" name="theme" value="dark" class="sr-only" disabled>
                                    <span class="flex flex-1 flex-col items-start">
                                        <svg class="h-6 w-6 mb-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                                        </svg>
                                        <span class="block text-sm font-semibold text-slate-400">Gelap</span>
                                        <span class="mt-1 block text-sm text-slate-400">Segera tersedia.</span>
                                    </span>
                                </label>

                                <!-- System -->
                                <label class="relative flex rounded-2xl border p-5 focus:outline-none transition-colors border-slate-200 bg-slate-50 opacity-60 cursor-not-allowed">
                                    <input type="radio" name="theme" value="system" class="sr-only" disabled>
                                    <span class="flex flex-1 flex-col items-start">
                                        <svg class="h-6 w-6 mb-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                                        </svg>
                                        <span class="block text-sm font-semibold text-slate-400">Sistem</span>
                                        <span class="mt-1 block text-sm text-slate-400">Segera tersedia.</span>
                                    </span>
                                </label>
                            </div>
                            @error('theme')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div id="notifikasi" class="scroll-mt-24">
                    <div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-2">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Notifikasi</h2>
                                <p class="text-sm text-slate-500 mt-0.5">Atur jenis notifikasi yang ingin Anda terima.</p>
                            </div>
                        </div>
                        <div class="p-6 space-y-6">
                            
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4" x-data="{ checked: {{ old('email_notifications', $preferences->email_notifications) ? 'true' : 'false' }} }">
                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 flex-shrink-0">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                        </svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-slate-900" id="email-label">Notifikasi Email</span>
                                        <span class="text-sm text-slate-500 mt-0.5" id="email-desc">Terima informasi melalui email.</span>
                                    </div>
                                </div>
                                <div class="flex h-6 items-center gap-3">
                                    <span class="text-xs font-bold w-14 text-right transition-colors" :class="checked ? 'text-blue-600' : 'text-slate-400'" x-text="checked ? 'Aktif' : 'Nonaktif'"></span>
                                    <label class="relative inline-flex items-center cursor-pointer" aria-labelledby="email-label" aria-describedby="email-desc">
                                        <input type="checkbox" name="email_notifications" value="1" class="sr-only peer" x-model="checked">
                                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 peer-focus:ring-offset-2 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="border-t border-slate-100"></div>

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4" x-data="{ checked: {{ old('push_notifications', $preferences->push_notifications) ? 'true' : 'false' }} }">
                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 flex-shrink-0">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                        </svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-slate-900" id="push-label">Notifikasi Push</span>
                                        <span class="text-sm text-slate-500 mt-0.5" id="push-desc">Terima pemberitahuan langsung di aplikasi jika sistem mendukungnya.</span>
                                    </div>
                                </div>
                                <div class="flex h-6 items-center gap-3">
                                    <span class="text-xs font-bold w-14 text-right transition-colors" :class="checked ? 'text-blue-600' : 'text-slate-400'" x-text="checked ? 'Aktif' : 'Nonaktif'"></span>
                                    <label class="relative inline-flex items-center cursor-pointer" aria-labelledby="push-label" aria-describedby="push-desc">
                                        <input type="checkbox" name="push_notifications" value="1" class="sr-only peer" x-model="checked">
                                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 peer-focus:ring-offset-2 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="inline-flex justify-center rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" :disabled="submitting">
                        <span x-show="!submitting">Simpan Preferensi</span>
                        <span x-show="submitting">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
