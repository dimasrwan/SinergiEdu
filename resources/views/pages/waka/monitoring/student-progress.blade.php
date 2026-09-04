<x-layouts.app>
    <x-slot:title>Monitoring Perkembangan Siswa</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Monitoring Perkembangan Siswa" description="Analisis perkembangan dan tren nilai akademik dari masing-masing siswa secara detail." />

        <x-card padding="none" class="mb-6">
            <div class="p-6">
                <form action="{{ route('waka.monitoring.student-progress') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
    <!-- Kelas -->
    <div>
        <x-input-label for="class_id" value="Pilih Kelas" class="mb-2 font-bold text-slate-700" />
        <x-searchable-select name="class_id" :selected="request('class_id')" :options="$classes->map(fn($c) => ['label' => $c->name, 'value' => $c->id])->toArray()" />
    </div>

    <!-- Siswa -->
    <div class="md:col-span-2">
        <x-input-label for="student_id" value="Pilih Siswa" class="mb-2 font-bold text-slate-700" />
        <x-searchable-select name="student_id" :selected="$selectedStudentId" :options="$students->map(fn($s) => ['label' => $s->user->name . ' (NIS: ' . ($s->nis ?? '-') . ')', 'value' => $s->id])->toArray()" />
    </div>

    <!-- Minggu -->
    <div>
        <x-input-label for="week_number" value="Minggu Monitoring" class="mb-2 font-bold text-slate-700" />
        <x-select id="week_number" name="week_number" class="w-full">
            @foreach($weeks as $week)
                <option value="{{ $week }}" {{ $selectedWeek === $week ? 'selected' : '' }}>{{ $week }}</option>
            @endforeach
        </x-select>
    </div>

    <!-- Submit -->
    <div class="md:col-span-4 flex justify-end pt-2">
        <x-button type="submit" variant="primary" class="px-8">
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
                            <p class="mt-1 text-xs text-slate-400">Orang tua: {{ $selectedStudent->parent->user->name ?? '-' }}</p>
                        </div>
                        <div class="flex items-end gap-3 text-right">
                            <x-button variant="secondary" href="{{ route('waka.monitoring.student-progress.export', ['student_id' => $selectedStudent->id]) }}">Unduh CSV</x-button>
                            <div>
                            <span class="text-xs text-slate-400 block font-medium">Tahun Ajaran Aktif</span>
                            <span class="text-sm font-bold text-blue-700">{{ $activeYear->year ?? '-' }}</span>
                            </div>
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
                                                    @if($grade->memorization_score !== null)
                                                        <span class="text-[10px] text-emerald-600 block mt-1 font-medium">Hafalan: Juz {{ $grade->memorization_juz ?? '-' }}, Ayat {{ $grade->memorization_ayat ?? '-' }}</span>
                                                    @endif
                                                </div>
                                                <div class="text-right">
                                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg-lg font-bold text-xs bg-blue-50 text-blue-700">
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

                    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
                        <div class="xl:col-span-2 space-y-6">
                            <x-card padding="lg">
                                <div class="mb-5 border-b border-slate-100 pb-4">
                                    <h4 class="text-base font-bold text-slate-900">Umpan Balik & Rencana Aksi Waka</h4>
                                    <p class="mt-1 text-sm text-slate-500">Catatan ini tersimpan untuk {{ $selectedWeek }} pada semester aktif.</p>
                                </div>
                                <form action="{{ route('waka.monitoring.store-collaborative-action') }}" method="POST" class="space-y-5">
                                    @csrf
                                    <input type="hidden" name="assessment_id" value="{{ $studentGrades->first()->id ?? 0 }}">
                                    <input type="hidden" name="role_type" value="waka">
                                    <input type="hidden" name="week_number" value="{{ $selectedWeek }}">
                                    <div>
                                        <x-input-label for="feedback_content" value="Umpan balik Waka" class="mb-1.5" />
                                        <x-textarea id="feedback_content" name="feedback_content" rows="4" class="w-full">{{ old('feedback_content', \App\Models\CollaborativeAction::where(['assessment_id' => $studentGrades->first()->id ?? 0, 'role_type' => 'waka', 'week_number' => $selectedWeek])->first()?->feedback_content ?? '') }}</x-textarea>
                                    </div>
                                    <div>
                                        <x-input-label for="action_plan" value="Rencana aksi" class="mb-1.5" />
                                        <x-textarea id="action_plan" name="action_plan" rows="4" class="w-full">{{ old('action_plan', \App\Models\CollaborativeAction::where(['assessment_id' => $studentGrades->first()->id ?? 0, 'role_type' => 'waka', 'week_number' => $selectedWeek])->first()?->action_plan ?? '') }}</x-textarea>
                                    </div>
                                    <div class="flex justify-end"><x-primary-button>Simpan Catatan Waka</x-primary-button></div>
                                </form>
                            </x-card>

                            <x-card padding="lg">
                                <h4 class="text-base font-bold text-slate-900 mb-4">Tabel Aksi Kolaboratif</h4>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm border-collapse">
                                        <thead>
                                            <tr class="text-slate-500 border-b">
                                                <th class="pb-3 text-left w-32">Pihak</th>
                                                <th class="pb-3 text-left">Feedback</th>
                                                <th class="pb-3 text-left">Rencana Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y">
                                            @foreach(['guru', 'ortu', 'waka', 'pengawas'] as $role)
                                            @php $action = \App\Models\CollaborativeAction::where(['assessment_id' => $studentGrades->first()->id ?? 0, 'role_type' => $role, 'week_number' => $selectedWeek])->first(); @endphp
                                            <tr>
                                                <td class="py-4 font-bold text-slate-700 capitalize">{{ $role }}</td>
                                                <td class="py-4 text-slate-600">{{ $action->feedback_content ?? '-' }}</td>
                                                <td class="py-4 text-slate-600">{{ $action->action_plan ?? '-' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </x-card>
                        </div>

                        <div class="space-y-6">
                            <x-card padding="lg">
                                <h4 class="text-sm font-bold text-slate-900">Dukungan Orang Tua</h4>
                                @if($parentSupport)
                                    <div class="mt-4 space-y-3 text-sm">
                                        <div><p class="text-xs font-semibold uppercase text-slate-400">Dukungan</p><p class="mt-1 whitespace-pre-wrap text-slate-700">{{ $parentSupport->support_description }}</p></div>
                                        @if($parentSupport->general_feedback)<div><p class="text-xs font-semibold uppercase text-slate-400">Feedback</p><p class="mt-1 whitespace-pre-wrap text-slate-700">{{ $parentSupport->general_feedback }}</p></div>@endif
                                        @if($parentSupport->action_plan)<div><p class="text-xs font-semibold uppercase text-slate-400">Rencana Aksi</p><p class="mt-1 whitespace-pre-wrap text-slate-700">{{ $parentSupport->action_plan }}</p></div>@endif
                                    </div>
                                @else
                                    <p class="mt-3 text-sm text-slate-500">Belum ada dukungan atau masukan orang tua untuk minggu ini.</p>
                                @endif
                            </x-card>

                            <x-card padding="lg">
                                <h4 class="text-sm font-bold text-slate-900">Feedback Guru</h4>
                                <div class="mt-4 space-y-3">
                                    @forelse($teacherFeedbacks as $teacherFeedback)
                                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                            <div class="flex items-center justify-between gap-2"><p class="text-xs font-bold text-slate-800">{{ $teacherFeedback->title }}</p><x-badge variant="{{ $teacherFeedback->type === 'positive' ? 'success' : ($teacherFeedback->type === 'negative' ? 'danger' : 'slate') }}">{{ $teacherFeedback->type_label }}</x-badge></div>
                                            <p class="mt-2 text-xs text-slate-600">{{ $teacherFeedback->message }}</p>
                                            <p class="mt-2 text-[11px] text-slate-400">{{ $teacherFeedback->teacher->user->name ?? '-' }} · {{ $teacherFeedback->subject->name ?? '-' }}</p>
                                        </div>
                                    @empty
                                        <p class="text-sm text-slate-500">Belum ada feedback guru.</p>
                                    @endforelse
                                </div>
                            </x-card>
                        </div>
                    </div>
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
                const classAverage = {{ $classAverage ?? 0 }};

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: subjectNames,
                        datasets: [
                            {
                                label: 'Rata-Rata Kelas',
                                data: Array(subjectNames.length).fill(classAverage),
                                borderColor: '#ef4444',
                                borderDash: [5, 5],
                                borderWidth: 2,
                                pointRadius: 0,
                                fill: false
                            },
                            {
                                label: 'Tes Awal',
                                data: preTestScores,
                                borderColor: '#123B82',
                                borderWidth: 2,
                                tension: 0.2
                            },
                            {
                                label: 'Tugas',
                                data: assignmentScores,
                                borderColor: '#119FEA',
                                borderWidth: 2,
                                tension: 0.2
                            },
                            {
                                label: 'Tes Akhir',
                                data: postTestScores,
                                borderColor: '#0EA5E9',
                                borderWidth: 2,
                                tension: 0.2
                            },
                            {
                                label: 'Karakter',
                                data: characterScores,
                                borderColor: '#475569',
                                borderWidth: 2,
                                tension: 0.2
                            },
                            {
                                label: 'Hafalan',
                                data: memorizationScores,
                                borderColor: '#94A3B8',
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
