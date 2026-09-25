<x-layouts.app>
    <x-slot:title>Detail Komite Sekolah</x-slot:title>

    <div class="space-y-6 max-w-3xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Detail Komite Sekolah</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Informasi detail akun anggota Komite Sekolah.</p>
            </div>
            <a href="{{ route('admin.komite.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
                &larr; Kembali
            </a>
        </div>

        <x-card padding="md" class="space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">{{ $komite->name }}</h2>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $komite->email }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $komite->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                        {{ $komite->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <a href="{{ route('admin.komite.edit', $komite) }}" class="px-3 py-1.5 text-xs font-bold text-white bg-primary hover:bg-blue-800 rounded-lg transition-colors">
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
                    <span class="font-semibold text-slate-800 mt-0.5 block">{{ $komite->school->name ?? '-' }}</span>
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
        </x-card>
    </div>
</x-layouts.app>
