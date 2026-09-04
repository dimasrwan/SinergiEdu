<x-layouts.app>
    <x-slot:title>Edit Penempatan / Pindah Kelas</x-slot:title>

    <div class="w-full max-w-2xl">
        <!-- Header -->
        <div class="mb-6 flex flex-col items-start gap-4">
            @if(request('redirect_to') === 'student')
                <a href="{{ route('admin.students.show', $studentPlacement->student_id) }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali ke Detail Siswa
                </a>
            @else
                <a href="{{ route('admin.student-placements.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali ke Daftar
                </a>
            @endif
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit / Pindah Kelas</h1>
                <p class="mt-1 text-sm text-slate-500">Perbarui kelas aktif siswa pada tahun ajaran ini tanpa membuat rekaman ganda.</p>
            </div>
        </div>

        <x-card padding="none" class="overflow-hidden">
            <form action="{{ route('admin.student-placements.update', $studentPlacement) }}" method="POST">
                @csrf
                @method('PUT')
                @if(request('redirect_to'))
                    <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
                @endif
                @if(request('redirect_to') === 'student')
                    <input type="hidden" name="student_id" value="{{ $studentPlacement->student_id }}">
                @endif

                <div class="p-6 md:p-8 space-y-6">
                    <!-- Validation Errors -->
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
                        <!-- Siswa -->
                        <div>
                            <label for="student_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Siswa <span class="text-danger">*</span></label>
                            <select id="student_id" name="student_id" class="block w-full pl-3 pr-10 py-2 border border-slate-300 rounded-lg bg-slate-50 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent sm:text-sm" required>
                                <option value="{{ $studentPlacement->student_id }}" selected>
                                    {{ $studentPlacement->student->user->name }} (NIS: {{ $studentPlacement->student->nis ?? '-' }})
                                </option>
                            </select>
                            <p class="mt-1 text-xs text-slate-500">Siswa tidak dapat diubah saat mengedit penempatan.</p>
                        </div>

                        <!-- Kelas -->
                        <div>
                            <label for="class_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Pindahkan ke Kelas <span class="text-danger">*</span></label>
                            <select id="class_id" name="class_id" class="block w-full pl-3 pr-10 py-2 border border-slate-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent sm:text-sm" required>
                                <option value="" disabled>Pilih kelas...</option>
                                @foreach($classrooms as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id', $studentPlacement->class_id) == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }} (Tingkat {{ $class->level }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tahun Ajaran -->
                        <div>
                            <label for="academic_year_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun Ajaran <span class="text-danger">*</span></label>
                            <select id="academic_year_id" name="academic_year_id" class="block w-full pl-3 pr-10 py-2 border border-slate-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent sm:text-sm" required>
                                <option value="" disabled>Pilih tahun ajaran...</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ old('academic_year_id', $studentPlacement->academic_year_id) == $year->id ? 'selected' : '' }}>
                                        {{ $year->year }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-slate-500">Peringatan: Mengubah tahun ajaran dapat berkonflik jika riwayat tahun tersebut sudah ada.</p>
                        </div>

                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 sm:gap-4">
                    @if(request('redirect_to') === 'student')
                        <x-button variant="secondary" href="{{ route('admin.students.show', $studentPlacement->student_id) }}" class="w-full sm:w-auto">Batal</x-button>
                    @else
                        <x-button variant="secondary" href="{{ route('admin.student-placements.index') }}" class="w-full sm:w-auto">Batal</x-button>
                    @endif
                    <x-button variant="primary" type="submit" class="w-full sm:w-auto justify-center">
                        Simpan Perubahan
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
