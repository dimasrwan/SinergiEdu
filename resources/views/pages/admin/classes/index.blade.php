<x-layouts.app>
    <x-slot:title>Manajemen Kelas</x-slot:title>

    <div class="w-full">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Manajemen Kelas</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Kelola master data kelas, wali kelas, dan tahun ajaran aktif. 
                    <span class="inline-flex items-center ml-2 px-2 py-0.5 rounded text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                        {{ $classes->total() }} kelas terdaftar
                    </span>
                </p>
            </div>
            <div class="flex gap-2 w-full sm:w-auto shrink-0">
                <x-button variant="primary" href="{{ route('admin.classes.create') }}" class="flex-1 sm:flex-none justify-center">
                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Kelas
                </x-button>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-xl flex items-start gap-3 shadow-sm">
                <svg class="h-5 w-5 text-green-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div>
                    <h3 class="text-sm font-bold text-green-800">Berhasil</h3>
                    <p class="text-sm text-green-700 mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-xl flex items-start gap-3 shadow-sm">
                <svg class="h-5 w-5 text-danger mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <div>
                    <h3 class="text-sm font-bold text-red-800">Gagal</h3>
                    <p class="text-sm text-red-700 mt-0.5">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <x-card padding="none" class="overflow-hidden">
            <!-- Filter Bar -->
            <div class="p-4 md:p-5 border-b border-slate-100 bg-slate-50/50">
                <form action="{{ route('admin.classes.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                    <div class="flex-1">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kelas..." 
                                class="block w-full pl-9 pr-3 py-2 border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent text-sm">
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3 md:w-auto w-full shrink-0">
                        <select name="grade_level" class="block w-full sm:w-40 py-2 pl-3 pr-8 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent text-sm">
                            <option value="">Semua Tingkat</option>
                            <option value="10" {{ request('grade_level') == '10' ? 'selected' : '' }}>X</option>
                            <option value="11" {{ request('grade_level') == '11' ? 'selected' : '' }}>XI</option>
                            <option value="12" {{ request('grade_level') == '12' ? 'selected' : '' }}>XII</option>
                        </select>
                        
                        <select name="academic_year_id" class="block w-full sm:w-48 py-2 pl-3 pr-8 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent text-sm">
                            <option value="">Semua Tahun Ajaran</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                    {{ $year->year }}
                                </option>
                            @endforeach
                        </select>
                        
                        <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl border border-slate-200 transition-colors">
                            Filter
                        </button>
                        @if(request('search') || request('grade_level') || request('academic_year_id'))
                            <a href="{{ route('admin.classes.index') }}" class="px-4 py-2 bg-white hover:bg-red-50 text-slate-500 hover:text-danger text-sm font-semibold rounded-xl border border-slate-200 transition-colors text-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-slate-100">
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider w-10">NO</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">KELAS</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden sm:table-cell">TINGKAT</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden md:table-cell">WALI KELAS</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider hidden lg:table-cell">TAHUN AJARAN</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center">JUMLAH SISWA</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($classes as $index => $class)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="py-4 px-6 text-sm font-medium text-slate-400">
                                    {{ $classes->firstItem() + $index }}
                                </td>
                                <td class="py-4 px-6">
                                    <p class="text-sm font-bold text-primary">{{ $class->name }}</p>
                                    <!-- Mobile Info Fallback -->
                                    <div class="lg:hidden mt-1 space-y-0.5">
                                        <p class="text-[11px] text-slate-500 sm:hidden">Tingkat: {{ $class->grade_level == '10' ? 'X' : ($class->grade_level == '11' ? 'XI' : ($class->grade_level == '12' ? 'XII' : $class->grade_level)) }}</p>
                                        <p class="text-[11px] text-slate-500 md:hidden">Wali: {{ $class->homeroomTeacher->user->name ?? 'Belum ditentukan' }}</p>
                                        <p class="text-[11px] text-slate-500 lg:hidden">Tahun: {{ $class->academicYear->year ?? 'Belum ditentukan' }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-6 hidden sm:table-cell">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-primary font-bold text-xs shadow-sm">
                                        {{ $class->grade_level == '10' ? 'X' : ($class->grade_level == '11' ? 'XI' : ($class->grade_level == '12' ? 'XII' : $class->grade_level)) }}
                                    </div>
                                </td>
                                <td class="py-4 px-6 hidden md:table-cell text-sm text-slate-700 font-medium">
                                    {{ $class->homeroomTeacher->user->name ?? 'Belum ditentukan' }}
                                </td>
                                <td class="py-4 px-6 hidden lg:table-cell text-sm text-slate-700 font-medium">
                                    {{ $class->academicYear->year ?? 'Belum ditentukan' }}
                                </td>
                                <td class="py-4 px-6 text-center text-sm font-bold {{ $class->students_count > 0 ? 'text-slate-900' : 'text-slate-400' }}">
                                    {{ $class->students_count }} siswa
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.classes.show', $class) }}" class="p-1.5 text-slate-400 hover:text-accent hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </a>
                                        <a href="{{ route('admin.classes.edit', $class) }}" class="p-1.5 text-slate-400 hover:text-accent hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                        </a>
                                        <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?\n\nPERINGATAN: Jika kelas masih memiliki siswa, penghapusan akan gagal.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-danger hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center">
                                    <div class="mx-auto w-16 h-16 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-4">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-900">
                                        @if(request('search') || request('grade_level') || request('academic_year_id'))
                                            Tidak ada kelas yang ditemukan.
                                        @else
                                            Tidak ada data kelas
                                        @endif
                                    </h3>
                                    <p class="text-sm text-slate-500 mt-1 mb-4">
                                        @if(request('search') || request('grade_level') || request('academic_year_id'))
                                            Coba ubah kata kunci pencarian atau filter Anda.
                                        @else
                                            Belum ada data kelas yang didaftarkan dalam sistem.
                                        @endif
                                    </p>
                                    @if(!(request('search') || request('grade_level') || request('academic_year_id')))
                                        <x-button variant="primary" href="{{ route('admin.classes.create') }}" class="!py-2 !text-xs">
                                            Tambah Kelas
                                        </x-button>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($classes->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $classes->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
