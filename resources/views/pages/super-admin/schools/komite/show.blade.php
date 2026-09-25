<x-layouts.app title="Detail Komite Sekolah">
    <div class="w-full space-y-6 pt-2 sm:pt-4">
        <!-- Back Navigation -->
        <div class="mb-2 px-1">
            <a href="{{ route('super_admin.schools.show', $school) }}" class="inline-flex items-center text-xs sm:text-sm font-semibold text-slate-500 hover:text-blue-600 gap-1.5 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Detail Sekolah
            </a>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl p-4 sm:p-6 md:p-8 shadow-sm max-w-3xl space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900">{{ $komite->name }}</h1>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">{{ $komite->email }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $komite->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                        {{ $komite->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <a href="{{ route('super_admin.schools.komite.edit', [$school, $komite]) }}" class="px-3.5 py-1.5 text-xs font-bold text-white bg-primary hover:bg-blue-800 rounded-lg transition-colors">
                        Edit Akun
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs sm:text-sm">
                <div>
                    <span class="block text-slate-400 font-bold uppercase tracking-wider text-[10px]">Role Sistem</span>
                    <span class="font-semibold text-slate-800 mt-0.5 block">Komite Sekolah</span>
                </div>
                <div>
                    <span class="block text-slate-400 font-bold uppercase tracking-wider text-[10px]">Sekolah Terikat</span>
                    <span class="font-semibold text-slate-800 mt-0.5 block">{{ $school->name }}</span>
                </div>
                <div>
                    <span class="block text-slate-400 font-bold uppercase tracking-wider text-[10px]">Tanggal Pembuatan</span>
                    <span class="font-semibold text-slate-800 mt-0.5 block">{{ $komite->created_at ? $komite->created_at->translatedFormat('d M Y, H:i') : '-' }}</span>
                </div>
                <div>
                    <span class="block text-slate-400 font-bold uppercase tracking-wider text-[10px]">Status Email</span>
                    <span class="font-semibold text-slate-800 mt-0.5 block">{{ $komite->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi' }}</span>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
