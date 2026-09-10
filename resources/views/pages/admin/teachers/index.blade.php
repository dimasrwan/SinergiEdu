<x-layouts.app>
    <x-slot:title>Manajemen Guru</x-slot:title>

    <div class="space-y-6" x-data="{
        deleteModalOpen: false,
        teacherToDeleteId: null,
        teacherNameToDelete: '',
        confirmDelete(id, name) {
            this.teacherToDeleteId = id;
            this.teacherNameToDelete = name;
            this.deleteModalOpen = true;
        },
        submitDelete() {
            if (this.teacherToDeleteId) {
                const form = document.getElementById('delete-teacher-form-' + this.teacherToDeleteId);
                if (form) form.submit();
            }
        }
    }">
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
                <x-button variant="primary" href="{{ route('admin.teachers.create') }}" class="w-full sm:w-auto justify-center h-11 sm:h-10 rounded-xl">
                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Guru
                </x-button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm text-emerald-800 flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span class="min-w-0 flex-1 break-words">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-sm text-red-800 flex flex-col gap-1">
                <div class="flex items-center gap-2 font-bold">
                    <svg class="h-5 w-5 text-red-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Gagal Menyimpan Data</span>
                </div>
                <ul class="list-disc pl-9 mt-1">
                    @foreach($errors->all() as $error)
                        <li class="break-words">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Search Toolbar -->
        <div class="bg-white p-3.5 sm:p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
            <form action="{{ route('admin.teachers.index') }}" method="GET" class="w-full md:w-96">
                <div class="flex items-center w-full px-3 py-2 sm:py-2 border border-slate-300 rounded-xl bg-white focus-within:border-accent focus-within:ring-2 focus-within:ring-accent/20 transition-all">
                    <svg class="h-5 w-5 text-slate-400 shrink-0 mr-2.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIP..." 
                        class="w-full border-0 p-0 text-xs sm:text-sm bg-transparent placeholder-slate-400 focus:outline-none focus:ring-0 focus:border-0 min-w-0">
                </div>
            </form>
        </div>

        <!-- Mobile Card View (< 1024px) -->
        <div class="block lg:hidden space-y-3.5">
            @forelse($teachers as $teacher)
                @php
                    $name = $teacher->user->name ?? 'U N';
                    $initials = collect(explode(' ', $name))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                @endphp
                <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs space-y-3">
                    <!-- Identity Row -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <div class="w-10 h-10 rounded-full bg-blue-50 text-primary flex items-center justify-center text-sm font-bold border border-blue-100 shrink-0">
                                {{ strtoupper($initials) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-semibold text-slate-900 text-sm leading-snug break-words">
                                    {{ $teacher->user->name ?? '-' }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5 break-all">
                                    NIP: {{ $teacher->nip ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <!-- View Details Action -->
                        <a href="{{ route('admin.teachers.show', $teacher) }}" 
                           class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors shrink-0" 
                           title="Lihat Detail" 
                           aria-label="Lihat detail guru">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </a>
                    </div>

                    <!-- Metadata Grid (Email & Phone) -->
                    <div class="pt-2 border-t border-slate-100 grid grid-cols-1 gap-2 text-xs">
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Email</span>
                            <span class="text-slate-700 font-medium break-words leading-relaxed">
                                {{ $teacher->user->email ?? '-' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Telepon</span>
                            <span class="text-slate-700 font-medium break-all">
                                {{ $teacher->phone ?? 'Tanpa no. HP' }}
                            </span>
                        </div>
                    </div>

                    <!-- Actions Footer -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2 flex-wrap">
                        <button type="button" 
                                x-on:click.prevent="$dispatch('open-modal', 'add-assignment-{{ $teacher->id }}')" 
                                class="px-3 py-1.5 text-xs font-semibold text-white bg-accent hover:bg-blue-600 rounded-lg transition-colors shrink-0">
                            + Penugasan
                        </button>
                        <a href="{{ route('admin.teachers.edit', $teacher) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg transition-colors shrink-0">
                            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                            </svg>
                            Edit
                        </a>
                        <button type="button" 
                                x-on:click="confirmDelete('{{ $teacher->id }}', '{{ addslashes($teacher->user->name ?? '') }}')" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 border border-red-100 rounded-lg transition-colors shrink-0">
                            <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                            Hapus
                        </button>
                    </div>

                    <!-- Hidden Delete Form -->
                    <form id="delete-teacher-form-{{ $teacher->id }}" action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>

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
                                        <div class="font-bold text-slate-900 break-words">{{ $teacher->user->name ?? '-' }}</div>
                                        <div class="text-xs text-slate-500 font-mono break-all">NIP: {{ $teacher->nip ?? '-' }}</div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Mata Pelajaran <span class="text-danger">*</span></label>
                                            <x-select name="subject_id" required placeholder="-- Pilih Mapel --" :selected="old('subject_id')" :options="$subjects->map(fn($sub) => ['value' => $sub->id, 'label' => $sub->name])->toArray()" />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kelas <span class="text-danger">*</span></label>
                                            <x-select name="class_id" required placeholder="-- Pilih Kelas --" :selected="old('class_id')" :options="$classrooms->map(fn($cls) => ['value' => $cls->id, 'label' => $cls->name])->toArray()" />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun Ajaran <span class="text-danger">*</span></label>
                                            <x-select name="academic_year_id" required placeholder="-- Pilih Tahun Ajaran --" :selected="old('academic_year_id', $academicYears->first()->id ?? null)" :options="$academicYears->map(fn($ay) => ['value' => $ay->id, 'label' => $ay->year])->toArray()" />
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
                </div>
            @empty
                <div class="bg-white rounded-xl p-8 border border-slate-200 text-center">
                    <div class="w-14 h-14 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
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
            @endforelse
        </div>

        <!-- Desktop Table Container (>= 1024px) -->
        <div class="hidden lg:block">
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
                                        <div class="text-sm text-slate-700 break-words">{{ $teacher->user->email ?? '-' }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5 break-all">{{ $teacher->phone ?? 'Tanpa no. HP' }}</div>
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
                                            <button type="button" x-on:click="confirmDelete('{{ $teacher->id }}', '{{ addslashes($teacher->user->name ?? '') }}')" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus" aria-label="Hapus guru">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                            </button>
                                        </div>

                                        <!-- Hidden Delete Form -->
                                        <form id="delete-teacher-form-{{ $teacher->id }}" action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>

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
                                                            <div class="font-bold text-slate-900 break-words">{{ $teacher->user->name ?? '-' }}</div>
                                                            <div class="text-xs text-slate-500 font-mono break-all">NIP: {{ $teacher->nip ?? '-' }}</div>
                                                        </div>
                                                        
                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                                            <div>
                                                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Mata Pelajaran <span class="text-danger">*</span></label>
                                                                <x-select name="subject_id" required placeholder="-- Pilih Mapel --" :selected="old('subject_id')" :options="$subjects->map(fn($sub) => ['value' => $sub->id, 'label' => $sub->name])->toArray()" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kelas <span class="text-danger">*</span></label>
                                                                <x-select name="class_id" required placeholder="-- Pilih Kelas --" :selected="old('class_id')" :options="$classrooms->map(fn($cls) => ['value' => $cls->id, 'label' => $cls->name])->toArray()" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun Ajaran <span class="text-danger">*</span></label>
                                                                <x-select name="academic_year_id" required placeholder="-- Pilih Tahun Ajaran --" :selected="old('academic_year_id', $academicYears->first()->id ?? null)" :options="$academicYears->map(fn($ay) => ['value' => $ay->id, 'label' => $ay->year])->toArray()" />
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
            </x-card>
        </div>

        @if($teachers->hasPages())
            <div class="border-t border-slate-200 bg-white sm:bg-slate-50/50 rounded-xl px-4 sm:px-6 py-4 shadow-xs">
                {{ $teachers->links() }}
            </div>
        @endif

        <!-- Custom Delete Confirmation Modal (SinergiEdu Design System) -->
        <div 
            x-show="deleteModalOpen" 
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto" 
            role="dialog" 
            aria-modal="true"
            aria-labelledby="modal-title-delete-teacher"
            aria-describedby="modal-desc-delete-teacher"
            x-on:keydown.escape.window="deleteModalOpen = false"
        >
            <!-- Overlay -->
            <div 
                x-show="deleteModalOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"
                @click="deleteModalOpen = false"
                aria-hidden="true"
            ></div>

            <!-- Dialog Box -->
            <div 
                x-show="deleteModalOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative z-10 transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full md:w-[420px] md:max-w-[420px] md:flex-none max-h-[calc(100dvh-32px)] overflow-y-auto p-5 md:p-5 border border-slate-200 mx-auto my-auto shrink-0 box-border"
                @click.away="deleteModalOpen = false"
            >
                <!-- Trash Icon Container -->
                <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-red-50 text-red-600 mb-3 shrink-0">
                    <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </div>

                <!-- Content -->
                <div class="text-center space-y-1.5 w-full max-w-[360px] mx-auto min-w-0">
                    <h3 class="text-base sm:text-lg font-semibold text-slate-900 tracking-tight break-words px-1" id="modal-title-delete-teacher">
                        Hapus Data Guru?
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed break-words" id="modal-desc-delete-teacher">
                        Apakah Anda yakin ingin menghapus data guru &ldquo;<span x-text="teacherNameToDelete" class="font-semibold text-slate-900 break-all"></span>&rdquo;?
                    </p>
                </div>

                <!-- Warning Box -->
                <div class="mt-4 p-3 bg-amber-50/80 border border-amber-200/80 rounded-xl flex items-start gap-2.5 text-left w-full min-w-0 box-border">
                    <svg class="h-4 w-4 text-amber-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <div class="text-xs leading-relaxed text-amber-800 min-w-0 flex-1 break-words">
                        <span class="font-semibold block text-amber-900 mb-0.5">Tindakan Permanen</span>
                        Tindakan ini akan menghapus akun login guru dan seluruh asosiasi data terkait. Penghapusan tidak dapat dibatalkan.
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 md:mt-5 flex items-center justify-end gap-2 flex-wrap sm:flex-nowrap w-full">
                    <button 
                        type="button" 
                        @click="deleteModalOpen = false"
                        class="flex-1 sm:flex-initial min-w-[90px] px-4 h-10 bg-white hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold rounded-xl border border-slate-200 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300"
                    >
                        Batal
                    </button>
                    <button 
                        type="button" 
                        @click="submitDelete()"
                        class="flex-1 sm:flex-initial min-w-[90px] px-4 h-10 bg-red-600 hover:bg-red-700 text-white text-xs sm:text-sm font-semibold rounded-xl transition-colors shadow-2xs focus:outline-none focus:ring-2 focus:ring-red-500/30"
                    >
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
