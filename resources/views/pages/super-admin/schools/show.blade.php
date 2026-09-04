<x-layouts.app title="Detail Sekolah">
    <div class="max-w-5xl space-y-6 mx-auto" x-data="{}">
        <div class="mb-4">
            <a href="{{ route('super_admin.schools.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Manajemen Sekolah
            </a>
        </div>

        <div class="bg-white border border-slate-200/60 rounded-3xl p-6 md:p-8 shadow-sm space-y-8">
            <!-- Header Profil -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b pb-6">
                <div class="flex items-center gap-4">
                    @if($school->logo)
                        <img src="{{ Storage::url($school->logo) }}" alt="Logo" class="h-16 w-16 rounded-2xl object-cover border border-slate-100">
                    @else
                        <div class="h-16 w-16 bg-blue-100 text-blue-700 font-bold rounded-2xl flex items-center justify-center">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M9 8h1m-1 4h1m-1 4h1m4-8h1m-1 4h1m-1 4h1M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" />
                            </svg>
                        </div>
                    @endif
                    <div>
                        <div class="flex items-center gap-3">
                            <h1 class="text-2xl font-bold text-slate-950">{{ $school->name }}</h1>
                            @if($school->is_active)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-green-50 text-green-700 text-[10px] font-bold uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-slate-500 font-medium mt-1">NPSN: {{ $school->npsn ?? '-' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('super_admin.schools.toggle-status', $school) }}" method="POST" onsubmit="return confirm('Yakin ingin {{ $school->is_active ? 'menonaktifkan' : 'mengaktifkan' }} sekolah ini?')">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="is_active" value="{{ $school->is_active ? '0' : '1' }}">
                        <button type="submit" class="px-4 py-2.5 text-sm font-semibold {{ $school->is_active ? 'text-slate-700 hover:bg-slate-50 bg-white border' : 'text-white bg-green-600 hover:bg-green-700' }} rounded-xl transition duration-150">
                            {{ $school->is_active ? 'Nonaktifkan Sekolah' : 'Aktifkan Sekolah' }}
                        </button>
                    </form>
                    <a href="{{ route('super_admin.schools.edit', $school) }}" class="px-4 py-2.5 text-sm font-semibold text-white bg-accent hover:bg-blue-600 rounded-xl transition duration-150 inline-flex items-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                        Edit Profil
                    </a>
                </div>
            </div>

            <!-- Detail Informasi -->
            <div class="grid grid-cols-1 gap-8">
                <div class="space-y-4">
                    <h2 class="text-base font-bold text-slate-900 border-b pb-2">Informasi Sekolah</h2>
                    <div class="space-y-3 text-sm">
                        <div class="grid grid-cols-3 md:grid-cols-6">
                            <span class="text-slate-500">Email Utama</span>
                            <span class="col-span-2 md:col-span-5 font-semibold text-slate-800">{{ $school->email ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-3 md:grid-cols-6">
                            <span class="text-slate-500">Nomor Telepon</span>
                            <span class="col-span-2 md:col-span-5 font-semibold text-slate-800">{{ $school->phone ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-3 md:grid-cols-6">
                            <span class="text-slate-500">Alamat Lengkap</span>
                            <span class="col-span-2 md:col-span-5 text-slate-800">{{ $school->address ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h2 class="text-base font-bold text-slate-900 border-b pb-2">Statistik Tenant</h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="border border-slate-100 bg-slate-50 rounded-xl p-4 text-center">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Guru</p>
                            <p class="text-2xl font-bold text-slate-900">{{ number_format($school->teachers_count) }}</p>
                        </div>
                        <div class="border border-slate-100 bg-slate-50 rounded-xl p-4 text-center">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Siswa</p>
                            <p class="text-2xl font-bold text-slate-900">{{ number_format($school->students_count) }}</p>
                        </div>
                        <div class="border border-slate-100 bg-slate-50 rounded-xl p-4 text-center">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Kelas</p>
                            <p class="text-2xl font-bold text-slate-900">{{ number_format($school->classrooms_count) }}</p>
                        </div>
                        <div class="border border-slate-100 bg-blue-50/50 rounded-xl p-4 text-center">
                            <p class="text-xs font-bold uppercase tracking-wider text-blue-500 mb-1">Total Pengguna</p>
                            <p class="text-2xl font-bold text-primary">{{ number_format($school->users_count) }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b pb-2">
                        <h2 class="text-base font-bold text-slate-900">Admin Sekolah <span class="ml-2 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-600">{{ count($admins) }}</span></h2>
                        <a href="{{ route('super_admin.schools.admins.create', $school) }}" class="text-sm font-semibold text-primary hover:text-blue-700 transition-colors">
                            + Tambah Admin
                        </a>
                    </div>

                    <div class="overflow-x-auto border border-slate-100 rounded-xl">
                        <table class="w-full text-left border-collapse min-w-max">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Admin</th>
                                    <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                                    <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tgl Bergabung</th>
                                    <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($admins as $admin)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">
                                                    {{ strtoupper(substr($admin->name, 0, 2)) }}
                                                </div>
                                                <p class="text-sm font-bold text-slate-900">{{ $admin->name }}</p>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <p class="text-sm text-slate-600">{{ $admin->email }}</p>
                                        </td>
                                        <td class="py-3 px-4">
                                            <p class="text-sm text-slate-500">{{ $admin->created_at->format('d M Y') }}</p>
                                        </td>
                                        <td class="py-3 px-4">
                                            @if($admin->is_active)
                                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-green-50 text-green-700 text-[10px] font-bold uppercase tracking-wider">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider border border-slate-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <div class="flex items-center justify-end gap-1.5 ">
                                                <a href="{{ route('super_admin.schools.admins.edit', [$school, $admin]) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                                </a>
                                                <form action="{{ route('super_admin.schools.admins.toggle-status', [$school, $admin]) }}" method="POST" class="inline" onsubmit="return confirm('{{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Admin ini?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="is_active" value="{{ $admin->is_active ? 0 : 1 }}">
                                                    <button type="submit" class="p-1.5 {{ $admin->is_active ? 'text-slate-400 hover:text-red-600 hover:bg-red-50' : 'text-slate-400 hover:text-green-600 hover:bg-green-50' }} rounded-lg transition-colors" title="{{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        @if($admin->is_active)
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                                        @else
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                        @endif
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-sm text-slate-500">
                                            Belum ada Admin Sekolah untuk tenant ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
