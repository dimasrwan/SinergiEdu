<x-layouts.app>
    <x-slot:title>Tambah Penugasan Guru</x-slot:title>

    <div class="w-full space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                @if(request('redirect_to') === 'teacher' && request('teacher_id'))
                    <a href="{{ route('admin.teachers.show', request('teacher_id')) }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition mb-1">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Kembali ke Detail Guru
                    </a>
                @else
                    <a href="{{ route('admin.teacher-assignments.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition mb-1">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Kembali ke Daftar Penugasan
                    </a>
                @endif
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Tambah Penugasan Guru</h1>
                <p class="text-sm text-slate-500">Tentukan guru pengampu beserta kelas dan mata pelajaran pada semester aktif.</p>
            </div>

            <!-- Active Context Pills -->
            <div class="flex flex-wrap items-center gap-2">
                <div class="flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 rounded-xl shadow-xs text-xs">
                    <span class="text-slate-400 font-medium">TA:</span>
                    <span class="font-bold text-slate-800">{{ $activeAcademicYear->year }}</span>
                </div>
                <div class="flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 rounded-xl shadow-xs text-xs">
                    <span class="text-slate-400 font-medium">Semester:</span>
                    <span class="font-bold text-slate-800">{{ $activeSemester->name }}</span>
                </div>
            </div>
        </div>

        <x-card padding="none" class="overflow-hidden shadow-xs border border-slate-200" x-data="{
            assignments: [{ id: Date.now(), class_id: '', subject_id: '' }],
            addAssignment() {
                this.assignments.push({ id: Date.now(), class_id: '', subject_id: '' });
            },
            removeAssignment(id) {
                if (this.assignments.length > 1) {
                    this.assignments = this.assignments.filter(a => a.id !== id);
                }
            }
        }">
            <form action="{{ route('admin.teacher-assignments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="academic_year_id" value="{{ $activeAcademicYear->id }}">
                <input type="hidden" name="semester_id" value="{{ $activeSemester->id }}">

                @if(request('redirect_to'))
                    <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
                @endif
                @if(request('teacher_id'))
                    <input type="hidden" name="teacher_id" value="{{ request('teacher_id') }}">
                @endif

                <div class="p-6 md:p-8 space-y-8">
                    <!-- Validation Errors -->
                    @if($errors->any())
                        <div class="p-4 bg-red-50/80 border border-red-200/80 rounded-xl text-sm text-red-700 flex items-start gap-3">
                            <svg class="h-5 w-5 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <h4 class="font-semibold text-red-900 mb-1">Terjadi kesalahan pada input:</h4>
                                <ul class="list-disc list-inside space-y-0.5 text-xs">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Section 1: Parameter Utama -->
                    <div class="p-5 bg-slate-50/70 border border-slate-200/80 rounded-2xl space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Guru Selection -->
                            <div class="md:col-span-3 space-y-2">
                                <label for="teacher_id" class="block text-sm font-semibold text-slate-800">
                                    Guru Pengampu <span class="text-red-500">*</span>
                                </label>
                                @if(request('teacher_id'))
                                    @php $selectedTeacher = $teachers->firstWhere('id', request('teacher_id')); @endphp
                                    <div class="flex items-center gap-3 p-3 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm shadow-2xs">
                                        <div class="h-9 w-9 rounded-full bg-blue-100 border border-blue-200 flex items-center justify-center text-xs font-bold text-blue-700 shrink-0">
                                            {{ strtoupper(substr($selectedTeacher->user->name ?? 'G', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900">{{ $selectedTeacher->user->name }}</div>
                                            <div class="text-xs text-slate-500 font-mono">NIP: {{ $selectedTeacher->nip ?? '-' }}</div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="teacher_id" value="{{ $selectedTeacher->id }}">
                                @else
                                    @if($teachers->isEmpty())
                                        <div class="p-3.5 bg-amber-50 border border-amber-200/80 rounded-xl flex items-start gap-3">
                                            <svg class="h-5 w-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <p class="text-xs text-amber-800 font-medium">Belum ada data guru yang tersedia di sekolah ini.</p>
                                        </div>
                                        <x-searchable-select id="teacher_id" name="teacher_id" placeholder="-- Pilih Guru --" required :options="$teachers->map(fn($t) => ['value' => $t->id, 'label' => $t->user->name . ' (NIP. ' . ($t->nip ?? '-') . ')'])->toArray()" />
                                    @endif
                                @endif
                            </div>

                            <!-- Read-only Academic Year -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-800">Tahun Ajaran Aktif</label>
                                <div class="flex items-center gap-4 pl-5 pr-3.5 py-2.5 bg-slate-100/70 border border-slate-300 rounded-xl text-slate-800 text-sm font-semibold shadow-2xs">
                                    <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                    <span>{{ $activeAcademicYear->year }}</span>
                                </div>
                            </div>

                            <!-- Read-only Semester -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-800">Semester Aktif</label>
                                <div class="flex items-center gap-4 pl-5 pr-3.5 py-2.5 bg-slate-100/70 border border-slate-300 rounded-xl text-slate-800 text-sm font-semibold shadow-2xs">
                                    <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $activeSemester->name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Repeater Assignment Items -->
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Rincian Penugasan Mengajar</h3>
                                <p class="text-xs text-slate-500">Tambahkan satu atau lebih pasangan kelas dan mata pelajaran yang akan diampu.</p>
                            </div>
                            <button type="button" @click="addAssignment()" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200/80 rounded-xl hover:bg-blue-100 transition-colors shadow-2xs">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                Tambah Penugasan
                            </button>
                        </div>
                        
                        <div class="space-y-3">
                            <template x-for="(assignment, index) in assignments" :key="assignment.id">
                                <div class="flex flex-col md:flex-row items-stretch md:items-center gap-4 p-4 bg-white border border-slate-200 rounded-2xl shadow-2xs hover:border-slate-300 transition-all">
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kelas <span class="text-red-500">*</span></label>
                                        <select x-model="assignment.class_id" :name="`assignments[${index}][class_id]`" class="w-full flex items-center justify-between bg-white border border-slate-200 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 rounded-xl px-4 py-2.5 text-[14px] font-medium text-slate-800 shadow-2xs transition-all cursor-pointer" required>
                                            <option value="" disabled selected>-- Pilih Kelas --</option>
                                            @foreach($classrooms as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }} (Tingkat {{ $class->level }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Mata Pelajaran <span class="text-red-500">*</span></label>
                                        <select x-model="assignment.subject_id" :name="`assignments[${index}][subject_id]`" class="w-full flex items-center justify-between bg-white border border-slate-200 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 rounded-xl px-4 py-2.5 text-[14px] font-medium text-slate-800 shadow-2xs transition-all cursor-pointer" required>
                                            <option value="" disabled selected>-- Pilih Mata Pelajaran --</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}">{{ $subject->name }} (Kode: {{ $subject->code ?? '-' }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex items-center justify-end md:self-end pt-2 md:pt-0">
                                        <button type="button" @click="removeAssignment(assignment.id)" x-show="assignments.length > 1" class="p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors" title="Hapus baris ini">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/60 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                    @if(request('redirect_to') === 'teacher' && request('teacher_id'))
                        <x-button variant="secondary" href="{{ route('admin.teachers.show', request('teacher_id')) }}" class="w-full sm:w-auto">Batal</x-button>
                    @else
                        <x-button variant="secondary" href="{{ route('admin.teacher-assignments.index') }}" class="w-full sm:w-auto">Batal</x-button>
                    @endif
                    <x-button variant="primary" type="submit" class="w-full sm:w-auto justify-center" :disabled="$teachers->isEmpty() || $subjects->isEmpty() || $classrooms->isEmpty()">
                        Simpan Penugasan
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
