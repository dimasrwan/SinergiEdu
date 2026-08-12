<x-layouts.app>
    <x-slot:title>Monitoring Nilai Akademik</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Monitoring Nilai Akademik" description="Periksa sebaran rata-rata nilai kelas, mata pelajaran, dan lima komponen penilaian." />

        <x-card padding="none" class="mb-6">
            <div class="p-6">
                <form action="{{ route('waka.monitoring.grades') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <x-input-label for="class_id" value="Filter Kelas" class="mb-1.5" />
                        <x-select id="class_id" name="class_id" onchange="this.form.submit()">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </x-select>
                    </div>
                    <div>
                        <x-input-label for="subject_id" value="Filter Mapel" class="mb-1.5" />
                        <x-select id="subject_id" name="subject_id" onchange="this.form.submit()">
                            <option value="">Semua Mapel</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}" {{ $selectedSubjectId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </x-select>
                    </div>
                    <div class="flex items-end pb-2">
                        <span class="text-xs text-slate-400">Gunakan filter untuk memperkecil cakupan analisis data.</span>
                    </div>
                </form>
            </div>
        </x-card>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Grafik Rata-Rata Komponen Nilai -->
                <x-card padding="lg" class="flex flex-col justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 mb-4">Rata-Rata Komponen Penilaian</h4>
                        <div class="relative w-full" style="height: 300px;">
                            <canvas id="componentChart"></canvas>
                        </div>
                    </div>
                </x-card>

                <!-- Grafik Rata-Rata Nilai per Mata Pelajaran -->
                <x-card padding="lg" class="flex flex-col justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 mb-4">Perbandingan Nilai Mata Pelajaran</h4>
                        <div class="relative w-full" style="height: 300px;">
                            <canvas id="subjectChart"></canvas>
                        </div>
                    </div>
                </x-card>
            </div>
    </div>

    <!-- Script Load Chart.js CDN -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart Komponen
            const compCtx = document.getElementById('componentChart').getContext('2d');
            new Chart(compCtx, {
                type: 'radar',
                data: {
                    labels: ['Tes Awal', 'Tugas', 'Tes Akhir', 'Karakter', 'Hafalan'],
                    datasets: [{
                        label: 'Nilai Rata-rata',
                        data: [
                            {{ $avgPreTest }},
                            {{ $avgAssignment }},
                            {{ $avgPostTest }},
                            {{ $avgCharacter }},
                            {{ $avgMemorization }}
                        ],
                        backgroundColor: 'rgba(17, 159, 234, 0.2)',
                        borderColor: '#119FEA',
                        pointBackgroundColor: '#119FEA',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#119FEA'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            angleLines: { display: true },
                            suggestedMin: 0,
                            suggestedMax: 100
                        }
                    }
                }
            });

            // Chart Mapel
            const subCtx = document.getElementById('subjectChart').getContext('2d');
            const subjectLabels = {!! json_encode($subjectComparison->pluck('name')) !!};
            const subjectData = {!! json_encode($subjectComparison->pluck('avg')) !!};

            new Chart(subCtx, {
                type: 'bar',
                data: {
                    labels: subjectLabels.length ? subjectLabels : ['Belum ada data'],
                    datasets: [{
                        label: 'Rata-rata Nilai',
                        data: subjectData.length ? subjectData : [0],
                        backgroundColor: 'rgba(18, 59, 130, 0.85)',
                        borderColor: '#123B82',
                        borderWidth: 1,
                        borderRadius: 8
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
