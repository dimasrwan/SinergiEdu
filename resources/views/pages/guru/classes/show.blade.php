<x-layouts.app>
    <x-slot:title>Detail Kelas - {{ $class->classroom->name }}</x-slot:title>

    <div class="w-full space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('guru.classes.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ $class->classroom->name }}</h1>
                    <p class="text-slate-500 text-sm mt-1">{{ $class->subject->name }} • Tingkat {{ $class->classroom->grade_level }}</p>
                </div>
            </div>
            
            <div class="bg-blue-50 text-primary px-4 py-2 rounded-xl text-sm font-semibold border border-blue-100 flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                Tahun Ajaran {{ $class->academicYear->year }} • Semester {{ $class->semester->name }}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Info Penugasan -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Informasi Penugasan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                        <div>
                            <p class="text-xs text-slate-500 mb-1 uppercase tracking-wider font-semibold">Mata Pelajaran</p>
                            <p class="text-slate-900 font-medium">{{ $class->subject->name }} ({{ $class->subject->code }})</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1 uppercase tracking-wider font-semibold">Nama Kelas</p>
                            <p class="text-slate-900 font-medium">{{ $class->classroom->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1 uppercase tracking-wider font-semibold">Tingkat Pendidikan</p>
                            <p class="text-slate-900 font-medium">{{ $class->classroom->education_level }} - Kelas {{ $class->classroom->grade_level }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1 uppercase tracking-wider font-semibold">Jumlah Siswa</p>
                            <p class="text-slate-900 font-medium">{{ $class->classroom->students->count() ?? 0 }} Siswa terdaftar</p>
                        </div>
                    </div>
                </div>

                <!-- Daftar Siswa Terdaftar -->
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                    <div class="p-6 pb-4 flex items-center justify-between gap-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
                            </svg>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900">Daftar Siswa Terdaftar</h3>
                        </div>
                        <span class="text-xs sm:text-sm font-bold uppercase tracking-wider text-slate-400">
                            {{ $class->classroom->students->count() }} ORANG
                        </span>
                    </div>

                    @if($class->classroom->students->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-100">
                                        <th class="py-3.5 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider w-16">NO</th>
                                        <th class="py-3.5 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider">NAMA & NIS</th>
                                        <th class="py-3.5 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">J.KELAMIN</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($class->classroom->students as $index => $student)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="py-4 px-6 text-sm font-medium text-slate-400">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="py-4 px-6">
                                                <p class="text-sm font-bold text-slate-900">{{ $student->user->name ?? '-' }}</p>
                                                <p class="text-xs text-slate-500 font-mono mt-0.5">NIS: {{ $student->nis ?? '-' }}</p>
                                            </td>
                                            <td class="py-4 px-6 text-right">
                                                @if(strtoupper($student->gender ?? '') === 'L' || strtolower($student->gender ?? '') === 'laki-laki')
                                                    <span class="inline-flex text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-100 px-3 py-1 rounded-full">Laki-Laki</span>
                                                @elseif(strtoupper($student->gender ?? '') === 'P' || strtolower($student->gender ?? '') === 'perempuan')
                                                    <span class="inline-flex text-xs font-semibold text-pink-700 bg-pink-50 border border-pink-100 px-3 py-1 rounded-full">Perempuan</span>
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
                        <div class="p-8 text-center">
                            <div class="mx-auto w-12 h-12 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center text-slate-300 mb-3">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-800">Belum ada siswa yang terdaftar di kelas ini.</p>
                            <p class="text-xs text-slate-400 mt-1">Siswa akan muncul di sini setelah didaftarkan oleh Administrator.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-primary rounded-2xl border border-primary p-6 text-white text-center">
                    <h3 class="font-bold text-lg mb-2">Area Pembelajaran</h3>
                    <p class="text-blue-100 text-sm mb-6">Kelola materi, tugas, dan nilai untuk kelas ini.</p>
                    
                    <div class="space-y-3">
                        <!-- Navigation Buttons (Currently redirect to placeholders or main index based on existing implementation) -->
                        <a href="{{ route('guru.materials.index') }}" class="block w-full py-2.5 bg-white/10 hover:bg-white/20 rounded-xl text-sm font-semibold transition border border-white/20">
                            Materi Pelajaran
                        </a>
                        <a href="{{ route('guru.assignments.index') }}" class="block w-full py-2.5 bg-white/10 hover:bg-white/20 rounded-xl text-sm font-semibold transition border border-white/20">
                            Tugas & Kuis
                        </a>
                        <a href="{{ route('guru.grades.index') }}" class="block w-full py-2.5 bg-white/10 hover:bg-white/20 rounded-xl text-sm font-semibold transition border border-white/20">
                            Penilaian
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</x-layouts.app>
