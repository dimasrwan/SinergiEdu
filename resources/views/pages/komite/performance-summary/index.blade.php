<x-layouts.app>
    <x-slot:title>Ringkasan Kinerja Sekolah</x-slot:title>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Ringkasan Kinerja Sekolah</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Indikator kinerja agregat dan statistik akademik sekolah.</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold border border-slate-200 self-start sm:self-auto">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
                Data Agregat Terprivasi
            </span>
        </div>

        <!-- 4 Grid Stat Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
            <x-card padding="sm">
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400">Total Siswa</p>
                <h3 class="text-xl sm:text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($totalStudents) }}</h3>
                <p class="text-[10px] sm:text-xs text-slate-500 mt-1">Siswa Terdaftar</p>
            </x-card>

            <x-card padding="sm">
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400">Total Guru</p>
                <h3 class="text-xl sm:text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($totalTeachers) }}</h3>
                <p class="text-[10px] sm:text-xs text-slate-500 mt-1">Tenaga Pendidik</p>
            </x-card>

            <x-card padding="sm">
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400">Total Kelas</p>
                <h3 class="text-xl sm:text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($totalClasses) }}</h3>
                <p class="text-[10px] sm:text-xs text-slate-500 mt-1">Rombongan Belajar</p>
            </x-card>

            <x-card padding="sm">
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400">Total Mata Pelajaran</p>
                <h3 class="text-xl sm:text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($totalSubjects) }}</h3>
                <p class="text-[10px] sm:text-xs text-slate-500 mt-1">Kurikulum Aktif</p>
            </x-card>
        </div>

        <!-- Detail Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-card padding="md">
                <h3 class="text-base font-bold text-slate-900 mb-3">Indikator Efisiensi Operasional</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <span class="text-xs font-medium text-slate-600">Rasio Guru & Siswa</span>
                        <span class="text-sm font-bold text-slate-900">
                            @if($totalTeachers > 0)
                                1 : {{ round($totalStudents / $totalTeachers, 1) }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <span class="text-xs font-medium text-slate-600">Rata-rata Ukuran Kelas</span>
                        <span class="text-sm font-bold text-slate-900">
                            @if($totalClasses > 0)
                                {{ round($totalStudents / $totalClasses, 1) }} Siswa/Rombel
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-600">Status Operational School</span>
                        <span class="px-2.5 py-0.5 rounded bg-emerald-50 text-emerald-700 text-xs font-bold">Aktif & Optimal</span>
                    </div>
                </div>
            </x-card>

            <x-card padding="md">
                <h3 class="text-base font-bold text-slate-900 mb-3">Konteks Tahun Ajaran</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <span class="text-xs font-medium text-slate-600">Tahun Ajaran Aktif</span>
                        <span class="text-sm font-bold text-primary">{{ $activeAcademicYear->year ?? 'Belum Diatur' }}</span>
                    </div>
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <span class="text-xs font-medium text-slate-600">Semester Aktif</span>
                        <span class="text-sm font-bold text-slate-900">{{ $activeSemester->name ?? 'Belum Diatur' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-600">Privasi Data</span>
                        <span class="text-xs font-semibold text-slate-500">Nilai & Data Individual Dilindungi</span>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</x-layouts.app>
