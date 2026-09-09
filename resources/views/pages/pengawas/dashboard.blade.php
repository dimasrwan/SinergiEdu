<x-layouts.app>
    <x-slot:title>Dashboard Pengawas Sekolah</x-slot:title>

    <div class="space-y-8">
        <!-- School Health Index Banner -->
        <div class="bg-primary rounded-2xl p-8 text-white shadow-xl shadow-primary/20 relative overflow-hidden mb-8">
            <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight mb-2">Pengawasan Sekolah</h1>
                    <p class="text-blue-200 text-sm max-w-xl">Pantau performa agregat sekolah, sebaran nilai akademik, serta kelola evaluasi sekolah dengan mudah.</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <x-card padding="sm" class="hover:shadow-sm transition-shadow cursor-pointer min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-500 block truncate">Rata-rata Sekolah</span>
                        <h3 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mt-1 truncate">{{ $schoolAvgGrade > 0 ? $schoolAvgGrade : '0.00' }}</h3>
                    </div>
                    <div class="text-primary bg-blue-50/50 p-2.5 sm:p-3 rounded-xl border border-blue-100 shrink-0">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                    </div>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-sm transition-shadow cursor-pointer min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-500 block truncate">Total Guru</span>
                        <h3 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mt-1 truncate">{{ $totalTeachers }}</h3>
                    </div>
                    <div class="text-accent bg-sky-50/50 p-2.5 sm:p-3 rounded-xl border border-sky-100 shrink-0">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
                    </div>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-sm transition-shadow cursor-pointer min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-500 block truncate">Total Siswa</span>
                        <h3 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mt-1 truncate">{{ $totalStudents }}</h3>
                    </div>
                    <div class="text-primary bg-blue-50/50 p-2.5 sm:p-3 rounded-xl border border-blue-100 shrink-0">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                    </div>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-sm transition-shadow cursor-pointer min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-500 block truncate">Total Kelas</span>
                        <h3 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mt-1 truncate">{{ $totalClasses }}</h3>
                    </div>
                    <div class="text-accent bg-sky-50/50 p-2.5 sm:p-3 rounded-xl border border-sky-100 shrink-0">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 22v-4a2 2 0 1 0-4 0v4"/><path d="m18 10 4 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-8l4-2"/><path d="M18 5v17"/><path d="m4 6 8-4 8 4"/><path d="M6 5v17"/><circle cx="12" cy="9" r="2"/></svg>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Peringkat Kelas -->
            <div class="lg:col-span-1 min-w-0">
                <x-card padding="none" class="h-full">
                    <div class="p-4 sm:p-6 border-b border-slate-100">
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900">Peringkat Akademik Kelas</h3>
                    </div>
                    <x-table :headers="['Peringkat', 'Kelas', 'Rata-rata']">
                        @forelse($classRankings as $index => $rank)
                            <tr>
                                <td class="px-4 py-4 font-bold text-slate-900">#{{ $index + 1 }}</td>
                                <td class="px-4 py-4 font-semibold text-slate-800">{{ $rank['name'] }}</td>
                                <td class="px-4 py-4 font-bold text-primary text-right">{{ $rank['avg'] > 0 ? $rank['avg'] : '-' }}</td>
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
            <div class="lg:col-span-2 min-w-0">
                <x-card padding="md" class="h-full">
                    <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-4">Sebaran Nilai Komponen Akademik Sekolah</h3>
                    <div class="relative w-full min-w-0" style="height: 280px;">
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
