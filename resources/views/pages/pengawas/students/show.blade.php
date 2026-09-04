<x-layouts.app>
    <x-slot:title>Detail Siswa - {{ $student->user?->name }}</x-slot:title>

    <div class="space-y-6">
        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('pengawas.students.index') }}" class="hover:text-slate-800 font-semibold transition flex items-center gap-1">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                Monitoring Siswa
            </a>
            <span>/</span>
            <span class="text-slate-800 font-semibold">{{ $student->user?->name }}</span>
        </div>

        {{-- Hero Card Profil Siswa --}}
        <div class="bg-gradient-to-br from-primary to-cyan-500 rounded-3xl p-6 text-white shadow-xl shadow-primary/20 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-56 h-56 bg-white/5 rounded-full blur-3xl -translate-y-1/3 translate-x-1/4"></div>
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center gap-5">
                <div class="h-20 w-20 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-3xl font-black shrink-0">
                    {{ strtoupper(substr($student->user?->name ?? '?', 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold">{{ $student->user?->name }}</h1>
                    <div class="flex flex-wrap gap-x-6 gap-y-1 mt-2 text-blue-100 text-sm">
                        <span>NIS: <strong class="text-white">{{ $student->nis ?? '-' }}</strong></span>
                        <span>NISN: <strong class="text-white">{{ $student->nisn ?? '-' }}</strong></span>
                        <span>{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                    <div class="flex flex-wrap gap-x-6 gap-y-1 mt-1 text-blue-100 text-sm">
                        <span>Orang Tua: <strong class="text-white">{{ $student->parent?->user?->name ?? '-' }}</strong></span>
                        @if($activeClassroom)
                            <span>Kelas: <strong class="text-white">{{ $activeClassroom->name }}</strong></span>
                        @endif
                    </div>
                </div>
                @if($activeYear)
                    <div class="text-right shrink-0">
                        <div class="text-xs text-blue-200">Tahun Ajaran Aktif</div>
                        <div class="text-sm font-bold">{{ $activeYear->name }}</div>
                        @if($activeSemester)
                            <div class="text-xs text-blue-200 mt-0.5">{{ $activeSemester->name }}</div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Statistik Nilai --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {{-- Rata-rata Keseluruhan --}}
            <x-card padding="md" class="border-l-4 border-l-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-semibold">Rata-rata Keseluruhan</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($stats['overall_avg'], 1) }}</p>
                        <p class="text-xs text-slate-500 mt-1">
                            Kelas: {{ number_format($classAverage, 1) }}
                            <span class="text-blue-600 font-semibold">
                                @if($stats['overall_avg'] >= $classAverage)
                                    ↑ Lebih tinggi
                                @else
                                    ↓ Lebih rendah
                                @endif
                            </span>
                        </p>
                    </div>
                    <svg class="h-12 w-12 text-blue-500/20" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
                </div>
            </x-card>

            {{-- Tes Awal --}}
            <x-card padding="md" class="border-l-4 border-l-orange-500">
                <div>
                    <p class="text-xs text-slate-500 uppercase font-semibold">Rata-rata Tes Awal</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($stats['avg_pre_test'], 1) }}</p>
                </div>
            </x-card>

            {{-- Tes Akhir --}}
            <x-card padding="md" class="border-l-4 border-l-emerald-500">
                <div>
                    <p class="text-xs text-slate-500 uppercase font-semibold">Rata-rata Tes Akhir</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($stats['avg_post_test'], 1) }}</p>
                </div>
            </x-card>

            {{-- Tugas --}}
            <x-card padding="md" class="border-l-4 border-l-yellow-500">
                <div>
                    <p class="text-xs text-slate-500 uppercase font-semibold">Rata-rata Tugas</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($stats['avg_assignment'], 1) }}</p>
                </div>
            </x-card>

            {{-- Karakter --}}
            <x-card padding="md" class="border-l-4 border-l-purple-500">
                <div>
                    <p class="text-xs text-slate-500 uppercase font-semibold">Rata-rata Karakter</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($stats['avg_character'], 1) }}</p>
                </div>
            </x-card>

            {{-- Hafalan --}}
            <x-card padding="md" class="border-l-4 border-l-pink-500">
                <div>
                    <p class="text-xs text-slate-500 uppercase font-semibold">Rata-rata Hafalan</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($stats['avg_memorization'], 1) }}</p>
                </div>
            </x-card>
        </div>

        {{-- Tabel Detail Nilai Per Mata Pelajaran --}}
        <x-card padding="none">
            <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">Nilai Per Mata Pelajaran</h2>
                <a href="{{ route('pengawas.feedback.create', ['student_id' => $student->id]) }}"
                   class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-semibold">
                    Berikan Feedback
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-3 text-left font-semibold text-slate-700">Mata Pelajaran</th>
                            <th class="px-4 py-3 text-center font-semibold text-slate-700">Guru</th>
                            <th class="px-4 py-3 text-center font-semibold text-slate-700">Tes Awal</th>
                            <th class="px-4 py-3 text-center font-semibold text-slate-700">Tugas</th>
                            <th class="px-4 py-3 text-center font-semibold text-slate-700">Tes Akhir</th>
                            <th class="px-4 py-3 text-center font-semibold text-slate-700">Karakter</th>
                            <th class="px-4 py-3 text-center font-semibold text-slate-700">Hafalan</th>
                            <th class="px-4 py-3 text-center font-semibold text-slate-700">Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($grades as $grade)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-3 font-medium text-slate-900">{{ $grade->subject?->name }}</td>
                                <td class="px-4 py-3 text-center text-xs text-slate-600">{{ $grade->teacher?->user?->name }}</td>
                                <td class="px-4 py-3 text-center text-slate-600">{{ $grade->pre_test_score ? number_format($grade->pre_test_score, 1) : '-' }}</td>
                                <td class="px-4 py-3 text-center text-slate-600">{{ $grade->assignment_score ? number_format($grade->assignment_score, 1) : '-' }}</td>
                                <td class="px-4 py-3 text-center text-slate-600">{{ $grade->post_test_score ? number_format($grade->post_test_score, 1) : '-' }}</td>
                                <td class="px-4 py-3 text-center text-slate-600">{{ $grade->character_score ? number_format($grade->character_score, 1) : '-' }}</td>
                                <td class="px-4 py-3 text-center text-slate-600">{{ $grade->memorization_score ? number_format($grade->memorization_score, 1) : '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $grade->average_score >= 80 ? 'bg-emerald-100 text-emerald-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ number_format($grade->average_score, 1) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                    <p class="text-sm">Belum ada data nilai</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        {{-- Feedback Pengawas --}}
        @if($grades->where('supervisor_feedback')->isNotEmpty())
            <x-card padding="md">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Feedback dari Pengawas</h3>
                <div class="space-y-4">
                    @foreach($grades->where('supervisor_feedback') as $grade)
                        <div class="p-4 bg-blue-50 border-l-4 border-l-blue-500 rounded">
                            <p class="text-sm font-semibold text-slate-700">{{ $grade->supervisor?->name ?? 'Pengawas' }}</p>
                            <p class="text-sm text-slate-600 mt-2">{{ $grade->supervisor_feedback }}</p>
                            @if($grade->supervisor_action_plan)
                                <div class="mt-3 p-3 bg-white rounded border border-blue-200">
                                    <p class="text-xs font-semibold text-slate-600 uppercase">Rencana Aksi:</p>
                                    <p class="text-sm text-slate-700 mt-1">{{ $grade->supervisor_action_plan }}</p>
                                    @if($grade->supervisor_priority)
                                        <span class="inline-block mt-2 px-2 py-1 text-xs font-semibold rounded
                                            {{ $grade->supervisor_priority === 'high' ? 'bg-red-100 text-red-800' : ($grade->supervisor_priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                            Prioritas: {{ ucfirst($grade->supervisor_priority) }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </x-card>
        @endif
    </div>
</x-layouts.app>
