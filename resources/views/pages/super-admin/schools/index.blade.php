<x-layouts.app>
    <x-slot:title>Manajemen Sekolah</x-slot:title>

    <div class="space-y-6" x-data="{}">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between border-b border-slate-200 pb-5 gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Manajemen Sekolah</h1>
                <p class="mt-2 text-sm text-slate-500 max-w-2xl">
                    Kelola direktori sekolah dan tenant di platform SinergiEdu.
                    @if($schools->total() > 0)
                        <span class="inline-flex items-center ml-2 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold border border-slate-200">
                            {{ $schools->total() }} sekolah terdaftar
                        </span>
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto shrink-0">
                <x-button variant="primary" href="{{ route('super_admin.schools.create') }}">
                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Sekolah
                </x-button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm text-emerald-800 flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Toolbar (Search & Filter) -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-col items-center justify-between gap-4">
            <form action="{{ route('super_admin.schools.index') }}" method="GET" class="w-full flex flex-col sm:flex-row gap-4">
                <!-- Search takes remaining width -->
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari sekolah atau NPSN..." 
                        class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent text-sm transition-shadow">
                </div>
                
                <!-- Filter Status (Compact) -->
                <div class="relative w-full sm:w-56 shrink-0">
                    <select name="status" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2.5 text-sm border border-slate-300 rounded-xl bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent appearance-none cursor-pointer font-medium">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Container -->
        <x-card padding="none" class="overflow-visible">
            <div class="overflow-x-auto lg:overflow-visible w-full">
                <table class="w-full text-left border-collapse min-w-[900px] table-fixed">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-[32%]">Sekolah</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-[12%]">NPSN</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-[25%]">Email</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-[12%]">Pengguna</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-[10%]">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right w-[9%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 w-full">
                        @forelse($schools as $school)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4 flex items-center gap-3">
                                    @if($school->logo)
                                        <img src="{{ Storage::url($school->logo) }}" alt="Logo" class="w-10 h-10 rounded-lg object-cover border border-slate-200 shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 shrink-0">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M9 8h1m-1 4h1m-1 4h1m4-8h1m-1 4h1m-1 4h1M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" /></svg>
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <div class="font-bold text-slate-900 text-sm truncate group-hover:text-primary transition-colors">
                                            <a href="{{ route('super_admin.schools.show', $school) }}" class="focus:outline-none">{{ $school->name }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-slate-500">{{ $school->npsn ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-slate-600 truncate block">{{ $school->email ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-slate-700">{{ number_format($school->users_count) }} akun</span>
                                </td>
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
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('super_admin.schools.show', $school) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </a>
                                        <a href="{{ route('super_admin.schools.edit', $school) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-4">
                                            @if(request('search') || request('status'))
                                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                                            @else
                                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" /></svg>
                                            @endif
                                        </div>
                                        
                                        @if(request('search') || request('status'))
                                            <h3 class="text-sm font-bold text-slate-900 mb-1">Tidak ada sekolah yang ditemukan.</h3>
                                            <p class="text-xs text-slate-500">Coba ubah kata kunci pencarian atau filter status Anda.</p>
                                        @else
                                            <h3 class="text-sm font-bold text-slate-900 mb-1">Belum ada sekolah</h3>
                                            <p class="text-xs text-slate-500 mb-4">Belum terdapat data tenant sekolah yang terdaftar di sistem.</p>
                                            <x-button variant="primary" href="{{ route('super_admin.schools.create') }}" class="!py-2 !text-xs">
                                                + Tambah Sekolah
                                            </x-button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($schools->hasPages())
                <div class="p-5 border-t border-slate-100 bg-slate-50/50">
                    {{ $schools->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
