<x-layouts.app>
    <x-slot:title>Detail Guru</x-slot:title>

    <div class="max-w-4xl space-y-6 mx-auto" x-data="{
        deleteModalOpen: false,
        assignmentToDeleteId: null,
        assignmentSubjectToDelete: '',
        assignmentClassToDelete: '',
        assignmentPeriodToDelete: '',
        confirmDeleteAssignment(id, subject, classroom, period) {
            this.assignmentToDeleteId = id;
            this.assignmentSubjectToDelete = subject;
            this.assignmentClassToDelete = classroom;
            this.assignmentPeriodToDelete = period;
            this.deleteModalOpen = true;
        },
        submitDeleteAssignment() {
            if (this.assignmentToDeleteId) {
                const form = document.getElementById('delete-assignment-form-' + this.assignmentToDeleteId);
                if (form) form.submit();
            }
        }
    }">
        <div class="mb-2">
            <a href="{{ route('admin.teachers.index') }}" class="inline-flex items-center text-xs sm:text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <div class="bg-white border border-slate-200/60 rounded-2xl sm:rounded-3xl p-4 sm:p-6 md:p-8 shadow-xs space-y-6 sm:space-y-8">
            <!-- Header Profil -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                <div class="flex items-center gap-3.5 sm:gap-4 min-w-0 w-full sm:w-auto">
                    <div class="h-12 w-12 sm:h-16 sm:w-16 bg-blue-100 text-blue-700 font-bold rounded-2xl flex items-center justify-center text-xl sm:text-2xl shrink-0">
                        {{ strtoupper(substr($teacher->user->name ?? 'G', 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-lg sm:text-2xl font-bold text-slate-950 leading-snug break-words">
                            {{ $teacher->user->name ?? '-' }}
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5 break-all">
                            NIP: {{ $teacher->nip ?? '-' }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 w-full sm:w-auto shrink-0">
                    <button type="button" x-on:click.prevent="$dispatch('open-modal', 'add-assignment')" class="w-full sm:w-auto px-4 h-11 sm:h-10 text-xs sm:text-sm font-semibold text-white bg-accent hover:bg-blue-600 rounded-xl transition duration-150 inline-flex items-center justify-center gap-1.5 shrink-0">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Tambah Penugasan
                    </button>
                    <a href="{{ route('admin.teachers.edit', $teacher) }}" class="w-full sm:w-auto px-4 h-11 sm:h-10 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 border border-slate-200 rounded-xl transition duration-150 inline-flex items-center justify-center shrink-0">
                        Edit Profil
                    </a>
                </div>
            </div>

            <!-- Detail Informasi -->
            <div class="grid grid-cols-1 gap-6 sm:gap-8">
                <div class="space-y-3 sm:space-y-4">
                    <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Informasi Guru</h2>
                    <div class="space-y-3.5 text-xs sm:text-sm">
                        <div class="flex flex-col sm:grid sm:grid-cols-6 gap-1 sm:gap-4">
                            <span class="text-slate-500 font-medium text-xs uppercase sm:normal-case tracking-wider sm:tracking-normal">Email</span>
                            <span class="sm:col-span-5 font-semibold text-slate-800 break-words leading-relaxed">{{ $teacher->user->email ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col sm:grid sm:grid-cols-6 gap-1 sm:gap-4">
                            <span class="text-slate-500 font-medium text-xs uppercase sm:normal-case tracking-wider sm:tracking-normal">Nomor HP</span>
                            <span class="sm:col-span-5 font-semibold text-slate-800 break-all">{{ $teacher->phone ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col sm:grid sm:grid-cols-6 gap-1 sm:gap-4">
                            <span class="text-slate-500 font-medium text-xs uppercase sm:normal-case tracking-wider sm:tracking-normal">Alamat</span>
                            <span class="sm:col-span-5 text-slate-800 break-words leading-relaxed">{{ $teacher->address ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <h2 class="text-base font-bold text-slate-900">Penugasan Mengajar</h2>
                        <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                            {{ count($assignments ?? []) }} penugasan
                        </span>
                    </div>
                    
                    @if(session('success'))
                        <div class="p-3.5 sm:p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-3 shadow-xs">
                            <svg class="h-5 w-5 text-emerald-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-xs sm:text-sm font-bold text-emerald-800">Berhasil</h3>
                                <p class="text-xs sm:text-sm text-emerald-700 mt-0.5 break-words">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="p-3.5 sm:p-4 bg-red-50 border border-red-100 rounded-xl flex items-start gap-3 shadow-xs">
                            <svg class="h-5 w-5 text-red-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-xs sm:text-sm font-bold text-red-800">Gagal</h3>
                                <p class="text-xs sm:text-sm text-red-700 mt-0.5 break-words">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="p-3.5 sm:p-4 bg-red-50 border border-red-100 rounded-xl flex items-start gap-3 shadow-xs">
                            <svg class="h-5 w-5 text-red-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-xs sm:text-sm font-bold text-red-800">Gagal Menyimpan Data</h3>
                                <ul class="list-disc pl-5 mt-1 text-xs sm:text-sm text-red-700 space-y-0.5">
                                    @foreach($errors->all() as $error)
                                        <li class="break-words">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Mobile Assignment Cards (< 1024px) -->
                    <div class="block lg:hidden space-y-3">
                        @forelse($assignments ?? [] as $assignment)
                            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs space-y-2.5">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-sm font-bold text-slate-900 leading-snug break-words">
                                            {{ $assignment->subject->name }}
                                        </h3>
                                        <div class="mt-1">
                                            <span class="inline-flex items-center text-xs font-semibold text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded-md border border-slate-200">
                                                {{ $assignment->classroom->name }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1 shrink-0">
                                        <button type="button" @click="$dispatch('open-modal', 'edit-assignment-{{ $assignment->id }}')" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit" aria-label="Edit penugasan">
                                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                        </button>
                                        <button type="button" @click="confirmDeleteAssignment('{{ $assignment->id }}', '{{ addslashes($assignment->subject->name) }}', '{{ addslashes($assignment->classroom->name) }}', '{{ addslashes(($assignment->academicYear?->year ?? '-') . ' • ' . ($assignment->semester?->name ?? '-')) }}')" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus" aria-label="Hapus penugasan">
                                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="pt-2 border-t border-slate-100 flex flex-col gap-0.5 text-xs">
                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Periode</span>
                                    <span class="text-slate-600 font-medium break-words">
                                        {{ $assignment->academicYear?->year ?? '-' }} &middot; {{ $assignment->semester?->name ?? '-' }}
                                    </span>
                                </div>

                                <!-- Hidden Delete Assignment Form -->
                                <form id="delete-assignment-form-{{ $assignment->id }}" action="{{ route('admin.teacher-assignments.destroy', $assignment) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="redirect_to" value="teacher">
                                </form>

                                <!-- Edit Assignment Modal (Shared) -->
                                <x-modal name="edit-assignment-{{ $assignment->id }}" maxWidth="xl">
                                    <form action="{{ route('admin.teacher-assignments.update', $assignment) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="redirect_to" value="teacher">
                                        <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                                        
                                        <div class="p-6 text-left whitespace-normal">
                                            <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-5">Edit Penugasan Mengajar</h2>
                                            
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
                                                        <x-select name="subject_id" required placeholder="-- Pilih Mapel --" :selected="$assignment->subject_id" :options="$subjects->map(fn($sub) => ['value' => $sub->id, 'label' => $sub->name])->toArray()" />
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kelas <span class="text-danger">*</span></label>
                                                        <x-select name="class_id" required placeholder="-- Pilih Kelas --" :selected="$assignment->class_id" :options="$classrooms->map(fn($cls) => ['value' => $cls->id, 'label' => $cls->name])->toArray()" />
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun Ajaran <span class="text-danger">*</span></label>
                                                        <x-select name="academic_year_id" required placeholder="-- Pilih Tahun Ajaran --" :selected="$assignment->academic_year_id" :options="$academicYears->map(fn($ay) => ['value' => $ay->id, 'label' => $ay->year])->toArray()" />
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Semester <span class="text-danger">*</span></label>
                                                        <x-semester-select name="semester_id" required class="block w-full py-2.5 px-3 text-sm border border-slate-300 focus:border-accent focus:ring focus:ring-accent/20 rounded-lg bg-white shadow-sm cursor-pointer" :selected="$assignment->semester_id" empty-label="-- Pilih Semester --" disabled-empty />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-8 flex justify-end gap-3 pt-5 border-t border-slate-100">
                                                <button type="button" x-on:click.prevent="$dispatch('close-modal', 'edit-assignment-{{ $assignment->id }}')" class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                                                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-primary rounded-lg-lg hover:bg-blue-900 transition-colors">Simpan Perubahan</button>
                                            </div>
                                        </div>
                                    </form>
                                </x-modal>
                            </div>
                        @empty
                            <div class="bg-white border border-slate-200 rounded-xl p-6 text-center space-y-3">
                                <p class="text-xs sm:text-sm text-slate-500">Belum ada penugasan mengajar untuk guru ini.</p>
                                <button type="button" x-on:click.prevent="$dispatch('open-modal', 'add-assignment')" class="px-4 py-2 text-xs font-semibold text-white bg-primary hover:bg-blue-900 rounded-lg transition-colors inline-flex items-center gap-1.5">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    Tambah Penugasan Pertama
                                </button>
                            </div>
                        @endforelse
                    </div>

                    <!-- Desktop Assignment Table Container (>= 1024px) -->
                    <div class="hidden lg:block overflow-x-auto border border-slate-200/80 rounded-xl">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200/80">
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas</th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Periode</th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($assignments ?? [] as $assignment)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="py-3 px-4">
                                            <p class="text-sm font-bold text-slate-900 break-words">{{ $assignment->subject->name }}</p>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="inline-flex items-center text-xs font-semibold text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded-lg border border-slate-200">
                                                {{ $assignment->classroom->name }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <p class="text-sm text-slate-600 break-words">{{ $assignment->academicYear?->year ?? '-' }} &middot; {{ $assignment->semester?->name ?? '-' }}</p>
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <button type="button" @click="$dispatch('open-modal', 'edit-assignment-{{ $assignment->id }}')" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit" aria-label="Edit penugasan">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                                </button>
                                                <button type="button" @click="confirmDeleteAssignment('{{ $assignment->id }}', '{{ addslashes($assignment->subject->name) }}', '{{ addslashes($assignment->classroom->name) }}', '{{ addslashes(($assignment->academicYear?->year ?? '-') . ' • ' . ($assignment->semester?->name ?? '-')) }}')" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus" aria-label="Hapus penugasan">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-sm text-slate-500">
                                            Belum ada penugasan mengajar untuk guru ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Delete Confirmation Modal (SinergiEdu Design System) -->
        <div 
            x-show="deleteModalOpen" 
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto" 
            role="dialog" 
            aria-modal="true"
            aria-labelledby="modal-title-delete-assignment"
            aria-describedby="modal-desc-delete-assignment"
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
                    <h3 class="text-base sm:text-lg font-semibold text-slate-900 tracking-tight break-words px-1" id="modal-title-delete-assignment">
                        Hapus Penugasan Mengajar?
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed break-words" id="modal-desc-delete-assignment">
                        Apakah Anda yakin ingin menghapus penugasan mapel &ldquo;<span x-text="assignmentSubjectToDelete" class="font-semibold text-slate-900 break-all"></span>&rdquo; kelas &ldquo;<span x-text="assignmentClassToDelete" class="font-semibold text-slate-900 break-all"></span>&rdquo;?
                    </p>
                </div>

                <!-- Warning Box -->
                <div class="mt-4 p-3 bg-amber-50/80 border border-amber-200/80 rounded-xl flex items-start gap-2.5 text-left w-full min-w-0 box-border">
                    <svg class="h-4 w-4 text-amber-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <div class="text-xs leading-relaxed text-amber-800 min-w-0 flex-1 break-words">
                        <span class="font-semibold block text-amber-900 mb-0.5">Penugasan Terhapus</span>
                        Data penugasan mengajar ini akan dihapus dari sistem.
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
                        @click="submitDeleteAssignment()"
                        class="flex-1 sm:flex-initial min-w-[90px] px-4 h-10 bg-red-600 hover:bg-red-700 text-white text-xs sm:text-sm font-semibold rounded-xl transition-colors shadow-2xs focus:outline-none focus:ring-2 focus:ring-red-500/30"
                    >
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Assignment Modal -->
    <x-modal name="add-assignment" maxWidth="xl">
        <form action="{{ route('admin.teacher-assignments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="redirect_to" value="teacher">
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

                </div>
            </div>
        </form>
    </x-modal>
</x-layouts.app>
