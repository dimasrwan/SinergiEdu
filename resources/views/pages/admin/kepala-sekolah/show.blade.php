<x-layouts.app>
    <x-slot:title>Detail Kepala Sekolah/Madrasah</x-slot:title>

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.kepala-sekolah.index') }}" class="p-2 rounded-full hover:bg-slate-100 text-slate-500 hover:text-slate-700 transition">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Profil Kepala Sekolah/Madrasah</h1>
                    <p class="text-sm text-slate-500">Detail informasi profil dan akun sistem.</p>
                </div>
            </div>
            <div class="flex gap-2">
                <x-button variant="secondary" href="{{ route('admin.kepala-sekolah.edit', $kepalaSekolah) }}">
                    Edit Profil
                </x-button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Quick Info -->
            <div class="lg:col-span-1 space-y-6">
                <x-card padding="none" class="overflow-hidden">
                    <div class="p-6 flex flex-col items-center text-center border-b border-slate-100">
                        @php
                            $name = $kepalaSekolah->user->name ?? 'U N';
                            $initials = collect(explode(' ', $name))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                        @endphp
                        <div class="w-24 h-24 rounded-full bg-blue-50 text-primary flex items-center justify-center text-3xl font-bold border-4 border-white shadow-sm mb-4">
                            {{ strtoupper($initials) }}
                        </div>
                        <h2 class="text-xl font-bold text-slate-900">{{ $kepalaSekolah->user->name ?? '-' }}</h2>
                        <div class="mt-1 flex flex-col gap-1 items-center justify-center">
                            @if($kepalaSekolah->nip)
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-md border border-slate-200">
                                    NIP: {{ $kepalaSekolah->nip }}
                                </span>
                            @endif
                            <span class="px-2.5 py-1 bg-blue-50 text-primary text-xs font-semibold rounded-md border border-blue-100">
                                Kepala Sekolah/Madrasah
                            </span>
                        </div>
                    </div>
                    <div class="p-6 bg-slate-50/50">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Email Login</h3>
                                <div class="flex items-center gap-2 text-sm text-slate-700 font-medium">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                                    {{ $kepalaSekolah->user->email ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor Telepon</h3>
                                <div class="flex items-center gap-2 text-sm text-slate-700 font-medium">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.896-1.596-5.496-4.196-7.092-7.092l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                                    {{ $kepalaSekolah->phone ?? 'Tidak tersedia' }}
                                </div>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Alamat</h3>
                                <div class="flex items-start gap-2 text-sm text-slate-700 font-medium">
                                    <svg class="h-4 w-4 text-slate-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                    <span>{{ $kepalaSekolah->address ?? 'Tidak tersedia' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Right Column: Detail Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Pribadi & Akun -->
                <x-card padding="none">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-slate-900">Informasi Pribadi & Akun</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                            <div>
                                <dt class="text-sm font-semibold text-slate-500">Nama Lengkap</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $kepalaSekolah->user->name ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-semibold text-slate-500">NIP</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $kepalaSekolah->nip ?? 'Belum Diatur' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-semibold text-slate-500">Email Login</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $kepalaSekolah->user->email ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-semibold text-slate-500">Role Sistem</dt>
                                <dd class="mt-1 text-sm text-slate-900">Kepala Sekolah/Madrasah</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-semibold text-slate-500">Alamat Lengkap</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $kepalaSekolah->address ?? 'Belum Diatur' }}</dd>
                            </div>
                        </dl>
                    </div>
                </x-card>
            </div>
            
        </div>
    </div>
</x-layouts.app>
