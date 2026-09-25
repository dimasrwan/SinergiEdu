<x-layouts.app>
    <x-slot:title>Monitoring Nilai Akademik</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Monitoring Nilai Akademik" description="Periksa sebaran rata-rata nilai kelas, mata pelajaran, dan lima komponen penilaian.">
            <x-slot:actions>
                <x-button variant="secondary" href="{{ route('waka.monitoring.export-excel-klasikal', request()->query()) }}">
                    Unduh Excel
                </x-button>
            </x-slot:actions>
        </x-page-header>

        <x-card padding="none" class="mb-6 border border-slate-200/75 min-w-0">
            <div class="p-4 sm:p-6 min-w-0">
                <form action="{{ route('waka.monitoring.grades') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 min-w-0">
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
                        <x-input-label for="meeting_id" value="Filter Pertemuan" class="mb-1.5" />
                        <x-select id="meeting_id" name="meeting_id" onchange="this.form.submit()">
                            <option value="">Semua Pertemuan</option>
                            @foreach($meetings as $meeting)
                                <option value="{{ $meeting->id }}" {{ $selectedMeetingId == $meeting->id ? 'selected' : '' }}>
                                    P{{ $meeting->meeting_number }} · {{ $meeting->meeting_date->format('d M Y') }} · {{ $meeting->topic }}
                                </option>
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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 min-w-0">
            <!-- Grafik Rata-Rata Komponen Nilai -->
            <x-card padding="lg" class="flex flex-col justify-between min-w-0">
                <div class="min-w-0">
                    <h4 class="text-xs sm:text-sm font-bold text-slate-800 mb-4 truncate">Rata-Rata Komponen Penilaian</h4>
                    <div class="relative w-full min-w-0 h-[260px] sm:h-[300px]">
                        <canvas id="componentChart"></canvas>
                    </div>
                </div>
            </x-card>

            <!-- Grafik Rata-Rata Nilai per Mata Pelajaran -->
            <x-card padding="lg" class="flex flex-col justify-between min-w-0">
                <div class="min-w-0">
                    <h4 class="text-xs sm:text-sm font-bold text-slate-800 mb-4 truncate">Perbandingan Nilai Mata Pelajaran</h4>
                    <div class="relative w-full min-w-0 h-[260px] sm:h-[300px]">
                        <canvas id="subjectChart"></canvas>
                    </div>
                </div>
            </x-card>
        </div>

        <x-card padding="none" class="border border-slate-200/75 min-w-0">
            <div class="border-b border-slate-100 p-4 sm:p-6 min-w-0">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 truncate">Rekap Nilai Siswa</h2>
                <p class="mt-1 text-xs sm:text-sm text-slate-500 truncate">Bandingkan setiap capaian siswa dengan rata-rata komponen pada filter aktif.</p>
            </div>

            <!-- Desktop Table (hidden lg:block) -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-4">Siswa</th>
                            <th class="px-5 py-4">Kelas / Mapel</th>
                            <th class="px-5 py-4">Pertemuan</th>
                            <th class="px-5 py-4 text-center">Tes Awal</th>
                            <th class="px-5 py-4 text-center">Tugas</th>
                            <th class="px-5 py-4 text-center">Tes Akhir</th>
                            <th class="px-5 py-4 text-center">Karakter</th>
                            <th class="px-5 py-4 text-center">Hafalan</th>
                            <th class="px-5 py-4 text-center">Rata-rata</th>
                            <th class="px-5 py-4">Catatan Guru</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($gradesData as $grade)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-5 py-4"><p class="font-semibold text-slate-900">{{ $grade->student->user->name ?? '-' }}</p><p class="text-xs text-slate-400">NIS: {{ $grade->student->nis ?? '-' }}</p></td>
                                <td class="px-5 py-4 text-slate-600"><p>{{ $grade->learningMeeting->classroom->name ?? '-' }}</p><p class="text-xs text-slate-400">{{ $grade->learningMeeting->subject->name ?? '-' }}</p></td>
                                <td class="px-5 py-4 text-slate-600"><p>P{{ $grade->learningMeeting->meeting_number ?? '-' }}</p><p class="text-xs text-slate-400">{{ $grade->learningMeeting->meeting_date?->format('d M Y') ?? '-' }}</p></td>
                                <td class="px-5 py-4 text-center">{{ $grade->pre_test_score ?? '-' }}</td>
                                <td class="px-5 py-4 text-center">{{ $grade->assignment_score ?? '-' }}</td>
                                <td class="px-5 py-4 text-center">{{ $grade->post_test_score ?? '-' }}</td>
                                <td class="px-5 py-4 text-center">{{ $grade->character_score ?? '-' }}</td>
                                <td class="px-5 py-4 text-center">{{ $grade->memorization_score ?? '-' }}</td>
                                <td class="px-5 py-4 text-center"><span class="font-bold text-primary">{{ $grade->average_score }}</span></td>
                                <td class="max-w-xs px-5 py-4 text-xs text-slate-500">{{ $grade->notes ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="px-5 py-12 text-center text-slate-500">Belum ada penilaian per pertemuan pada tahun ajaran dan semester aktif.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View (lg:hidden) -->
            <div class="lg:hidden p-4 space-y-3.5 divide-y divide-slate-100">
                @forelse ($gradesData as $grade)
                    <div class="pt-3.5 first:pt-0 space-y-3 min-w-0">
                        <div class="flex items-start justify-between gap-2 min-w-0">
                            <div class="min-w-0 flex-1">
                                <h4 class="font-bold text-slate-900 text-sm truncate">{{ $grade->student->user->name ?? '-' }}</h4>
                                <p class="text-xs text-slate-500 flex flex-wrap gap-1.5 mt-0.5">
                                    <span class="font-semibold text-primary">Kelas {{ $grade->learningMeeting->classroom->name ?? '-' }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $grade->learningMeeting->subject->name ?? '-' }}</span>
                                </p>
                            </div>
                            <div class="shrink-0 flex flex-col items-center justify-center p-2 bg-blue-50 text-primary rounded-xl min-w-[56px] border border-blue-100">
                                <span class="text-[9px] font-bold uppercase opacity-75">Avg</span>
                                <span class="text-base font-black">{{ $grade->average_score }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2 text-xs">
                            <div class="p-2 bg-slate-50 rounded-xl border border-slate-100 text-center">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Tes Awal</span>
                                <span class="font-bold text-slate-800 mt-0.5 block">{{ $grade->pre_test_score ?? '-' }}</span>
                            </div>
                            <div class="p-2 bg-slate-50 rounded-xl border border-slate-100 text-center">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Tugas</span>
                                <span class="font-bold text-slate-800 mt-0.5 block">{{ $grade->assignment_score ?? '-' }}</span>
                            </div>
                            <div class="p-2 bg-slate-50 rounded-xl border border-slate-100 text-center">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Tes Akhir</span>
                                <span class="font-bold text-slate-800 mt-0.5 block">{{ $grade->post_test_score ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="p-2 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Karakter</span>
                                <span class="font-bold text-slate-800">{{ $grade->character_score ?? '-' }}</span>
                            </div>
                            <div class="p-2 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Hafalan</span>
                                <span class="font-bold text-slate-800">{{ $grade->memorization_score ?? '-' }}</span>
                            </div>
                        </div>

                        @if($grade->notes)
                            <div class="p-2.5 bg-slate-50 rounded-xl text-xs text-slate-600 leading-relaxed italic break-words">
                                "{{ $grade->notes }}"
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-500 text-sm">Belum ada penilaian per pertemuan pada tahun ajaran dan semester aktif.</div>
                @endforelse
            </div>
        </x-card>
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
