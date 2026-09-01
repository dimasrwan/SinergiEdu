<x-layouts.app>
    <x-slot:title>Dashboard Pengawas Sekolah</x-slot:title>

    <div class="space-y-8">
        <!-- School Health Index Banner -->
        <div class="bg-primary rounded-3xl p-8 text-white shadow-xl shadow-primary/20 relative overflow-hidden mb-8">
            <div
                class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3">
            </div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight mb-2">School Oversight Experience</h1>
                    <p class="text-blue-200 text-sm max-w-xl">Pantau performa agregat sekolah, sebaran nilai akademik,
                        serta kelola evaluasi sekolah dengan mudah.</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <x-card padding="sm" class="hover:shadow-md transition-shadow cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Rata-rata Nilai
                            Sekolah</span>
                        <h3 class="text-3xl font-bold tracking-tight text-slate-900 mt-1">
                            {{ $schoolAvgGrade > 0 ? $schoolAvgGrade : '0.00' }}</h3>
                    </div>
                    <div class="text-primary bg-blue-50/50 p-3 rounded-xl border border-blue-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292" />
                        </svg>
                    </div>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-md transition-shadow cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Guru</span>
                        <h3 class="text-3xl font-bold tracking-tight text-slate-900 mt-1">{{ $totalTeachers }}</h3>
                    </div>
                    <div class="text-accent bg-sky-50/50 p-3 rounded-xl border border-sky-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493" />
                        </svg>
                    </div>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-md transition-shadow cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Siswa</span>
                        <h3 class="text-3xl font-bold tracking-tight text-slate-900 mt-1">{{ $totalStudents }}</h3>
                    </div>
                    <div class="text-primary bg-blue-50/50 p-3 rounded-xl border border-blue-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 18.72a9.005 9.005 0 00-3.096-1.51 4.5 4.5 0 00-5.808 0 9.005 9.005 0 00-3.096 1.51M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-md transition-shadow cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Kelas</span>
                        <h3 class="text-3xl font-bold tracking-tight text-slate-900 mt-1">{{ $totalClasses }}</h3>
                    </div>
                    <div class="text-accent bg-sky-50/50 p-3 rounded-xl border border-sky-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25" />
                        </svg>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Aktivitas Pengawas Cards -->
        <div class="space-y-4">
            <h2 class="text-lg font-bold text-slate-800">Aktivitas Pengawasan Anda</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('pengawas.evaluations.index') }}" class="block">
                    <x-card padding="sm"
                        class="hover:shadow-md hover:border-primary/30 transition border border-transparent cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Dokumen
                                    Evaluasi</span>
                                <h3 class="text-2xl font-bold tracking-tight text-slate-900 mt-1">
                                    {{ $totalEvaluations }}</h3>
                            </div>
                            <div class="text-cyan-600 bg-cyan-50/50 p-3 rounded-xl border border-cyan-100">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                            </div>
                        </div>
                    </x-card>
                </a>

                <a href="{{ route('pengawas.students.index') }}" class="block">
                    <x-card padding="sm"
                        class="hover:shadow-md hover:border-primary/30 transition border border-transparent cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Umpan Balik
                                    Siswa</span>
                                <h3 class="text-2xl font-bold tracking-tight text-slate-900 mt-1">{{ $totalFeedbacks }}
                                </h3>
                            </div>
                            <div class="text-emerald-600 bg-emerald-50/50 p-3 rounded-xl border border-emerald-100">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a.598.598 0 0 1-.655-.705l.337-2.3a8.19 8.19 0 0 1-1.593-4.715C3.51 7.444 7.54 3.75 12 3.75c4.97 0 9 3.706 9 8.25ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                                </svg>
                            </div>
                        </div>
                    </x-card>
                </a>

                <a href="{{ route('pengawas.action-plans.index') }}" class="block">
                    <x-card padding="sm"
                        class="hover:shadow-md hover:border-primary/30 transition border border-transparent cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Rencana
                                    Aksi</span>
                                <h3 class="text-2xl font-bold tracking-tight text-slate-900 mt-1">
                                    {{ $totalActionPlans }}</h3>
                            </div>
                            <div class="text-violet-600 bg-violet-50/50 p-3 rounded-xl border border-violet-100">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                                </svg>
                            </div>
                        </div>
                    </x-card>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Peringkat Kelas -->
            <div class="lg:col-span-1">
                <x-card padding="none" class="h-full">
                    <div class="p-6 border-b border-slate-100">
                        <h3 class="text-lg font-semibold text-slate-900">Peringkat Akademik Kelas</h3>
                    </div>
                    <x-table :headers="['Peringkat', 'Kelas', 'Rata-rata']">
                        @forelse($classRankings as $index => $rank)
                            <tr>
                                <td class="px-4 py-3 font-bold text-slate-900">#{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-800">{{ $rank['name'] }}</td>
                                <td class="px-4 py-3 font-bold text-primary text-right">
                                    {{ $rank['avg'] > 0 ? $rank['avg'] : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-slate-400">Belum ada data nilai.</td>
                            </tr>
                        @endforelse
                    </x-table>
                </x-card>
            </div>

            <!-- Grafik Sekolah -->
            <div class="lg:col-span-2">
                <x-card padding="md" class="h-full">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Sebaran Nilai Komponen Akademik Sekolah</h3>
                    <div class="relative w-full" style="height: 300px;">
                        <canvas id="componentSchoolChart"></canvas>
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <!-- Script Load Chart.js CDN -->
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('componentSchoolChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Tes Awal', 'Tugas', 'Tes Akhir', 'Karakter', 'Hafalan'],
                        datasets: [{
                            label: 'Nilai Rata-rata Komponen Sekolah',
                            data: [
                                {{ $avgPreTest }},
                                {{ $avgAssignment }},
                                {{ $avgPostTest }},
                                {{ $avgCharacter }},
                                {{ $avgMemorization }}
                            ],
                            backgroundColor: [
                                'rgba(18, 59, 130, 0.8)', // Primary Blue
                                'rgba(17, 159, 234, 0.8)', // Accent Blue
                                'rgba(18, 59, 130, 0.6)',
                                'rgba(17, 159, 234, 0.6)',
                                'rgba(18, 59, 130, 0.4)'
                            ],
                            borderColor: [
                                '#123B82',
                                '#119FEA',
                                '#123B82',
                                '#119FEA',
                                '#123B82'
                            ],
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
</x-layouts.app>