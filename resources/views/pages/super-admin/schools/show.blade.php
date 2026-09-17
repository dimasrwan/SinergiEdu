<x-layouts.app title="Detail Sekolah">
    <div class="w-full space-y-6 pt-2 sm:pt-4" x-data="{}">
        <!-- Back Navigation -->
        <div class="mb-2 px-1">
            <a href="{{ route('super_admin.schools.index') }}" class="inline-flex items-center text-xs sm:text-sm font-semibold text-slate-500 hover:text-blue-600 gap-1.5 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Manajemen Sekolah
            </a>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl p-4 sm:p-6 md:p-8 shadow-sm space-y-8">
            <!-- Header Profil -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-100 pb-6">
                <div class="flex items-start sm:items-center gap-3.5 sm:gap-4 min-w-0 w-full sm:w-auto">
                    @if($school->logo)
                        <img src="{{ Storage::url($school->logo) }}" alt="Logo" class="h-14 w-14 sm:h-16 sm:w-16 rounded-2xl object-cover border border-slate-200/80 shadow-2xs shrink-0">
                    @else
                        <div class="h-14 w-14 sm:h-16 sm:w-16 bg-blue-50 border border-blue-100 text-blue-600 font-bold rounded-2xl flex items-center justify-center shrink-0">
                            <svg class="h-7 w-7 sm:h-8 sm:w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M9 8h1m-1 4h1m-1 4h1m4-8h1m-1 4h1m-1 4h1M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" />
                            </svg>
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 leading-snug break-words whitespace-normal tracking-tight">{{ $school->name }}</h1>
                            @if($school->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg bg-green-50 text-green-700 text-[10px] font-bold uppercase tracking-wider shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider border border-slate-200 shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                </span>
                            @endif
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-1">NPSN: <span class="text-slate-700 font-bold">{{ $school->npsn ?? '-' }}</span></p>
                    </div>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto shrink-0 pt-2 sm:pt-0" x-data="{ deleteModalOpen: false, confirmName: '' }">
                    <form action="{{ route('super_admin.schools.toggle-status', $school) }}" method="POST" class="flex-1 sm:flex-initial" onsubmit="return confirm('Yakin ingin {{ $school->is_active ? 'menonaktifkan' : 'mengaktifkan' }} sekolah ini?')">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="is_active" value="{{ $school->is_active ? '0' : '1' }}">
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 text-xs sm:text-sm font-semibold {{ $school->is_active ? 'text-slate-700 hover:bg-slate-50 bg-white border border-slate-200' : 'text-white bg-green-600 hover:bg-green-700' }} rounded-xl transition duration-150 text-center shadow-2xs">
                            {{ $school->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                    <a href="{{ route('super_admin.schools.edit', $school) }}" class="flex-1 sm:flex-initial px-4 py-2 text-xs sm:text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition duration-150 inline-flex items-center justify-center gap-1.5 shadow-2xs">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                        Edit Profil
                    </a>
                    <button type="button" @click="confirmName = ''; deleteModalOpen = true" class="px-3 py-2 text-xs sm:text-sm font-semibold text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 rounded-xl transition duration-150 inline-flex items-center justify-center gap-1">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                        Hapus
                    </button>

                    <!-- Delete Modal in Show View -->
                    <div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center text-left">
                        <div x-show="deleteModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60" @click="deleteModalOpen = false"></div>
                        <div x-show="deleteModalOpen" x-transition class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xl max-w-lg w-full relative z-10 space-y-5">
                            @if($deletionEligibility['eligible'])
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3 text-red-600">
                                        <div class="w-10 h-10 rounded-full bg-red-50 border border-red-100 flex items-center justify-center shrink-0">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-slate-900">Hapus Sekolah Permanen?</h3>
                                            <p class="text-xs text-slate-500">Tindakan ini destruktif dan tidak dapat dibatalkan.</p>
                                        </div>
                                    </div>

                                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/80 text-xs sm:text-sm text-slate-700">
                                        Sekolah: <strong class="text-slate-900">{{ $school->name }}</strong>
                                    </div>

                                    <div class="text-xs text-slate-600 leading-relaxed">
                                        <p class="font-semibold text-slate-800">Tindakan ini akan menghapus data sekolah secara permanen.</p>
                                        <p class="mt-1">Ketik nama sekolah persis di bawah ini untuk mengonfirmasi penghapusan:</p>
                                    </div>

                                    <form action="{{ route('super_admin.schools.destroy', $school) }}" method="POST" class="space-y-4 pt-1">
                                        @csrf
                                        @method('DELETE')
                                        <input type="text" name="confirm_school_name" x-model="confirmName" placeholder="{{ $school->name }}" required class="block w-full px-3.5 py-2.5 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-red-500 focus:border-red-500">
                                        
                                        <div class="flex items-center justify-end gap-3 pt-2">
                                            <button type="button" @click="deleteModalOpen = false" class="px-4 py-2 text-xs sm:text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                                                Batal
                                            </button>
                                            <button type="submit" :disabled="confirmName.trim() !== @js($school->name).trim()" :class="confirmName.trim() === @js($school->name).trim() ? 'bg-red-600 hover:bg-red-700 text-white cursor-pointer' : 'bg-slate-200 text-slate-400 cursor-not-allowed'" class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl transition-colors">
                                                Hapus Permanen
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3 text-amber-600">
                                        <div class="w-10 h-10 rounded-full bg-amber-50 border border-amber-100 flex items-center justify-center shrink-0">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-slate-900">Penghapusan Permanen Tidak Tersedia</h3>
                                            <p class="text-xs text-amber-700 font-medium">Sekolah masih memiliki data terkait di sistem.</p>
                                        </div>
                                    </div>

                                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/80 text-xs text-slate-700">
                                        Sekolah: <strong class="text-slate-900">{{ $school->name }}</strong>
                                    </div>

                                    <div class="space-y-2">
                                        <p class="text-xs font-semibold text-slate-700">Rincian dependency yang memblokir penghapusan:</p>
                                        <div class="max-h-48 overflow-y-auto p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-1.5">
                                            @foreach($deletionEligibility['reasons'] as $reason)
                                                <div class="flex items-start gap-2 text-xs text-slate-600">
                                                    <span class="text-amber-500 font-bold">•</span>
                                                    <span>{{ $reason }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="p-3 bg-blue-50 border border-blue-100 rounded-xl text-xs text-blue-800 flex items-start gap-2.5">
                                        <svg class="h-4 w-4 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Gunakan fitur <strong>Nonaktifkan Sekolah</strong> untuk membatasi akses tanpa menghapus riwayat data.</span>
                                    </div>

                                    <div class="flex items-center justify-end gap-3 pt-2">
                                        <button type="button" @click="deleteModalOpen = false" class="px-4 py-2 text-xs sm:text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                                            Tutup
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Sections -->
            <div class="space-y-8">
                <!-- Informasi Sekolah Section -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                        <span class="w-1.5 h-4 bg-blue-600 rounded-full"></span>
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 uppercase tracking-wider">Informasi Sekolah</h2>
                    </div>
                    <div class="space-y-3.5 text-xs sm:text-sm pt-1">
                        <div class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-4">
                            <span class="text-slate-400 sm:w-36 shrink-0 font-medium uppercase tracking-wider text-[11px]">Email Utama</span>
                            <span class="font-bold text-slate-900 break-all leading-snug">{{ $school->email ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-4">
                            <span class="text-slate-400 sm:w-36 shrink-0 font-medium uppercase tracking-wider text-[11px]">Nomor Telepon</span>
                            <span class="font-bold text-slate-900 leading-snug">{{ $school->phone ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-4">
                            <span class="text-slate-400 sm:w-36 shrink-0 font-medium uppercase tracking-wider text-[11px]">Alamat Lengkap</span>
                            <span class="font-semibold text-slate-800 break-words whitespace-normal leading-snug">{{ $school->address ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Statistik Tenant Section -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                        <span class="w-1.5 h-4 bg-blue-600 rounded-full"></span>
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 uppercase tracking-wider">Statistik Tenant</h2>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 pt-1">
                        <div class="border border-slate-200/80 bg-slate-50/70 rounded-xl p-3.5 sm:p-4 text-center shadow-2xs">
                            <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Guru</p>
                            <p class="text-xl sm:text-2xl font-extrabold text-slate-900">{{ number_format($school->teachers_count) }}</p>
                        </div>
                        <div class="border border-slate-200/80 bg-slate-50/70 rounded-xl p-3.5 sm:p-4 text-center shadow-2xs">
                            <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Siswa</p>
                            <p class="text-xl sm:text-2xl font-extrabold text-slate-900">{{ number_format($school->students_count) }}</p>
                        </div>
                        <div class="border border-slate-200/80 bg-slate-50/70 rounded-xl p-3.5 sm:p-4 text-center shadow-2xs">
                            <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Kelas</p>
                            <p class="text-xl sm:text-2xl font-extrabold text-slate-900">{{ number_format($school->classrooms_count) }}</p>
                        </div>
                        <div class="border border-blue-200/80 bg-blue-50/60 rounded-xl p-3.5 sm:p-4 text-center shadow-2xs">
                            <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-blue-600 mb-1">Total Pengguna</p>
                            <p class="text-xl sm:text-2xl font-extrabold text-blue-700">{{ number_format($school->users_count) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Admin Sekolah Section -->
                <div class="space-y-4 pt-2">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-4 bg-blue-600 rounded-full"></span>
                            <h2 class="text-sm sm:text-base font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                Admin Sekolah 
                                <span class="inline-flex items-center rounded-md bg-blue-50 text-blue-700 px-2 py-0.5 text-xs font-bold border border-blue-100">{{ count($admins) }}</span>
                            </h2>
                        </div>
                        <a href="{{ route('super_admin.schools.admins.create', $school) }}" class="text-xs sm:text-sm font-bold text-blue-600 hover:text-blue-700 transition-colors inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Tambah Admin
                        </a>
                    </div>

                    <!-- Desktop Admin Table (md:block) -->
                    <div class="hidden md:block overflow-x-auto border border-slate-200/80 rounded-2xl shadow-2xs">
                        <table class="w-full text-left border-collapse min-w-max">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-200/80">
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Admin</th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tgl Bergabung</th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($admins as $admin)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="py-3.5 px-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-xs font-bold shrink-0 shadow-2xs">
                                                    {{ strtoupper(substr($admin->name, 0, 2)) }}
                                                </div>
                                                <p class="text-sm font-bold text-slate-900 break-words whitespace-normal">{{ $admin->name }}</p>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <p class="text-sm font-medium text-slate-600 break-all">{{ $admin->email }}</p>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <p class="text-sm text-slate-500 whitespace-nowrap">{{ $admin->created_at->format('d M Y') }}</p>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            @if($admin->is_active)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-green-50 text-green-700 text-[10px] font-bold uppercase tracking-wider">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider border border-slate-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-right">
                                            <div class="flex items-center justify-end gap-1.5">
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

                    <!-- Mobile Admin Card List (block md:hidden) -->
                    <div class="block md:hidden space-y-3">
                        @forelse($admins as $admin)
                            <div class="bg-white border border-slate-200/80 rounded-xl p-4 shadow-2xs space-y-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-xs font-bold shrink-0 shadow-2xs">
                                            {{ strtoupper(substr($admin->name, 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-slate-900 leading-snug break-words whitespace-normal">{{ $admin->name }}</p>
                                            <p class="text-xs text-slate-500 font-medium break-all mt-0.5">{{ $admin->email }}</p>
                                        </div>
                                    </div>
                                    @if($admin->is_active)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-green-50 text-green-700 text-[10px] font-bold uppercase tracking-wider shrink-0">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider border border-slate-200 shrink-0">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                        </span>
                                    @endif
                                </div>

                                <div class="flex flex-col sm:flex-row sm:items-center justify-between text-xs text-slate-500 pt-3 border-t border-slate-100 gap-2">
                                    <span class="font-medium">Bergabung: <strong class="text-slate-700">{{ $admin->created_at->format('d M Y') }}</strong></span>
                                    
                                    <div class="flex items-center gap-2 pt-1 sm:pt-0">
                                        <a href="{{ route('super_admin.schools.admins.edit', [$school, $admin]) }}" class="flex-1 sm:flex-initial text-center px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                            Edit
                                        </a>
                                        <form action="{{ route('super_admin.schools.admins.toggle-status', [$school, $admin]) }}" method="POST" class="flex-1 sm:flex-initial" onsubmit="return confirm('{{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Admin ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="is_active" value="{{ $admin->is_active ? 0 : 1 }}">
                                            <button type="submit" class="w-full text-center px-3 py-1.5 text-xs font-semibold {{ $admin->is_active ? 'text-red-700 bg-red-50 hover:bg-red-100' : 'text-green-700 bg-green-50 hover:bg-green-100' }} rounded-lg transition-colors">
                                                {{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-white border border-slate-100 rounded-xl p-6 text-center text-xs text-slate-500">
                                Belum ada Admin Sekolah untuk tenant ini.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Komite Sekolah Section -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-4 bg-blue-600 rounded-full"></span>
                            <h2 class="text-sm sm:text-base font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                Komite Sekolah 
                                <span class="inline-flex items-center rounded-md bg-blue-50 text-blue-700 px-2 py-0.5 text-xs font-bold border border-blue-100">{{ count($komites) }}</span>
                            </h2>
                        </div>
                        <a href="{{ route('super_admin.schools.komite.create', $school) }}" class="text-xs sm:text-sm font-bold text-blue-600 hover:text-blue-700 transition-colors inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            + Tambah Komite
                        </a>
                    </div>

                    <!-- Desktop Komite Table (md:block) -->
                    <div class="hidden md:block overflow-x-auto border border-slate-200/80 rounded-2xl shadow-2xs">
                        <table class="w-full text-left border-collapse min-w-max">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-200/80">
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Komite</th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($komites as $komiteItem)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="py-3.5 px-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-xs font-bold shrink-0 shadow-2xs">
                                                    {{ strtoupper(substr($komiteItem->name, 0, 2)) }}
                                                </div>
                                                <p class="text-sm font-bold text-slate-900 break-words whitespace-normal">{{ $komiteItem->name }}</p>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <p class="text-sm font-medium text-slate-600 break-all">{{ $komiteItem->email }}</p>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            @if($komiteItem->is_active)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-green-50 text-green-700 text-[10px] font-bold uppercase tracking-wider">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider border border-slate-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-right">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <a href="{{ route('super_admin.schools.komite.edit', [$school, $komiteItem]) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                                </a>
                                                <form action="{{ route('super_admin.schools.komite.toggle-status', [$school, $komiteItem]) }}" method="POST" class="inline" onsubmit="return confirm('{{ $komiteItem->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Komite ini?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="is_active" value="{{ $komiteItem->is_active ? 0 : 1 }}">
                                                    <button type="submit" class="p-1.5 {{ $komiteItem->is_active ? 'text-slate-400 hover:text-red-600 hover:bg-red-50' : 'text-slate-400 hover:text-green-600 hover:bg-green-50' }} rounded-lg transition-colors" title="{{ $komiteItem->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        @if($komiteItem->is_active)
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
                                        <td colspan="4" class="py-8 text-center text-sm text-slate-500">
                                            Belum ada Komite Sekolah untuk tenant ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Komite Card List (block md:hidden) -->
                    <div class="block md:hidden space-y-3">
                        @forelse($komites as $komiteItem)
                            <div class="bg-white border border-slate-200/80 rounded-xl p-4 shadow-2xs space-y-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-xs font-bold shrink-0 shadow-2xs">
                                            {{ strtoupper(substr($komiteItem->name, 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-slate-900 leading-snug break-words whitespace-normal">{{ $komiteItem->name }}</p>
                                            <p class="text-xs text-slate-500 font-medium break-all mt-0.5">{{ $komiteItem->email }}</p>
                                        </div>
                                    </div>
                                    @if($komiteItem->is_active)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-green-50 text-green-700 text-[10px] font-bold uppercase tracking-wider shrink-0">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider border border-slate-200 shrink-0">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                                    <a href="{{ route('super_admin.schools.komite.edit', [$school, $komiteItem]) }}" class="px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                        Edit
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="bg-white border border-slate-100 rounded-xl p-6 text-center text-xs text-slate-500">
                                Belum ada Komite Sekolah untuk tenant ini.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pengawas Sekolah Section -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-4 bg-blue-600 rounded-full"></span>
                            <h2 class="text-sm sm:text-base font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                Pengawas Sekolah
                                <span class="inline-flex items-center rounded-md bg-blue-50 text-blue-700 px-2 py-0.5 text-xs font-bold border border-blue-100">{{ $school->supervisors->count() }}</span>
                            </h2>
                        </div>
                        <button type="button" x-on:click="$dispatch('open-modal', 'connect-pengawas-modal')" class="text-xs sm:text-sm font-bold text-blue-600 hover:text-blue-700 transition-colors inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Hubungkan Pengawas
                        </button>
                    </div>

                    <!-- Desktop Pengawas Table (md:block) -->
                    <div class="hidden md:block overflow-x-auto border border-slate-200/80 rounded-2xl shadow-2xs">
                        <table class="w-full text-left border-collapse min-w-max">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-200/80">
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Pengawas</th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">NIP</th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($school->supervisors as $supervisor)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="py-3.5 px-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-xs font-bold shrink-0 shadow-2xs">
                                                    {{ strtoupper(substr($supervisor->name, 0, 2)) }}
                                                </div>
                                                <p class="text-sm font-bold text-slate-900 break-words whitespace-normal">{{ $supervisor->name }}</p>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <p class="text-sm font-mono text-slate-600">{{ $supervisor->pengawas?->nip ?? '-' }}</p>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <p class="text-sm font-medium text-slate-600 break-all">{{ $supervisor->email }}</p>
                                        </td>
                                        <td class="py-3.5 px-4 text-right">
                                            <form action="{{ route('super_admin.schools.supervisors.detach', [$school, $supervisor]) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin melepas pengawas ini dari {{ $school->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                                    Lepas
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-sm text-slate-500">
                                            Belum ada Pengawas Sekolah yang terhubung ke tenant ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Pengawas Card List (block md:hidden) -->
                    <div class="block md:hidden space-y-3">
                        @forelse($school->supervisors as $supervisor)
                            <div class="bg-white border border-slate-200/80 rounded-xl p-4 shadow-2xs space-y-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-xs font-bold shrink-0 shadow-2xs">
                                            {{ strtoupper(substr($supervisor->name, 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-slate-900 leading-snug break-words whitespace-normal">{{ $supervisor->name }}</p>
                                            <p class="text-xs text-slate-500 font-medium break-all mt-0.5">{{ $supervisor->email }}</p>
                                            @if($supervisor->pengawas?->nip)
                                                <p class="text-xs text-slate-400 font-mono mt-0.5">NIP: {{ $supervisor->pengawas->nip }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <form action="{{ route('super_admin.schools.supervisors.detach', [$school, $supervisor]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin melepas pengawas ini dari {{ $school->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                            Lepas
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="bg-white border border-slate-100 rounded-xl p-6 text-center text-xs text-slate-500">
                                Belum ada Pengawas Sekolah yang terhubung ke tenant ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hubungkan Pengawas -->
    <x-modal name="connect-pengawas-modal" maxWidth="md">
        <form action="{{ route('super_admin.schools.supervisors.attach', $school) }}" method="POST" class="p-6">
            @csrf
            <h2 class="text-lg font-bold text-slate-900 mb-2">Hubungkan Pengawas Sekolah</h2>
            <p class="text-xs text-slate-500 mb-4">Pilih Pengawas yang ingin ditugaskan ke sekolah <strong>{{ $school->name }}</strong>.</p>

            @if(isset($availablePengawas) && count($availablePengawas) > 0)
                <div class="mb-4">
                    <label for="user_id" class="block text-xs font-semibold text-slate-700 mb-1">Pilih Pengawas <span class="text-red-500">*</span></label>
                    <x-searchable-select name="user_id" id="user_id" placeholder="-- Pilih Pengawas --" required>
                        <option value="">-- Pilih Pengawas --</option>
                        @foreach($availablePengawas as $ap)
                            <option value="{{ $ap->id }}">{{ $ap->name }} ({{ $ap->email }})</option>
                        @endforeach
                    </x-searchable-select>
                </div>
                <div class="flex justify-end gap-2">
                    <x-button variant="secondary" type="button" x-on:click="$dispatch('close-modal', 'connect-pengawas-modal')">Batal</x-button>
                    <x-button variant="primary" type="submit">Hubungkan</x-button>
                </div>
            @else
                <div class="p-4 bg-slate-50 rounded-xl text-center text-xs text-slate-500 mb-4">
                    Semua Pengawas yang terdaftar di sistem sudah terhubung ke sekolah ini atau belum ada akun Pengawas yang tersedia.
                </div>
                <div class="flex justify-end">
                    <x-button variant="secondary" type="button" x-on:click="$dispatch('close-modal', 'connect-pengawas-modal')">Tutup</x-button>
                </div>
            @endif
        </form>
    </x-modal>
</x-layouts.app>


