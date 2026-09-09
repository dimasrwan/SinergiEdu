<x-layouts.app>
    <x-slot:title>Dashboard Kepala Sekolah</x-slot:title>

    <div class="w-full space-y-8">

        <!-- Banner Ikhtisar -->
        <div class="bg-primary rounded-2xl p-8 text-white shadow-xl shadow-primary/20 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight mb-2">Dashboard Kepala Sekolah</h1>
                    <p class="text-blue-200 text-sm max-w-xl">
                        Selamat datang, {{ Auth::user()->name }}. Pantau kinerja akademik dan tenaga pendidik di sekolah Anda.
                    </p>
                </div>
                <div class="flex flex-wrap gap-4 shrink-0">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 min-w-[140px]">
                        <p class="text-xs text-blue-200 font-semibold uppercase tracking-wider mb-1">Tahun Ajaran</p>
                        <h3 class="text-xl font-bold">{{ $activeYear?->name ?? 'Belum Aktif' }}</h3>
                        <p class="text-xs text-blue-200 mt-1">{{ $activeSemester?->name ?? 'Belum ada semester aktif' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Utama -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <x-card class="min-w-0">
                <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                    <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 truncate">Total Guru</p>
                        <p class="text-xl sm:text-2xl font-bold text-slate-900 truncate">{{ $totalTeachers }}</p>
                    </div>
                </div>
            </x-card>

            <x-card class="min-w-0">
                <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                    <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 truncate">Total Siswa</p>
                        <p class="text-xl sm:text-2xl font-bold text-slate-900 truncate">{{ $totalStudents }}</p>
                    </div>
                </div>
            </x-card>

            <x-card class="min-w-0">
                <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                    <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 truncate">Total Kelas</p>
                        <p class="text-xl sm:text-2xl font-bold text-slate-900 truncate">{{ $totalClasses }}</p>
                    </div>
                </div>
            </x-card>

            <x-card class="min-w-0">
                <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                    <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 truncate">Feedback Saya</p>
                        <p class="text-xl sm:text-2xl font-bold text-slate-900 truncate">{{ $feedbacksCount }}</p>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            <!-- Kolom Kiri -->
            <div class="lg:col-span-2 space-y-6 sm:space-y-8 min-w-0">

                <!-- Rangking Kelas -->
                <section class="min-w-0">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base sm:text-lg font-bold text-slate-900">Rangking Kelas</h2>
                        <a href="{{ route('kepala-sekolah.academic.perkembangan') }}" class="text-xs sm:text-sm font-medium text-primary hover:text-primary/80 min-h-[44px] inline-flex items-center">Lihat Semua</a>
                    </div>
                    <x-card padding="none">
                        {{-- Mobile list --}}
                        <div class="block lg:hidden divide-y divide-slate-100">
                            @forelse($classRankings as $ranking)
                                <div class="p-4 flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="inline-flex items-center justify-center h-7 w-7 rounded-lg {{ $loop->iteration <= 3 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }} text-xs font-bold shrink-0">
                                            #{{ $loop->iteration }}
                                        </span>
                                        <div class="min-w-0">
                                            <h3 class="font-bold text-slate-900 text-sm truncate">{{ $ranking['name'] }}</h3>
                                            <p class="text-xs text-slate-500">Tingkat: {{ $ranking['grade_level'] }}</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-extrabold text-primary shrink-0">Rerata: {{ $ranking['avg'] }}</span>
                                </div>
                            @empty
                                <div class="p-6 text-center text-sm text-slate-500">Belum ada data nilai untuk ditampilkan.</div>
                            @endforelse
                        </div>

                        {{-- Desktop table --}}
                        <div class="hidden lg:block">
                            <table class="w-full text-left border-collapse text-sm">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200">
                                        <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Peringkat</th>
                                        <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas</th>
                                        <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Tingkat</th>
                                        <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($classRankings as $ranking)
                                        <tr class="hover:bg-slate-50">
                                            <td class="p-6">
                                                <span class="inline-flex items-center justify-center h-6 w-6 rounded-lg {{ $loop->iteration <= 3 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }} text-xs font-bold">{{ $loop->iteration }}</span>
                                            </td>
                                            <td class="p-6 font-semibold text-slate-900">{{ $ranking['name'] }}</td>
                                            <td class="p-6 text-slate-600">{{ $ranking['grade_level'] }}</td>
                                            <td class="p-6 font-bold text-slate-900">{{ $ranking['avg'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-8 text-center text-slate-500">Belum ada data nilai untuk ditampilkan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </x-card>
                </section>

                <!-- Analitik Mata Pelajaran -->
                <section class="min-w-0">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base sm:text-lg font-bold text-slate-900">Analitik Mata Pelajaran</h2>
                        <a href="{{ route('kepala-sekolah.academic.subjects') }}" class="text-xs sm:text-sm font-medium text-primary hover:text-primary/80 min-h-[44px] inline-flex items-center">Buka Analitik Penuh</a>
                    </div>
                    <x-card padding="none">
                        {{-- Mobile list --}}
                        <div class="block lg:hidden divide-y divide-slate-100">
                            @forelse($subjectAnalysis as $subject)
                                <div class="p-4 space-y-2">
                                    <div class="flex items-start justify-between gap-3">
                                        <h3 class="font-bold text-slate-900 text-sm truncate">{{ $subject['name'] }}</h3>
                                        <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 shrink-0">Rerata: {{ $subject['avg'] }}</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-xs pt-1">
                                        <div class="bg-slate-50 p-2 rounded-lg">
                                            <span class="text-slate-400 block text-[10px]">Karakter</span>
                                            <span class="font-semibold text-slate-700">{{ $subject['avg_character'] }}</span>
                                        </div>
                                        <div class="bg-slate-50 p-2 rounded-lg">
                                            <span class="text-slate-400 block text-[10px]">Hafalan</span>
                                            <span class="font-semibold text-slate-700">{{ $subject['avg_memorization'] }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between gap-2 pt-1">
                                        <span class="text-xs text-slate-500 font-medium">Ketuntasan:</span>
                                        <div class="flex items-center gap-2">
                                            <div class="w-20 bg-slate-200 rounded-full h-1.5">
                                                <div class="{{ $subject['pass_rate'] >= 75 ? 'bg-emerald-500' : 'bg-amber-500' }} h-1.5 rounded-full" style="width: {{ min($subject['pass_rate'], 100) }}%"></div>
                                            </div>
                                            <span class="text-xs font-bold text-slate-700">{{ $subject['pass_rate'] }}%</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-sm text-slate-500">Belum ada data mata pelajaran.</div>
                            @endforelse
                        </div>

                        {{-- Desktop table --}}
                        <div class="hidden lg:block">
                            <table class="w-full text-left border-collapse text-sm">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200">
                                        <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
                                        <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata</th>
                                        <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Karakter</th>
                                        <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Hafalan</th>
                                        <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Ketuntasan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($subjectAnalysis as $subject)
                                        <tr class="hover:bg-slate-50">
                                            <td class="p-6 font-semibold text-slate-900">{{ $subject['name'] }}</td>
                                            <td class="p-6 text-slate-600">{{ $subject['avg'] }}</td>
                                            <td class="p-6 text-slate-600">{{ $subject['avg_character'] }}</td>
                                            <td class="p-6 text-slate-600">{{ $subject['avg_memorization'] }}</td>
                                            <td class="p-6">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-20 bg-slate-200 rounded-full h-1.5">
                                                        <div class="{{ $subject['pass_rate'] >= 75 ? 'bg-emerald-500' : 'bg-amber-500' }} h-1.5 rounded-full" style="width: {{ min($subject['pass_rate'], 100) }}%"></div>
                                                    </div>
                                                    <span class="text-xs font-medium text-slate-600">{{ $subject['pass_rate'] }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-8 text-center text-slate-500">Belum ada data mata pelajaran.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </x-card>
                </section>

            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-6 sm:space-y-8 min-w-0">

                <!-- Intervensi Diperlukan -->
                <section class="min-w-0">
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-4">Intervensi Diperlukan</h2>
                    @forelse($belowTargetClasses as $class)
                        <div class="bg-white border border-red-200 rounded-2xl p-4 sm:p-5 shadow-sm mb-3">
                            <div class="flex items-center gap-2 text-red-600 mb-2">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <h3 class="font-bold text-sm truncate">{{ $class['name'] }}</h3>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Rata-rata nilai <span class="font-bold text-red-600">{{ $class['avg'] }}</span> berada di bawah target 75.
                            </p>
                            <a href="{{ route('kepala-sekolah.academic.perkembangan') }}" class="inline-flex items-center min-h-[44px] mt-2 text-[10px] uppercase tracking-wider font-bold text-primary hover:underline">Lihat Detail &rarr;</a>
                        </div>
                    @empty
                        <x-card>
                            <p class="text-xs sm:text-sm text-slate-500">Tidak ada kelas yang membutuhkan intervensi. Semua kelas berada di atas target.</p>
                        </x-card>
                    @endforelse
                </section>

                <!-- Status Penilaian Guru -->
                <section class="min-w-0">
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-4">Status Penilaian Guru</h2>
                    <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <div>
                                <p class="text-xl sm:text-2xl font-bold text-slate-900">{{ $completedGrading }} / {{ $totalAssignments }}</p>
                                <p class="text-xs text-slate-500">tugas telah dinilai</p>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full shrink-0 {{ $lateGrading > 0 ? 'bg-orange-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ $lateGrading }} belum dinilai
                            </span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $totalAssignments > 0 ? ($completedGrading / $totalAssignments) * 100 : 0 }}%"></div>
                        </div>
                        <a href="{{ route('kepala-sekolah.supervision.grading-status') }}" class="inline-flex items-center min-h-[44px] mt-3 text-xs sm:text-sm font-semibold text-primary hover:text-primary/80">Lihat Detail Status &rarr;</a>
                    </div>
                </section>

                <!-- Guru Berkinerja Tinggi -->
                <section class="min-w-0">
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-4">Guru Berkinerja Tinggi</h2>
                    <div class="space-y-3">
                        @forelse($topTeachers as $teacher)
                            <a href="{{ route('kepala-sekolah.supervision.teacher-detail', $teacher->id) }}" class="flex items-center justify-between p-3.5 sm:p-4 bg-white border border-slate-200 rounded-xl hover:border-primary hover:shadow-sm transition group min-w-0">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="h-9 w-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold text-xs shrink-0">{{ substr($teacher->name, 0, 1) }}</div>
                                    <div class="min-w-0">
                                        <p class="text-xs sm:text-sm font-semibold text-slate-900 truncate">{{ $teacher->name }}</p>
                                        <p class="text-[10px] sm:text-xs text-slate-500 truncate">{{ $teacher->subjects }}</p>
                                    </div>
                                </div>
                                <span class="text-xs sm:text-sm font-bold text-slate-900 shrink-0 ml-2">{{ $teacher->score }}</span>
                            </a>
                        @empty
                            <x-card>
                                <p class="text-xs sm:text-sm text-slate-500">Belum ada data guru.</p>
                            </x-card>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>

        <!-- Section Tautan Cepat -->
        <section class="min-w-0">
            <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-4">Menu Cepat</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <a href="{{ route('kepala-sekolah.academic.rekap') }}" class="p-4 bg-white border border-slate-200 rounded-xl hover:border-primary hover:shadow-sm transition group min-h-[44px] flex items-center">
                    <div class="flex items-center gap-3">
                        <div class="bg-slate-100 text-slate-500 p-2 rounded-lg group-hover:bg-blue-50 group-hover:text-primary transition shrink-0">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/></svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700">Rekapitulasi Nilai</span>
                    </div>
                </a>
                <a href="{{ route('kepala-sekolah.supervision.teacher-report') }}" class="p-4 bg-white border border-slate-200 rounded-xl hover:border-primary hover:shadow-sm transition group min-h-[44px] flex items-center">
                    <div class="flex items-center gap-3">
                        <div class="bg-slate-100 text-slate-500 p-2 rounded-lg group-hover:bg-blue-50 group-hover:text-primary transition shrink-0">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/></svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700">Laporan Kinerja Guru</span>
                    </div>
                </a>
                <a href="{{ route('kepala-sekolah.feedback.create') }}" class="p-4 bg-white border border-slate-200 rounded-xl hover:border-primary hover:shadow-sm transition group min-h-[44px] flex items-center">
                    <div class="flex items-center gap-3">
                        <div class="bg-slate-100 text-slate-500 p-2 rounded-lg group-hover:bg-blue-50 group-hover:text-primary transition shrink-0">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700">Kirim Feedback</span>
                    </div>
                </a>
                <a href="{{ route('kepala-sekolah.evaluasi.index') }}" class="p-4 bg-white border border-slate-200 rounded-xl hover:border-primary hover:shadow-sm transition group min-h-[44px] flex items-center">
                    <div class="flex items-center gap-3">
                        <div class="bg-slate-100 text-slate-500 p-2 rounded-lg group-hover:bg-blue-50 group-hover:text-primary transition shrink-0">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700">Evaluasi Sekolah</span>
                    </div>
                </a>
            </div>
        </section>

    </div>
</x-layouts.app>