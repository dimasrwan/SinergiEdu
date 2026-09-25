<x-layouts.app>
    <x-slot:title>Dashboard Pengawas Sekolah</x-slot:title>

    <div class="space-y-8">
        <!-- School Health Index Banner -->
        <div class="bg-primary rounded-2xl p-8 text-white shadow-xl shadow-primary/20 relative overflow-hidden mb-8">
            <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight mb-2">Pengawasan: {{ $activeSchool->name }}</h1>
                    <p class="text-blue-200 text-sm max-w-xl">Memantau performa agregat sekolah, sebaran nilai akademik, serta aktivitas seluruh role di {{ $activeSchool->name }}.</p>
                </div>
                <div class="shrink-0">
                    <x-button variant="secondary" href="{{ route('pengawas.select-school') }}" class="bg-white/10 border-white/20 text-white hover:bg-white/20 transition-all gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        Ganti Sekolah
                    </x-button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-6">
            <x-card padding="sm" class="hover:shadow-sm transition-shadow cursor-pointer min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-500 block truncate">Rata-rata Sekolah</span>
                        <h3 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mt-1 truncate">{{ $schoolAvgGrade > 0 ? $schoolAvgGrade : '0.00' }}</h3>
                    </div>
                    <div class="text-primary bg-blue-50/50 p-2 sm:p-2.5 rounded-xl border border-blue-100 shrink-0">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                    </div>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-sm transition-shadow cursor-pointer min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-500 block truncate">Total Guru</span>
                        <h3 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mt-1 truncate">{{ $totalTeachers }}</h3>
                    </div>
                    <div class="text-accent bg-sky-50/50 p-2 sm:p-2.5 rounded-xl border border-sky-100 shrink-0">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
                    </div>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-sm transition-shadow cursor-pointer min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-500 block truncate">Total Siswa</span>
                        <h3 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mt-1 truncate">{{ $totalStudents }}</h3>
                    </div>
                    <div class="text-primary bg-blue-50/50 p-2 sm:p-2.5 rounded-xl border border-blue-100 shrink-0">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                    </div>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-sm transition-shadow cursor-pointer min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-500 block truncate">Waka Kurikulum</span>
                        <h3 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mt-1 truncate">{{ $totalWaka }}</h3>
                    </div>
                    <div class="text-accent bg-sky-50/50 p-2 sm:p-2.5 rounded-xl border border-sky-100 shrink-0">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-sm transition-shadow cursor-pointer min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-500 block truncate">Kepala Sekolah</span>
                        <h3 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mt-1 truncate">{{ $totalKepsek }}</h3>
                    </div>
                    <div class="text-primary bg-blue-50/50 p-2 sm:p-2.5 rounded-xl border border-blue-100 shrink-0">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-sm transition-shadow cursor-pointer min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-500 block truncate">Total Kelas</span>
                        <h3 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mt-1 truncate">{{ $totalClasses }}</h3>
                    </div>
                    <div class="text-accent bg-sky-50/50 p-2 sm:p-2.5 rounded-xl border border-sky-100 shrink-0">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 22v-4a2 2 0 1 0-4 0v4"/><path d="m18 10 4 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-8l4-2"/><path d="M18 5v17"/><path d="m4 6 8-4 8 4"/><path d="M6 5v17"/><circle cx="12" cy="9" r="2"/></svg>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Tab Section: Sebaran Nilai & Peringkat Kelas -->
        <div x-data="{ activeTab: 'sebaran' }" class="space-y-4">
            <!-- Tab Headers -->
            <div class="flex border-b border-slate-200 bg-slate-100/70 p-1.5 rounded-2xl gap-1">
                <button type="button" 
                        @click="activeTab = 'sebaran'; $nextTick(() => { if (window.componentSchoolChartInstance) window.componentSchoolChartInstance.resize(); })"
                        :class="activeTab === 'sebaran' ? 'bg-white text-primary shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50'"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                    <span>Sebaran Nilai Komponen Akademik Sekolah</span>
                </button>

                <button type="button" 
                        @click="activeTab = 'peringkat'"
                        :class="activeTab === 'peringkat' ? 'bg-white text-primary shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50'"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.003 0H9.497m5.003 0a3.375 3.375 0 0 0 3.375-3.375V6.75a3.375 3.375 0 0 0-3.375-3.375H9.497a3.375 3.375 0 0 0-3.375 3.375v5.25a3.375 3.375 0 0 0 3.375 3.375" />
                    </svg>
                    <span>Peringkat Akademik Kelas</span>
                </button>
            </div>

            <!-- TAB 1: Sebaran Nilai Komponen Sekolah -->
            <div x-show="activeTab === 'sebaran'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                <x-card padding="md" class="w-full">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-4 border-b border-slate-100 gap-2">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900">Grafik Sebaran Nilai Komponen Akademik</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Rata-rata nilai agregat sekolah untuk setiap komponen penilaian di {{ $activeSchool->name }}.</p>
                        </div>
                    </div>
                    <div class="relative w-full min-w-0" style="height: 340px;">
                        <canvas id="componentSchoolChart"></canvas>
                    </div>
                </x-card>
            </div>

            <!-- TAB 2: Peringkat Akademik Kelas -->
            <div x-show="activeTab === 'peringkat'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                <x-card padding="none" class="w-full">
                    <div class="p-4 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900">Peringkat Akademik Kelas</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Daftar kelas berdasarkan pencapaian rata-rata nilai akademik.</p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-blue-50 text-primary text-xs font-bold w-fit">
                            Total: {{ count($classRankings) }} Kelas
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <x-table :headers="['Peringkat', 'Nama Kelas', 'Rata-Rata Nilai', 'Status Performa']">
                            @forelse($classRankings as $index => $rank)
                                @php
                                    $avg = (float) $rank['avg'];
                                    if ($avg >= 85) {
                                        $statusClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                        $statusLabel = 'Sangat Baik';
                                    } elseif ($avg >= 75) {
                                        $statusClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                        $statusLabel = 'Baik';
                                    } elseif ($avg >= 60) {
                                        $statusClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                        $statusLabel = 'Cukup';
                                    } elseif ($avg > 0) {
                                        $statusClass = 'bg-rose-50 text-rose-700 border-rose-200';
                                        $statusLabel = 'Perlu Bimbingan';
                                    } else {
                                        $statusClass = 'bg-slate-100 text-slate-600 border-slate-200';
                                        $statusLabel = 'Belum Ada Nilai';
                                    }
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-4 py-4 font-bold text-slate-900">
                                        @if($index === 0)
                                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-amber-100 text-amber-800 text-xs font-black">🥇 1</span>
                                        @elseif($index === 1)
                                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-slate-200 text-slate-700 text-xs font-black">🥈 2</span>
                                        @elseif($index === 2)
                                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-amber-50 text-amber-700 text-xs font-black">🥉 3</span>
                                        @else
                                            <span class="text-sm text-slate-600 pl-2">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 font-bold text-slate-800">{{ $rank['name'] }}</td>
                                    <td class="px-4 py-4 font-black text-primary text-base">{{ $rank['avg'] > 0 ? $rank['avg'] : '-' }}</td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold border {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-slate-400">Belum ada data nilai kelas.</td>
                                </tr>
                            @endforelse
                        </x-table>
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
            window.componentSchoolChartInstance = new Chart(ctx, {
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
                            'rgba(18, 59, 130, 0.85)', // Primary Blue
                            'rgba(17, 159, 234, 0.85)', // Accent Blue
                            'rgba(18, 59, 130, 0.65)',
                            'rgba(17, 159, 234, 0.65)',
                            'rgba(18, 59, 130, 0.45)'
                        ],
                        borderColor: [
                            '#123B82',
                            '#119FEA',
                            '#123B82',
                            '#119FEA',
                            '#123B82'
                        ],
                        borderWidth: 1.5,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    family: 'Inter, sans-serif',
                                    weight: 'bold'
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                stepSize: 20
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-layouts.app>
