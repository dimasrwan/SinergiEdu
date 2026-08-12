<x-layouts.app>
    <x-slot:title>Detail Kelas - {{ $class->name }}</x-slot:title>

    <div class="w-full">
        <!-- Header & Breadcrumb -->
        <div class="mb-6 flex flex-col items-start gap-4">
            <a href="{{ route('admin.classes.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <div class="w-full flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Detail Kelas</h1>
                    <p class="mt-1 text-sm text-slate-500">Informasi rekapitulasi kelas, daftar murid, dan pengajar.</p>
                </div>
                <div class="flex gap-2 w-full sm:w-auto shrink-0">
                    <x-button variant="secondary" href="{{ route('admin.classes.edit', $class) }}" class="flex-1 sm:flex-none justify-center">Edit Data Kelas</x-button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Kolom Kiri: Profil Kelas & Pengajar -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Info Kelas -->
                <x-card padding="lg" class="overflow-hidden">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-primary font-bold text-2xl shadow-sm shrink-0">
                            {{ $class->grade_level }}
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">{{ $class->name }}</h2>
                            <p class="text-sm font-semibold text-slate-500 mt-1">Kelas Akademik</p>
                        </div>
                    </div>
                    
                    <div class="border-t border-slate-100 pt-5 space-y-5">
                        <div class="flex justify-between items-center">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tingkat</p>
                            <p class="text-sm font-semibold text-slate-900">Kelas {{ $class->grade_level }}</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tahun Ajaran</p>
                            <span class="inline-flex items-center text-xs font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-md">
                                {{ $class->academicYear->year ?? 'Belum Ditentukan' }}
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Wali Kelas</p>
                            <div class="flex items-center gap-2">
                                @if($class->homeroomTeacher)
                                    <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-[10px]">
                                        {{ strtoupper(substr($class->homeroomTeacher->user->name ?? 'W', 0, 1)) }}
                                    </div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $class->homeroomTeacher->user->name ?? 'Tanpa Nama' }}</p>
                                @else
                                    <span class="inline-flex text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">BELUM DITENTUKAN</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Siswa</p>
                            <span class="inline-flex items-center justify-center bg-blue-50 text-primary text-xs font-bold px-2.5 py-1 rounded-full border border-blue-100 min-w-[2.5rem]">
                                {{ $class->students->count() }}
                            </span>
                        </div>
                    </div>
                </x-card>

                <!-- Guru & Mata Pelajaran -->
                <x-card padding="lg">
                    <div class="flex items-center justify-between mb-5 border-b border-slate-100 pb-3">
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <svg class="h-5 w-5 text-accent" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                            Guru & Mata Pelajaran
                        </h3>
                        <span class="inline-flex text-[10px] font-bold uppercase tracking-wider text-slate-400 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">{{ $teacherSubjects->count() }} Mapel</span>
                    </div>

                    @if($teacherSubjects->count() > 0)
                        <div class="space-y-4">
                            @foreach($teacherSubjects as $ts)
                                <div class="flex flex-col sm:flex-row justify-between sm:items-center p-3 bg-slate-50 border border-slate-100 rounded-xl gap-2">
                                    <p class="text-sm font-bold text-slate-900">{{ $ts->subject_name }}</p>
                                    <div class="flex items-center gap-2">
                                        <svg class="h-3.5 w-3.5 text-slate-400 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
                                        <span class="inline-flex text-[11px] font-bold text-slate-700 bg-white px-2 py-1 rounded-md border border-slate-200">
                                            {{ $ts->teacher_name }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 bg-slate-50 border border-slate-100 rounded-xl">
                            <p class="text-sm font-bold text-slate-700">Belum ada data pengajar</p>
                            <p class="text-xs text-slate-500 mt-1">Belum ada pembagian mapel untuk kelas ini.</p>
                        </div>
                    @endif
                </x-card>
            </div>

            <!-- Kolom Kanan: Daftar Siswa -->
            <div class="lg:col-span-7 space-y-6">
                <x-card padding="none">
                    <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <svg class="h-5 w-5 text-accent" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                            Daftar Siswa Terdaftar
                        </h3>
                        <span class="inline-flex text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ $class->students->count() }} Orang</span>
                    </div>
                    
                    @if($class->students->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-white border-b border-slate-100">
                                        <th class="py-3 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-10">No</th>
                                        <th class="py-3 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama & NIS</th>
                                        <th class="py-3 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">J.Kelamin</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($class->students as $index => $student)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="py-3 px-5 text-sm font-medium text-slate-400">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="py-3 px-5">
                                                <p class="text-sm font-bold text-slate-900">{{ $student->user->name ?? '-' }}</p>
                                                <p class="text-xs text-slate-500 font-mono mt-0.5">NIS: {{ $student->nis ?? '-' }}</p>
                                            </td>
                                            <td class="py-3 px-5 text-right">
                                                @if($student->gender === 'L')
                                                    <span class="inline-flex text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded-md">Laki-Laki</span>
                                                @elseif($student->gender === 'P')
                                                    <span class="inline-flex text-[10px] font-bold text-pink-700 bg-pink-50 border border-pink-100 px-2 py-0.5 rounded-md">Perempuan</span>
                                                @else
                                                    <span class="text-xs text-slate-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-10 text-center">
                            <div class="mx-auto w-12 h-12 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center text-slate-300 mb-4">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                            </div>
                            <p class="text-sm font-bold text-slate-900">Kelas Masih Kosong</p>
                            <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Belum ada satupun siswa yang didaftarkan ke kelas ini pada tahun ajaran yang sedang aktif.</p>
                        </div>
                    @endif
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.app>
