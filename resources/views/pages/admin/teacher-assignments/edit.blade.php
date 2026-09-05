<x-layouts.app>
    <x-slot:title>Edit Penugasan Guru</x-slot:title>

    <div class="w-full space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                @if(request('redirect_to') === 'teacher')
                    <a href="{{ route('admin.teachers.show', $teacherAssignment->teacher_id) }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition mb-1">
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
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Penugasan</h1>
                <p class="text-sm text-slate-500">Perbarui data kelas, mata pelajaran, atau konteks penugasan mengajar.</p>
            </div>
        </div>

        <x-card padding="none" class="overflow-hidden shadow-xs border border-slate-200">
            <form action="{{ route('admin.teacher-assignments.update', $teacherAssignment) }}" method="POST">
                @csrf
                @method('PUT')
                @if(request('redirect_to'))
                    <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
                @endif
                @if(request('redirect_to') === 'teacher')
                    <input type="hidden" name="teacher_id" value="{{ $teacherAssignment->teacher_id }}">
                @endif

                <div class="p-6 md:p-8 space-y-6">
                    <!-- Validation Errors Global -->
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

                    <div class="space-y-6 max-w-3xl">
                        <!-- Guru -->
                        <div class="space-y-2">
                            <label for="teacher_id" class="block text-sm font-semibold text-slate-800">Guru Pengampu <span class="text-red-500">*</span></label>
                            @if(request('redirect_to') === 'teacher')
                                <div class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm">
                                    <div class="h-9 w-9 rounded-full bg-blue-100 border border-blue-200 flex items-center justify-center text-xs font-bold text-blue-700 shrink-0">
                                        {{ strtoupper(substr($teacherAssignment->teacher->user->name ?? 'G', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-900">{{ $teacherAssignment->teacher->user->name }}</div>
                                        <div class="text-xs text-slate-500 font-mono">NIP: {{ $teacherAssignment->teacher->nip ?? '-' }}</div>
                                    </div>
                                </div>
                                <input type="hidden" name="teacher_id" value="{{ $teacherAssignment->teacher_id }}">
                            @else
                                <select id="teacher_id" name="teacher_id" class="block w-full pl-3.5 pr-10 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs" required>
                                    <option value="" disabled>Pilih guru...</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id', $teacherAssignment->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->user->name }} (NIP. {{ $teacher->nip ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kelas -->
                            <div class="space-y-2">
                                <label for="class_id" class="block text-sm font-semibold text-slate-800">Kelas <span class="text-red-500">*</span></label>
                                <select id="class_id" name="class_id" class="block w-full pl-3.5 pr-10 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs" required>
                                    <option value="" disabled>Pilih kelas...</option>
                                    @foreach($classrooms as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id', $teacherAssignment->class_id) == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }} (Tingkat {{ $class->level }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Mata Pelajaran -->
                            <div class="space-y-2">
                                <label for="subject_id" class="block text-sm font-semibold text-slate-800">Mata Pelajaran <span class="text-red-500">*</span></label>
                                <select id="subject_id" name="subject_id" class="block w-full pl-3.5 pr-10 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs" required>
                                    <option value="" disabled>Pilih mata pelajaran...</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id', $teacherAssignment->subject_id) == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }} (Kode: {{ $subject->code ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                            <!-- Tahun Ajaran -->
                            <div class="space-y-2">
                                <label for="academic_year_id" class="block text-sm font-semibold text-slate-800">Tahun Ajaran <span class="text-red-500">*</span></label>
                                <select id="academic_year_id" name="academic_year_id" class="block w-full pl-3.5 pr-10 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs" required>
                                    <option value="" disabled>Pilih tahun ajaran...</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->id }}" {{ old('academic_year_id', $teacherAssignment->academic_year_id) == $year->id ? 'selected' : '' }}>
                                            {{ $year->year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Semester -->
                            <div class="space-y-2">
                                <label for="semester_id" class="block text-sm font-semibold text-slate-800">Semester <span class="text-red-500">*</span></label>
                                <select id="semester_id" name="semester_id" class="block w-full pl-3.5 pr-10 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs" required>
                                    <option value="" disabled>Pilih semester...</option>
                                    @foreach($semesters as $semester)
                                        <option value="{{ $semester->id }}" {{ old('semester_id', $teacherAssignment->semester_id) == $semester->id ? 'selected' : '' }}>
                                            {{ $semester->name }} (TA {{ $semester->academicYear->year ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/60 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                    @if(request('redirect_to') === 'teacher')
                        <x-button variant="secondary" href="{{ route('admin.teachers.show', $teacherAssignment->teacher_id) }}" class="w-full sm:w-auto">Batal</x-button>
                    @else
                        <x-button variant="secondary" href="{{ route('admin.teacher-assignments.index') }}" class="w-full sm:w-auto">Batal</x-button>
                    @endif
                    <x-button variant="primary" type="submit" class="w-full sm:w-auto justify-center">
                        Simpan Perubahan
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
