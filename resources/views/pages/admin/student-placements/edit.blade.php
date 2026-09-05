<x-layouts.app>
    <x-slot:title>Edit Penempatan / Pindah Kelas</x-slot:title>

    <div class="w-full space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                @if(request('redirect_to') === 'student')
                    <a href="{{ route('admin.students.show', $studentPlacement->student_id) }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition mb-1">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Kembali ke Detail Siswa
                    </a>
                @else
                    <a href="{{ route('admin.student-placements.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition mb-1">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Kembali ke Daftar Penempatan
                    </a>
                @endif
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit / Pindah Kelas</h1>
                <p class="text-sm text-slate-500">Perbarui kelas aktif siswa pada tahun ajaran ini tanpa membuat rekaman ganda.</p>
            </div>
        </div>

        <x-card padding="none" class="overflow-hidden shadow-xs border border-slate-200">
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

                    <div class="space-y-6 max-w-2xl">
                        <!-- Info Siswa -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-800">Siswa</label>
                            <div class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm">
                                <div class="h-9 w-9 rounded-full bg-blue-100 border border-blue-200 flex items-center justify-center text-xs font-bold text-blue-700 shrink-0">
                                    {{ strtoupper(substr($studentPlacement->student->user->name ?? 'S', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-900">{{ $studentPlacement->student->user->name }}</div>
                                    <div class="text-xs text-slate-500 font-mono">NIS: {{ $studentPlacement->student->nis ?? '-' }}</div>
                                </div>
                            </div>
                            <input type="hidden" name="student_id" value="{{ $studentPlacement->student_id }}">
                        </div>

                        <!-- Kelas Target -->
                        <div class="space-y-2">
                            <label for="class_id" class="block text-sm font-semibold text-slate-800">Pindahkan ke Kelas <span class="text-red-500">*</span></label>
                            <x-select id="class_id" name="class_id" placeholder="Pilih kelas..." required :selected="old('class_id', $studentPlacement->class_id)" :options="$classrooms->map(fn($c) => ['value' => $c->id, 'label' => $c->name . ' (Tingkat ' . $c->level . ')'])->toArray()" />
                        </div>

                        <!-- Tahun Ajaran -->
                        <div class="space-y-2">
                            <label for="academic_year_id" class="block text-sm font-semibold text-slate-800">Tahun Ajaran <span class="text-red-500">*</span></label>
                            <x-select id="academic_year_id" name="academic_year_id" placeholder="Pilih tahun ajaran..." required :selected="old('academic_year_id', $studentPlacement->academic_year_id)" :options="$academicYears->map(fn($y) => ['value' => $y->id, 'label' => $y->year])->toArray()" />
                            <p class="text-xs text-slate-500">Peringatan: Mengubah tahun ajaran dapat berkonflik jika riwayat tahun tersebut sudah ada.</p>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/60 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
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
