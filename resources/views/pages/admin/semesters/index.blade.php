<x-layouts.app>
    <x-slot:title>Manajemen Semester</x-slot:title>

    <div class="w-full">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Manajemen Semester</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Kelola semester akademik yang digunakan sebagai konteks waktu dalam penilaian dan pembelajaran.
                    <span class="inline-flex items-center ml-2 px-2 py-0.5 rounded text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                        {{ $semesters->total() }} semester
                    </span>
                </p>
            </div>
            <div class="flex gap-2 w-full sm:w-auto shrink-0">
                <x-button variant="primary" href="{{ route('admin.semesters.create') }}" class="flex-1 sm:flex-none justify-center">
                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Semester
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
                    <h3 class="text-sm font-bold text-red-800">Gagal Menghapus</h3>
                    <p class="text-sm text-red-700 mt-0.5">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <x-card padding="none" class="overflow-hidden">
            <!-- Filter Bar -->
            <div class="p-4 md:p-5 border-b border-slate-100 bg-slate-50/50">
                <form action="{{ route('admin.semesters.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1 flex gap-3 flex-col sm:flex-row">
                        <div class="w-full sm:w-64">
                            <select name="academic_year_id" class="block w-full pl-3 pr-10 py-2 text-sm border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent">
                                <option value="">Semua Tahun Ajaran</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                        Tahun Ajaran {{ $year->year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 shrink-0">
                        <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl border border-slate-200 transition-colors w-full sm:w-auto text-center">
                            Filter
                        </button>
                        @if(request('academic_year_id'))
                            <a href="{{ route('admin.semesters.index') }}" class="px-4 py-2 bg-white hover:bg-red-50 text-slate-500 hover:text-danger text-sm font-semibold rounded-xl border border-slate-200 transition-colors w-full sm:w-auto text-center">
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
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">TAHUN AJARAN</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">SEMESTER</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">STATUS</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($semesters as $index => $semester)
                            <tr class="hover:bg-slate-50/50 transition-colors group {{ $semester->is_active ? 'bg-blue-50/30' : '' }}">
                                <td class="py-4 px-6 text-sm font-medium text-slate-400">
                                    {{ $semesters->firstItem() + $index }}
                                </td>
                                <td class="py-4 px-6">
                                    <p class="text-sm font-bold text-slate-900">
                                        {{ $semester->academicYear->year }}
                                    </p>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="text-sm font-bold {{ $semester->is_active ? 'text-primary' : 'text-slate-900' }}">
                                        {{ $semester->name }}
                                    </p>
                                </td>
                                <td class="py-4 px-6">
                                    @if($semester->is_active)
                                        <span class="inline-flex items-center text-[10px] font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded border border-green-200">AKTIF</span>
                                    @else
                                        <span class="inline-flex items-center text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">TIDAK AKTIF</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2 ">
                                        <a href="{{ route('admin.semesters.show', $semester) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail" aria-label="Lihat detail semester">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </a>
                                        <a href="{{ route('admin.semesters.edit', $semester) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit" aria-label="Edit semester">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                        </a>
                                        <form action="{{ route('admin.semesters.destroy', $semester) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Semester ini?\n\nPENTING: Jika Semester ini sedang dipakai oleh sistem penilaian siswa, sistem akan menolak penghapusan demi keamanan data.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus" aria-label="Hapus semester">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <div class="mx-auto w-16 h-16 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-4">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-900">
                                        @if(request('academic_year_id'))
                                            Semester tidak ditemukan.
                                        @else
                                            Belum ada semester
                                        @endif
                                    </h3>
                                    <p class="text-sm text-slate-500 mt-1 mb-4">
                                        @if(request('academic_year_id'))
                                            Tidak ada semester yang terdaftar pada Tahun Ajaran tersebut.
                                        @else
                                            Belum terdapat data semester yang terdaftar dalam sistem.
                                        @endif
                                    </p>
                                    @if(!request('academic_year_id'))
                                        <x-button variant="primary" href="{{ route('admin.semesters.create') }}" class="!py-2 !text-xs">
                                            Tambah Semester
                                        </x-button>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($semesters->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $semesters->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
