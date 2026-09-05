<x-layouts.app>
    <x-slot:title>Penugasan Guru</x-slot:title>

    <div class="w-full">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Penugasan Guru</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Atur guru, mata pelajaran, kelas, dan periode mengajar.
                    <span class="inline-flex items-center ml-2 px-2 py-0.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                        {{ $assignments->total() }} penugasan
                    </span>
                </p>
            </div>
            <div class="flex gap-2 w-full sm:w-auto shrink-0">
                <x-button variant="primary" href="{{ route('admin.teacher-assignments.create') }}" class="flex-1 sm:flex-none justify-center">
                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Penugasan
                </x-button>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-xl flex items-start gap-3 shadow-sm">
                <svg class="h-5 w-5 text-green-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div>
                    <h3 class="text-sm font-bold text-emerald-700">Berhasil</h3>
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
                <form action="{{ route('admin.teacher-assignments.index') }}" method="GET" class="flex flex-col gap-3">
                    <div class="flex flex-col md:flex-row gap-3">
                        <div class="w-full md:w-1/4 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari guru, mata pelajaran, atau kelas..." class="block w-full pl-10 pr-3 py-2 text-sm border border-slate-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent">
                        </div>
                        <div class="w-full md:w-1/6">
                            <x-select name="academic_year_id" placeholder="Tahun Ajaran" :selected="request('academic_year_id')" :options="$academicYears->map(fn($y) => ['value' => $y->id, 'label' => $y->year])->toArray()" />
                        </div>
                        <div class="w-full md:w-1/6">
                            <x-semester-select name="semester_id" :selected="request('semester_id')" empty-label="Semester" />
                        </div>
                        <div class="w-full md:w-1/6">
                            <x-select name="class_id" placeholder="Kelas" :selected="request('class_id')" :options="$classrooms->map(fn($c) => ['value' => $c->id, 'label' => $c->name])->toArray()" />
                        </div>
                        <div class="flex gap-2 shrink-0 md:ml-auto">
                            <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg border border-slate-200 transition-colors w-full sm:w-auto text-center">
                                Filter
                            </button>
                            @if(request()->anyFilled(['search', 'academic_year_id', 'semester_id', 'class_id']))
                                <a href="{{ route('admin.teacher-assignments.index') }}" class="px-4 py-2 bg-white hover:bg-red-50 text-slate-500 hover:text-danger text-sm font-semibold rounded-xl border border-slate-200 transition-colors w-full sm:w-auto text-center">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead>
                        <tr class="bg-white border-b border-slate-100">
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider w-10">NO</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">GURU</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">MATA PELAJARAN</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">KELAS</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">TAHUN AJARAN</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">SEMESTER</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($assignments as $index => $assignment)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="py-4 px-6 text-sm font-medium text-slate-400">
                                    {{ $assignments->firstItem() + $index }}
                                </td>
                                <td class="py-4 px-6">
                                    <p class="text-sm font-bold text-slate-900">
                                        {{ $assignment->teacher->user->name }}
                                    </p>
                                    <p class="text-xs text-slate-500 mt-0.5">NIP. {{ $assignment->teacher->nip ?? '-' }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="text-sm font-bold text-primary">
                                        {{ $assignment->subject->name }}
                                    </p>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center text-xs font-semibold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-lg border border-slate-200">
                                        {{ $assignment->classroom->name }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-sm font-medium text-slate-600">
                                    {{ $assignment->academicYear->year }}
                                </td>
                                <td class="py-4 px-6 text-sm font-medium text-slate-600">
                                    {{ $assignment->semester->name }}
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2 ">
                                        <a href="{{ route('admin.teacher-assignments.edit', $assignment) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit" aria-label="Edit penugasan">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                        </a>
                                        <form action="{{ route('admin.teacher-assignments.destroy', $assignment) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penugasan ini?\n\nPENTING: Jika penugasan ini sudah digunakan oleh data pembelajaran (Materi/Tugas), sistem akan menolak penghapusan demi keamanan data.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg-lg transition-colors" title="Hapus" aria-label="Hapus penugasan">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center">
                                    <div class="mx-auto w-16 h-16 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-4">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-900">Belum ada penugasan</h3>
                                    <p class="text-sm text-slate-500 mt-1 mb-4">
                                        Data penugasan guru berdasarkan filter yang dipilih tidak ditemukan.
                                    </p>
                                    @if(!request()->anyFilled(['search', 'academic_year_id', 'semester_id', 'class_id']))
                                        <x-button variant="primary" href="{{ route('admin.teacher-assignments.create') }}" class="!py-2 !text-xs">
                                            Tambah Penugasan Pertama
                                        </x-button>
                                    @endif
                                </td>
                            </tr>
                        @endempty
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($assignments->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $assignments->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
