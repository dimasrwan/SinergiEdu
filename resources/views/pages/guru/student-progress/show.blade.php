<x-layouts.app title="Detail Perkembangan Siswa">

<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('guru.student-progress.index') }}" class="inline-flex items-center justify-center rounded-lg-lg bg-white p-2 text-slate-400 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 hover:text-slate-500">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Detail Perkembangan Siswa</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $classroom->name }} &bull; {{ $subject->name }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Student Profile Card -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-lg bg-blue-100 text-xl font-bold text-blue-700">
                    {{ strtoupper(substr($student->user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">{{ $student->user->name }}</h2>
                    <p class="text-sm text-slate-500">NIS: {{ $student->nis }}</p>
                </div>
            </div>
            
            <div class="mt-6 border-t border-slate-100 pt-6">
                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-500">Total Tugas</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ $assignments->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-500">Tugas Diselesaikan</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ $assignments->filter(fn($a) => $a->submissions->isNotEmpty())->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-500">Rata-Rata Nilai</dt>
                        <dd class="text-sm font-bold {{ $avgScore >= 75 ? 'text-green-600' : 'text-orange-600' }}">
                            {{ $avgScore !== null ? $avgScore : 'Belum Ada' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Trend Chart -->
        <div class="lg:col-span-2 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold leading-6 text-slate-900 mb-4">Tren Nilai Tugas</h3>
            
            @php
                $gradedSubmissions = $assignments->flatMap->submissions->filter(fn($s) => $s->score !== null)->sortBy('created_at');
            @endphp
            
            @if($gradedSubmissions->count() >= 2)
                <div class="h-64 w-full">
                    <canvas id="trendChart"></canvas>
                </div>
                @push('scripts')
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    const ctx = document.getElementById('trendChart');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: {!! json_encode($gradedSubmissions->map(fn($s) => $s->assignment->title)->values()) !!},
                            datasets: [{
                                label: 'Nilai',
                                data: {!! json_encode($gradedSubmissions->map(fn($s) => $s->score)->values()) !!},
                                borderColor: '#2563eb',
                                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3
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
                </script>
                @endpush
            @else
                <div class="flex h-64 items-center justify-center rounded-lg-lg border-2 border-dashed border-slate-200 bg-slate-50">
                    <div class="text-center">
                        <svg class="mx-auto h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900">Belum cukup data</h3>
                        <p class="mt-1 text-sm text-slate-500">Minimal 2 tugas dinilai untuk menampilkan tren.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Assignments History -->
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 p-4 sm:p-6">
            <h3 class="text-base font-semibold leading-6 text-slate-900">Riwayat Penugasan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-semibold text-slate-900 sm:pl-6">Tugas</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900">Batas Waktu</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900">Status</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900">Nilai</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900">Feedback</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($assignments as $assignment)
                        @php
                            $submission = $assignment->submissions->first();
                        @endphp
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 sm:pl-6">
                                <div class="font-medium text-slate-900">{{ $assignment->title }}</div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                {{ $assignment->deadline->format('d M Y, H:i') }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                @if($submission)
                                    <span class="inline-flex items-center rounded-lg bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Selesai</span>
                                @else
                                    @if(now()->gt($assignment->deadline))
                                        <span class="inline-flex items-center rounded-lg bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">Terlewat</span>
                                    @else
                                        <span class="inline-flex items-center rounded-lg bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">Belum</span>
                                    @endif
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                @if($submission && $submission->score !== null)
                                    <span class="font-bold text-slate-900">{{ $submission->score }}</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-3 py-4 text-sm text-slate-500 max-w-xs truncate">
                                @if($submission && $submission->feedback)
                                    <span title="{{ $submission->feedback }}">{{ $submission->feedback }}</span>
                                @else
                                    <span class="text-slate-400 italic">Tidak ada</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                Belum ada tugas untuk dianalisis.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-layouts.app>
