<x-layouts.app>
    <x-slot:title>Edit Kelas - {{ $class->name }}</x-slot:title>

    <div class="w-full max-w-4xl">
        <!-- Header -->
        <div class="mb-6 flex flex-col items-start gap-4">
            <a href="{{ route('admin.classes.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Data Kelas</h1>
                <p class="mt-1 text-sm text-slate-500">Perbarui identitas kelas, tahun ajaran, dan penugasan wali kelas.</p>
            </div>
        </div>

        <x-card padding="none" class="overflow-hidden">
            <form action="{{ route('admin.classes.update', $class) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 md:p-8 space-y-8">
                    <!-- Validation Errors Global -->
                    @if($errors->any())
                        <div class="p-4 bg-red-50 border border-red-100 rounded-xl">
                            <div class="flex items-start gap-3">
                                <svg class="h-5 w-5 text-danger mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Identitas Kelas -->
                        <div class="space-y-6">
                            <div class="border-b border-slate-100 pb-2">
                                <h2 class="text-base font-bold text-slate-900">Identitas Kelas</h2>
                            </div>

                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Kelas <span class="text-danger">*</span></label>
                                <x-text-input id="name" name="name" type="text" :value="old('name', $class->name)" required class="w-full" />
                            </div>

                            <div>
                                <label for="grade_level" class="block text-sm font-semibold text-slate-700 mb-1.5">Tingkat Kelas <span class="text-danger">*</span></label>
                                <select id="grade_level" name="grade_level" required class="block w-full py-2.5 pl-3 pr-8 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent text-sm text-slate-700">
                                    <option value="10" {{ old('grade_level', $class->grade_level) == '10' ? 'selected' : '' }}>Tingkat 10</option>
                                    <option value="11" {{ old('grade_level', $class->grade_level) == '11' ? 'selected' : '' }}>Tingkat 11</option>
                                    <option value="12" {{ old('grade_level', $class->grade_level) == '12' ? 'selected' : '' }}>Tingkat 12</option>
                                </select>
                            </div>
                        </div>

                        <!-- Hubungan Akademik -->
                        <div class="space-y-6">
                            <div class="border-b border-slate-100 pb-2">
                                <h2 class="text-base font-bold text-slate-900">Hubungan Akademik</h2>
                            </div>

                            <div>
                                <label for="academic_year_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun Ajaran <span class="text-danger">*</span></label>
                                <select id="academic_year_id" name="academic_year_id" required class="block w-full py-2.5 pl-3 pr-8 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent text-sm text-slate-700">
                                    <option value="" disabled>Pilih tahun ajaran...</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->id }}" {{ old('academic_year_id', $class->academic_year_id) == $year->id ? 'selected' : '' }}>
                                            {{ $year->year }} {{ $year->is_active ? '(Aktif)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="homeroom_teacher_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Wali Kelas</label>
                                <select id="homeroom_teacher_id" name="homeroom_teacher_id" class="block w-full py-2.5 pl-3 pr-8 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent text-sm text-slate-700">
                                    <option value="">-- Belum Ditentukan --</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('homeroom_teacher_id', $class->homeroom_teacher_id) == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->user->name ?? 'Tanpa Nama' }} {{ $teacher->nip ? '('.$teacher->nip.')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 sm:gap-4">
                    <x-button variant="secondary" href="{{ route('admin.classes.index') }}" class="w-full sm:w-auto">Batal</x-button>
                    <x-button variant="primary" type="submit" class="w-full sm:w-auto justify-center">Perbarui Data Kelas</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
