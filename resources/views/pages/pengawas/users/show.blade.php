<x-layouts.app>
    <x-slot:title>Detail User - {{ $user->name }}</x-slot:title>

    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950">Detail Pengguna</h1>
                <p class="text-sm text-slate-500 mt-1">Informasi profil dan peran pengguna di sekolah binaan.</p>
            </div>
            <a href="{{ route('pengawas.users.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <x-card padding="default">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-6 pb-6 border-b border-slate-100">
                <div class="h-20 w-20 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-2xl overflow-hidden shrink-0">
                    @if($user->profile_photo_path)
                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <div class="space-y-1 min-w-0 flex-1">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h2 class="text-xl font-bold text-slate-950 truncate">{{ $user->name }}</h2>
                        <x-badge variant="primary">{{ $user->role?->display_name ?? 'User' }}</x-badge>
                        @if($user->is_active)
                            <x-badge variant="success">Aktif</x-badge>
                        @else
                            <x-badge variant="slate">Nonaktif</x-badge>
                        @endif
                    </div>
                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                    <p class="text-xs text-slate-400">Sekolah: <strong class="text-slate-700 font-semibold">{{ $user->school?->name ?? '-' }}</strong></p>
                </div>
            </div>

            <div class="pt-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div class="space-y-1">
                    <span class="text-xs font-semibold uppercase text-slate-400">ID Pengguna</span>
                    <p class="text-slate-900 font-medium">#{{ $user->id }}</p>
                </div>
                <div class="space-y-1">
                    <span class="text-xs font-semibold uppercase text-slate-400">Tipe Akses (Role)</span>
                    <p class="text-slate-900 font-medium">{{ $user->role?->display_name ?? '-' }} ({{ $user->role?->name ?? '-' }})</p>
                </div>
                <div class="space-y-1">
                    <span class="text-xs font-semibold uppercase text-slate-400">Terdaftar Sejak</span>
                    <p class="text-slate-900 font-medium">{{ $user->created_at?->format('d M Y, H:i') ?? '-' }}</p>
                </div>
                <div class="space-y-1">
                    <span class="text-xs font-semibold uppercase text-slate-400">Pembaruan Terakhir</span>
                    <p class="text-slate-900 font-medium">{{ $user->updated_at?->format('d M Y, H:i') ?? '-' }}</p>
                </div>
            </div>
        </x-card>
    </div>
</x-layouts.app>
