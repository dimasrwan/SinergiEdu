<x-layouts.app>
    <x-slot:title>Manajemen Guru</x-slot:title>



    <div class="space-y-6" x-data="{}">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between border-b border-slate-200 pb-5 mb-6 gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Daftar Guru</h1>
                <p class="mt-2 text-sm text-slate-500 max-w-2xl">
                    Kelola data tenaga pendidik, akun akses login, dan mata pelajaran yang diampu.
                    <span class="inline-flex items-center ml-2 px-2 py-0.5 rounded-lg bg-slate-100 text-slate-600 text-xs font-semibold border border-slate-200">
                        {{ $teachers->total() }} guru terdaftar
                    </span>
                </p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto shrink-0">
                <x-button variant="primary" href="{{ route('admin.teachers.create') }}">
                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Guru
                </x-button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm text-emerald-800 flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-sm text-red-800 flex flex-col gap-1">
                <div class="flex items-center gap-2 font-bold">
                    <svg class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Gagal Menyimpan Data</span>
                </div>
                <ul class="list-disc pl-9 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Search Toolbar -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
            <form action="{{ route('admin.teachers.index') }}" method="GET" class="w-full md:w-96 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIP..." 
                    class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-lg leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent sm:text-sm transition-shadow">
            </form>
            <!-- (Filter Dropdowns can be added here if needed in the future) -->
        </div>

        <!-- Table Container -->
        <x-card padding="none" class="overflow-visible">
            <div class="overflow-x-auto lg:overflow-visible">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama / NIP</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kontak</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas & Mapel</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($teachers as $teacher)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <!-- Avatar Inisial -->
                                        @php
                                            $name = $teacher->user->name ?? 'U N';
                                            $initials = collect(explode(' ', $name))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                                        @endphp
                                        <div class="w-10 h-10 rounded-full bg-blue-50 text-primary flex items-center justify-center text-sm font-bold border border-blue-100 shrink-0">
                                            {{ strtoupper($initials) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900 text-sm">{{ $teacher->user->name ?? '-' }}</div>
                                            <div class="text-xs text-slate-500 mt-0.5">NIP: {{ $teacher->nip ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-700">{{ $teacher->user->email ?? '-' }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $teacher->phone ?? 'Tanpa no. HP' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $assignments = \Illuminate\Support\Facades\DB::table('teacher_subjects')
                                            ->join('classes', 'teacher_subjects.class_id', '=', 'classes.id')
                                            ->join('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
                                            ->where('teacher_id', $teacher->id)
                                            ->select('classes.name as class_name', 'subjects.name as subject_name')
                                            ->get();
                                    @endphp
                                    
                                    @if($assignments->count() > 0)
                                        <div class="flex flex-col gap-1">
                                            @foreach($assignments as $assign)
                                                <div class="text-sm text-slate-700">
                                                    <span class="font-medium">{{ $assign->class_name }}</span> &middot; <span class="text-slate-500">{{ $assign->subject_name }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-sm text-slate-400 italic">Belum ada penugasan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex flex-wrap items-center justify-end gap-1.5">
                                        <button type="button" x-on:click.prevent="$dispatch('open-modal', 'add-assignment-{{ $teacher->id }}')" class="px-2.5 py-1 text-xs font-bold text-white bg-accent hover:bg-blue-600 rounded-lg transition-colors shrink-0">
                                            + Penugasan
                                        </button>
                                        <a href="{{ route('admin.teachers.show', $teacher) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail" aria-label="Lihat detail guru">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </a>
                                        <a href="{{ route('admin.teachers.edit', $teacher) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit" aria-label="Edit guru">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                        </a>
                                        <button type="button" x-on:click.prevent="$dispatch('open-modal', 'delete-teacher-{{ $teacher->id }}')" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus" aria-label="Hapus guru">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Delete Modal -->
                                    <x-modal name="delete-teacher-{{ $teacher->id }}" maxWidth="sm">
                                        <div class="p-6">
                                            <div class="w-12 h-12 rounded-full bg-red-100 text-danger flex items-center justify-center mb-4">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                            </div>
                                            <h2 class="text-lg font-bold text-slate-900">Konfirmasi Penghapusan</h2>
                                            <p class="mt-2 text-sm text-slate-600">Apakah Anda yakin ingin menghapus data guru <strong>{{ $teacher->user->name ?? '' }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
                                            <div class="mt-6 flex justify-end gap-3">
                                                <x-button variant="secondary" x-on:click="$dispatch('close-modal', 'delete-teacher-{{ $teacher->id }}')">Batal</x-button>
                                                <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-button variant="danger" type="submit">Hapus Data</x-button>
                                                </form>
                                            </div>
                                        </div>
                                    </x-modal>

                                    <!-- Add Assignment Modal -->
                                    <x-modal name="add-assignment-{{ $teacher->id }}" maxWidth="xl">
                                        <form action="{{ route('admin.teacher-assignments.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="redirect_to" value="teachers_index">
                                            <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                                            
                                            <div class="p-6 text-left whitespace-normal">
                                                <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-5">Tambah Penugasan Mengajar</h2>
                                                
                                                <div class="space-y-5">
                                                    <!-- Readonly Guru Info -->
                                                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 flex flex-col gap-1">
                                                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Guru</span>
                                                        <div class="font-bold text-slate-900">{{ $teacher->user->name ?? '-' }}</div>
                                                        <div class="text-xs text-slate-500 font-mono">NIP: {{ $teacher->nip ?? '-' }}</div>
                                                    </div>
                                                    
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                                        <div>
                                                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Mata Pelajaran <span class="text-danger">*</span></label>
                                                            <select name="subject_id" required class="block w-full py-2.5 px-3 text-sm border border-slate-300 focus:border-accent focus:ring focus:ring-accent/20 rounded-lg-lg bg-white shadow-sm cursor-pointer">
                                                                <option value="" disabled selected>-- Pilih Mapel --</option>
                                                                @foreach($subjects as $sub)
                                                                    <option value="{{ $sub->id }}" @selected(old('subject_id') == $sub->id)>{{ $sub->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kelas <span class="text-danger">*</span></label>
                                                            <select name="class_id" required class="block w-full py-2.5 px-3 text-sm border border-slate-300 focus:border-accent focus:ring focus:ring-accent/20 rounded-lg-lg bg-white shadow-sm cursor-pointer">
                                                                <option value="" disabled selected>-- Pilih Kelas --</option>
                                                                @foreach($classrooms as $cls)
                                                                    <option value="{{ $cls->id }}" @selected(old('class_id') == $cls->id)>{{ $cls->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun Ajaran <span class="text-danger">*</span></label>
                                                            <select name="academic_year_id" required class="block w-full py-2.5 px-3 text-sm border border-slate-300 focus:border-accent focus:ring focus:ring-accent/20 rounded-lg-lg bg-white shadow-sm cursor-pointer">
                                                                <option value="" disabled selected>-- Pilih Tahun Ajaran --</option>
                                                                @foreach($academicYears as $ay)
                                                                    <option value="{{ $ay->id }}" @selected(old('academic_year_id', $academicYears->first()->id ?? null) == $ay->id)>{{ $ay->year }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Semester <span class="text-danger">*</span></label>
                                                            <x-semester-select name="semester_id" required class="block w-full py-2.5 px-3 text-sm border border-slate-300 focus:border-accent focus:ring focus:ring-accent/20 rounded-lg bg-white shadow-sm cursor-pointer" :selected="old('semester_id')" empty-label="-- Pilih Semester --" disabled-empty />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-8 flex justify-end gap-3 pt-5 border-t border-slate-100">
                                                    <button type="button" x-on:click.prevent="$dispatch('close-modal', 'add-assignment-{{ $teacher->id }}')" class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                                                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-primary rounded-lg-lg hover:bg-blue-900 transition-colors">Simpan Penugasan</button>
                                                </div>
                                            </div>
                                        </form>
                                    </x-modal>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-4">
                                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                                        </div>
                                        <h3 class="text-sm font-bold text-slate-900 mb-1">Belum ada guru</h3>
                                        <p class="text-xs text-slate-500 max-w-sm mx-auto mb-4">Belum terdapat data guru yang terdaftar dalam sistem, atau tidak ada yang cocok dengan pencarian Anda.</p>
                                        @if(request('search'))
                                            <a href="{{ route('admin.teachers.index') }}" class="text-xs font-semibold text-primary hover:text-primary-hover transition">Reset Pencarian</a>
                                        @else
                                            <x-button variant="primary" href="{{ route('admin.teachers.create') }}" class="!py-2 !px-4 !text-xs">
                                                Tambah Guru Pertama
                                            </x-button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($teachers->hasPages())
                <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4">
                    {{ $teachers->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
