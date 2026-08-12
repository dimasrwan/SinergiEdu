<x-layouts.app>
    <x-slot:title>Monitoring Perkembangan Siswa</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Monitoring Perkembangan Siswa" description="Analisis perkembangan dan tren nilai akademik dari masing-masing siswa secara detail." />

        <x-card padding="none" class="mb-6">
            <div class="p-6">
                <form action="{{ route('waka.monitoring.student-progress') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-4">
                    <div class="flex-1">
                        <x-input-label for="student_id" value="Pilih Siswa" class="mb-1.5" />
                        <x-select id="student_id" name="student_id" onchange="this.form.submit()">
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($students as $s)
                                <option value="{{ $s->id }}" {{ $selectedStudentId == $s->id ? 'selected' : '' }}>
                                    {{ $s->user->name }} (NIS: {{ $s->nis ?? '-' }})
                                </option>
                            @endforeach
                        </x-select>
                    </div>
                    <div>
                        <x-button type="submit" variant="primary" class="w-full">
                            Tampilkan Perkembangan
                        </x-button>
                    </div>
                </form>
            </div>
        </x-card>

            @if($selectedStudent)
                <div class="space-y-6">
                    <x-card padding="sm" class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50 border-slate-200">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">{{ $selectedStudent->user->name }}</h3>
                            <p class="text-sm text-slate-500 mt-0.5">NIS: {{ $selectedStudent->nis ?? '-' }} &bull; NISN: {{ $selectedStudent->nisn ?? '-' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-slate-400 block font-medium">Tahun Ajaran Aktif</span>
                            <span class="text-sm font-bold text-blue-700">{{ $activeYear->year ?? '-' }}</span>
                        </div>
                    </x-card>

                    @if($studentGrades->isEmpty())
                        <x-card padding="lg" class="text-center py-12 text-slate-400 border border-slate-100 bg-white">
                            Belum ada rekam nilai untuk siswa ini pada tahun ajaran aktif.
                        </x-card>
                    @else
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Tabel Nilai Siswa -->
                            <div class="lg:col-span-1">
                                <x-card padding="lg" class="h-full">
                                    <h4 class="text-sm font-bold text-slate-800 mb-4">Rekap Nilai Pelajaran</h4>
                                    <div class="space-y-3 overflow-y-auto max-h-[300px] pr-2">
                                        @foreach($studentGrades as $grade)
                                            <div class="bg-slate-50 border border-slate-200/60 rounded-xl p-3 flex justify-between items-center transition-colors hover:bg-white hover:border-slate-300">
                                                <div class="min-w-0 flex-1">
                                                    <span class="text-xs font-semibold text-slate-700 truncate block">{{ $grade->subject->name ?? '-' }}</span>
                                                    <span class="text-[10px] text-slate-400">Rata-rata: {{ $grade->average_score }}</span>
                                                </div>
                                                <div class="text-right">
                                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg font-bold text-xs bg-blue-50 text-blue-700">
                                                        {{ $grade->average_score }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </x-card>
                            </div>

                            <!-- Grafik Progress Nilai Siswa -->
                            <div class="lg:col-span-2">
                                <x-card padding="lg" class="h-full flex flex-col justify-between">
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800 mb-4">Grafik Perkembangan Nilai Pelajaran</h4>
                                        <div class="relative w-full" style="height: 300px;">
                                            <canvas id="progressChart"></canvas>
                                        </div>
                                    </div>
                                </x-card>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <x-card padding="lg" class="py-16 text-center border-dashed border-slate-200 shadow-none bg-slate-50/50 text-slate-400 text-sm">
                    Pilih siswa di atas untuk melihat detail grafik perkembangan nilainya.
                </x-card>
            @endif
    </div>

    <!-- Script Chart.js -->
    @if($selectedStudent && $studentGrades->isNotEmpty())
        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('progressChart').getContext('2d');
                const subjectNames = {!! json_encode($studentGrades->map(fn($g) => $g->subject->name ?? '-')) !!};
                
                // Fetch datasets
                const preTestScores = {!! json_encode($studentGrades->pluck('pre_test_score')) !!};
                const assignmentScores = {!! json_encode($studentGrades->pluck('assignment_score')) !!};
                const postTestScores = {!! json_encode($studentGrades->pluck('post_test_score')) !!};
                const characterScores = {!! json_encode($studentGrades->pluck('character_score')) !!};
                const memorizationScores = {!! json_encode($studentGrades->pluck('memorization_score')) !!};

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: subjectNames,
                        datasets: [
                            {
                                label: 'Tes Awal',
                                data: preTestScores,
                                borderColor: '#123B82', // Primary
                                backgroundColor: 'transparent',
                                borderWidth: 2,
                                tension: 0.2
                            },
                            {
                                label: 'Tugas',
                                data: assignmentScores,
                                borderColor: '#119FEA', // Accent
                                backgroundColor: 'transparent',
                                borderWidth: 2,
                                tension: 0.2
                            },
                            {
                                label: 'Tes Akhir',
                                data: postTestScores,
                                borderColor: '#0EA5E9', // Sky 500
                                backgroundColor: 'transparent',
                                borderWidth: 2,
                                tension: 0.2
                            },
                            {
                                label: 'Karakter',
                                data: characterScores,
                                borderColor: '#475569', // Slate 600
                                backgroundColor: 'transparent',
                                borderWidth: 2,
                                tension: 0.2
                            },
                            {
                                label: 'Hafalan',
                                data: memorizationScores,
                                borderColor: '#94A3B8', // Slate 400
                                backgroundColor: 'transparent',
                                borderWidth: 2,
                                tension: 0.2
                            }
                        ]
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
    @endif
</x-layouts.app>
