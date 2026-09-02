<x-layouts.app>
    <x-slot:title>Tambah Penugasan Guru</x-slot:title>

    <div class="w-full max-w-2xl">
        <!-- Header -->
        <div class="mb-6 flex flex-col items-start gap-4">
            @if(request('redirect_to') === 'teacher' && request('teacher_id'))
                <a href="{{ route('admin.teachers.show', request('teacher_id')) }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali ke Detail Guru
                </a>
            @else
                <a href="{{ route('admin.teacher-assignments.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali ke Daftar
                </a>
            @endif
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Tambah Penugasan</h1>
                <p class="mt-1 text-sm text-slate-500">Isi kelima parameter wajib di bawah ini untuk menentukan penugasan mengajar.</p>
            </div>
        </div>

        <x-card padding="none" class="overflow-hidden">
            <form action="{{ route('admin.teacher-assignments.store') }}" method="POST">
                @csrf
                @if(request('redirect_to'))
                    <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
                @endif
                @if(request('teacher_id'))
                    <input type="hidden" name="teacher_id" value="{{ request('teacher_id') }}">
                @endif

                <div class="p-6 md:p-8 space-y-6">
                    <!-- Validation Errors Global -->
                    @if($errors->any())
                        <div class="p-4 bg-red-50 border border-red-100 rounded-xl">
                            <div class="flex items-start gap-3">
                                <svg class="h-5 w-5 text-danger mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <div>
                                    <h3 class="text-sm font-bold text-red-800">Terdapat Kesalahan</h3>
                                    <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-6">
                        <!-- Guru -->
                        <div>
                            <label for="teacher_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Guru <span class="text-danger">*</span></label>
                            @if(request('teacher_id'))
                                @php $selectedTeacher = $teachers->firstWhere('id', request('teacher_id')); @endphp
                                <select id="teacher_id" class="block w-full pl-3 pr-10 py-2 border border-slate-300 rounded-xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent sm:text-sm" disabled>
                                    <option value="{{ $selectedTeacher->id }}" selected>
                                        {{ $selectedTeacher->user->name }} (NIP. {{ $selectedTeacher->nip ?? '-' }})
                                    </option>
                                </select>
                            @else
                                @if($teachers->isEmpty())
                                    <p class="text-sm text-red-600 bg-red-50 p-2 rounded-lg border border-red-100">Belum ada guru yang dapat ditugaskan.</p>
                                @else
                                    <select id="teacher_id" name="teacher_id" class="block w-full pl-3 pr-10 py-2 border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent sm:text-sm" required>
                                        <option value="" disabled {{ old('teacher_id') ? '' : 'selected' }}>Pilih guru...</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->user->name }} (NIP. {{ $teacher->nip ?? '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            @endif
                        </div>

                        <!-- Mata Pelajaran -->
                        <div>
                            <label for="subject_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Mata Pelajaran <span class="text-danger">*</span></label>
                            @if($subjects->isEmpty())
                                <p class="text-sm text-red-600 bg-red-50 p-2 rounded-lg border border-red-100">Belum ada mata pelajaran tersedia.</p>
                            @else
                                <select id="subject_id" name="subject_id" class="block w-full pl-3 pr-10 py-2 border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent sm:text-sm" required>
                                    <option value="" disabled {{ old('subject_id') ? '' : 'selected' }}>Pilih mata pelajaran...</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <!-- Kelas -->
                        <div>
                            <label for="class_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Kelas <span class="text-danger">*</span></label>
                            @if($classrooms->isEmpty())
                                <p class="text-sm text-red-600 bg-red-50 p-2 rounded-lg border border-red-100">Belum ada kelas tersedia.</p>
                            @else
                                <select id="class_id" name="class_id" class="block w-full pl-3 pr-10 py-2 border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent sm:text-sm" required>
                                    <option value="" disabled {{ old('class_id') ? '' : 'selected' }}>Pilih kelas...</option>
                                    @foreach($classrooms as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Tahun Ajaran -->
                            <div>
                                <label for="academic_year_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun Ajaran <span class="text-danger">*</span></label>
                                @if($academicYears->isEmpty())
                                    <p class="text-sm text-red-600 bg-red-50 p-2 rounded-lg border border-red-100">Belum ada tahun ajaran.</p>
                                @else
                                    <select id="academic_year_id" name="academic_year_id" class="block w-full pl-3 pr-10 py-2 border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent sm:text-sm" required>
                                        <option value="" disabled {{ old('academic_year_id') ? '' : 'selected' }}>Pilih tahun ajaran...</option>
                                        @foreach($academicYears as $year)
                                            <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                                                {{ $year->year }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            <!-- Semester -->
                            <div>
                                <label for="semester_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Semester <span class="text-danger">*</span></label>
                                <x-semester-select id="semester_id" name="semester_id" class="block w-full pl-3 pr-10 py-2 border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent sm:text-sm" required :selected="old('semester_id')" empty-label="Pilih semester..." disabled-empty />
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 sm:gap-4">
                    @if(request('redirect_to') === 'teacher' && request('teacher_id'))
                        <x-button variant="secondary" href="{{ route('admin.teachers.show', request('teacher_id')) }}" class="w-full sm:w-auto">Batal</x-button>
                    @else
                        <x-button variant="secondary" href="{{ route('admin.teacher-assignments.index') }}" class="w-full sm:w-auto">Batal</x-button>
                    @endif
                    <x-button variant="primary" type="submit" class="w-full sm:w-auto justify-center" :disabled="$teachers->isEmpty() || $subjects->isEmpty() || $classrooms->isEmpty() || $academicYears->isEmpty() || $semesters->isEmpty()">
                        Simpan Penugasan
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
