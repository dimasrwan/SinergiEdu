<x-layouts.app>
    <x-slot:title>Dashboard Super Admin</x-slot:title>

    <div class="space-y-6">
        <!-- System Management Banner -->
        <div class="bg-primary rounded-2xl p-6 text-white shadow-xl shadow-primary/20 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight mb-1">Platform Management Workspace</h1>
                    <p class="text-blue-200 text-xs sm:text-sm max-w-xl">
                        Kelola sekolah, pengguna, dan konfigurasi platform SinergiEdu dari satu tempat.
                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('super_admin.schools.create') }}">
                        <x-button variant="secondary" class="!bg-white/10 !border-white/20 !text-white hover:!bg-white/20 !py-2 !px-4">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tambah Sekolah
                        </x-button>
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-card padding="sm" class="hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Sekolah</span>
                    <div class="text-primary bg-blue-50 p-2.5 rounded-xl border border-blue-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M9 8h1"/><path d="M9 12h1"/><path d="M9 16h1"/><path d="M14 8h1"/><path d="M14 12h1"/><path d="M14 16h1"/><path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-bold tracking-tight text-slate-900 leading-none">{{ $totalSchools }}</h3>
                    <p class="text-xs text-slate-500 mt-2 font-medium">Institusi terdaftar</p>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Sekolah Aktif</span>
                    <div class="text-accent bg-sky-50 p-2.5 rounded-xl border border-sky-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-bold tracking-tight text-slate-900 leading-none">{{ $activeSchools }}</h3>
                    <p class="text-xs text-slate-500 mt-2 font-medium">Sistem berjalan</p>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Sekolah Nonaktif</span>
                    <div class="text-primary bg-blue-50 p-2.5 rounded-xl border border-blue-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-bold tracking-tight text-slate-900 leading-none">{{ $inactiveSchools }}</h3>
                    <p class="text-xs text-slate-500 mt-2 font-medium">Akses ditangguhkan</p>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Pengguna</span>
                    <div class="text-accent bg-sky-50 p-2.5 rounded-xl border border-sky-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-bold tracking-tight text-slate-900 leading-none">{{ $totalUsers }}</h3>
                    <p class="text-xs text-slate-500 mt-2 font-medium">Seluruh platform</p>
                </div>
            </x-card>
        </div>

        <!-- System Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- User Growth Line Chart Placeholder -->
            <div class="lg:col-span-2">
                <x-card padding="md" class="h-full border-t border-slate-100 flex flex-col">
                    <h3 class="text-base font-bold text-slate-900 mb-1">Pertumbuhan Sekolah ({{ date('Y') }})</h3>
                    <p class="text-xs text-slate-500 mb-6">Akumulasi sekolah yang bergabung dalam 6 bulan terakhir.</p>
                    
                    <div class="flex-1 flex flex-col items-center justify-center text-slate-400 py-10">
                        <svg class="w-12 h-12 mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                        <span class="text-sm font-medium">Belum ada data historis yang cukup.</span>
                    </div>
                </x-card>
            </div>

            <!-- Distribution Placeholder -->
            <div class="lg:col-span-1">
                <x-card padding="md" class="h-full border-t border-slate-100 flex flex-col">
                    <h3 class="text-base font-bold text-slate-900 mb-1">Distribusi Sekolah</h3>
                    <p class="text-xs text-slate-500 mb-6">Berdasarkan status operasional.</p>
                    
                    <div class="flex-1 flex flex-col justify-center gap-4">
                        <div class="flex items-center justify-between p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                </div>
                                <span class="font-medium text-emerald-900">Aktif</span>
                            </div>
                            <span class="text-2xl font-bold text-emerald-700">{{ $activeSchools }}</span>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-200">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-500">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                </div>
                                <span class="font-medium text-slate-600">Nonaktif</span>
                            </div>
                            <span class="text-2xl font-bold text-slate-700">{{ $inactiveSchools }}</span>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>

        <!-- Dashboard Grid (Recent Schools) -->
        <div class="grid grid-cols-1 gap-6 pt-2">
            <div class="col-span-1">
                <x-card padding="none" class="overflow-hidden">
                    <div class="border-b border-slate-100 bg-white px-6 py-5 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Sekolah Terbaru</h3>
                            <p class="text-xs text-slate-500 mt-1">Daftar sekolah yang baru saja didaftarkan ke platform.</p>
                        </div>
                        <a href="{{ route('super_admin.schools.index') }}" class="text-sm font-medium text-primary hover:text-blue-700 transition-colors">Lihat Semua</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Sekolah</th>
                                    <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">NPSN</th>
                                    <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Pengguna</th>
                                    <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Tgl Bergabung</th>
                                    <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($recentSchools as $school)
                                    <tr class="hover:bg-slate-50/80 transition-colors group">
                                        <td class="px-6 py-4 font-semibold text-slate-900 text-sm flex items-center gap-3">
                                            @if($school->logo)
                                                <img src="{{ Storage::url($school->logo) }}" alt="Logo" class="w-8 h-8 rounded-lg object-cover border border-slate-200">
                                            @else
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M9 8h1m-1 4h1m-1 4h1m4-8h1m-1 4h1m-1 4h1M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" /></svg>
                                                </div>
                                            @endif
                                            {{ $school->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-600">{{ $school->npsn ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-600">{{ number_format($school->users_count) }} akun</td>
                                        <td class="px-6 py-4 text-sm text-slate-500">{{ \Carbon\Carbon::parse($school->created_at)->translatedFormat('d M Y') }}</td>
                                        <td class="px-6 py-4">
                                            @if($school->is_active)
                                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-green-50 text-green-700 text-[10px] font-bold uppercase tracking-wider">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider border border-slate-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('super_admin.schools.show', $school) }}" class="p-1.5 text-slate-400 hover:text-primary transition" title="Lihat"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg></a>
                                                <a href="{{ route('super_admin.schools.edit', $school) }}" class="p-1.5 text-slate-400 hover:text-accent transition" title="Edit" aria-label="Edit"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg></a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500">
                                            Belum ada sekolah terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.app>
