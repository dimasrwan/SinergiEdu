<x-layouts.app>
    <x-slot:title>Dashboard Pengawas Sekolah</x-slot:title>

    <div class="space-y-8">
        <!-- School Health Index Banner -->
        <div class="bg-primary rounded-3xl p-8 text-white shadow-xl shadow-primary/20 relative overflow-hidden mb-8">
            <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight mb-2">Pengawasan Sekolah</h1>
                    <p class="text-blue-200 text-sm max-w-xl">Pantau performa agregat sekolah, sebaran nilai akademik, serta kelola evaluasi sekolah dengan mudah.</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <x-card padding="sm" class="hover:shadow-md transition-shadow cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Rata-rata Nilai Sekolah</span>
                        <h3 class="text-3xl font-bold tracking-tight text-slate-900 mt-1">{{ $schoolAvgGrade > 0 ? $schoolAvgGrade : '0.00' }}</h3>
                    </div>
                    <div class="text-primary bg-blue-50/50 p-3 rounded-xl border border-blue-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292" />
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
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493" />
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
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.005 9.005 0 00-3.096-1.51 4.5 4.5 0 00-5.808 0 9.005 9.005 0 00-3.096 1.51M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
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
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25" />
                        </svg>
                    </div>
                </div>
            </x-card>
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
                                <td class="px-4 py-3 font-bold text-primary text-right">{{ $rank['avg'] > 0 ? $rank['avg'] : '-' }}</td>
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
        document.addEventListener('DOMContentLoaded', function() {
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
