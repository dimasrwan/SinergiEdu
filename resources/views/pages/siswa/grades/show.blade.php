<x-layouts.app>
    <x-slot:title>Detail Nilai: {{ $grade->subject->name ?? '-' }}</x-slot:title>

    <div class="space-y-6">
        <div class="mb-4">
            <a href="{{ route('siswa.grades.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition mb-3">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Rekap Nilai
            </a>
        </div>

        <!-- Grade Header -->
        <x-card padding="lg" class="mb-6">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-950">{{ $grade->subject->name ?? '-' }}</h1>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Guru Pengampu: <span class="text-slate-800 font-semibold">{{ $grade->teacher->user->name ?? '-' }}</span></p>
                </div>
                <div class="flex items-center gap-3 bg-slate-50 px-4 py-3 rounded-2xl border border-slate-100">
                    <div class="text-right">
                        <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wide">Rata-rata Mapel</span>
                        <span class="text-xl font-bold text-slate-900">{{ $grade->average_score > 0 ? $grade->average_score : '-' }}</span>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-6 border-t border-slate-100">
                <div>
                    <span class="block text-xs font-medium text-slate-500 mb-1">Kelas</span>
                    <span class="text-sm font-semibold text-slate-900">{{ $grade->classroom->name ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-medium text-slate-500 mb-1">Tahun Ajaran</span>
                    <span class="text-sm font-semibold text-slate-900">{{ $grade->academicYear->year ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-medium text-slate-500 mb-1">Semester</span>
                    <span class="text-sm font-semibold text-slate-900">{{ $grade->semester->name ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-medium text-slate-500 mb-1">Tugas Dinilai</span>
                    <span class="text-sm font-semibold text-slate-900">
                        {{ $assignments->filter(fn($a) => $a->submissions->first()?->score !== null)->count() }} dari {{ $assignments->count() }}
                    </span>
                </div>
            </div>
        </x-card>

        <!-- Detailed Assignments List -->
        <h2 class="text-lg font-bold text-slate-900 mb-4 px-1">Rincian Tugas</h2>

        @if($assignments->isEmpty())
            <x-card padding="lg" class="text-center py-16">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Belum Ada Tugas</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Belum ada tugas pada mata pelajaran ini.</p>
            </x-card>
        @else
            <div class="space-y-4">
                @foreach($assignments as $assignment)
                    @php
                        $submission = $assignment->submissions->first();
                        $status = 'Belum Mengumpulkan';
                        $statusColor = 'bg-slate-100 text-slate-700';
                        $badgeColor = 'bg-slate-200';
                        
                        if ($submission) {
                            if ($submission->score !== null) {
                                $status = 'Dinilai';
                                $statusColor = 'bg-emerald-50 text-emerald-700 border border-emerald-100';
                                $badgeColor = 'bg-emerald-500';
                            } else {
                                $status = 'Menunggu Penilaian';
                                $statusColor = 'bg-amber-50 text-amber-700 border border-amber-100';
                                $badgeColor = 'bg-amber-500';
                            }
                        }
                    @endphp
                    <x-card padding="none" class="overflow-hidden">
                        <div class="p-5 md:p-6 flex flex-col md:flex-row gap-6">
                            <!-- Left: Assignment Info -->
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-base md:text-lg font-bold text-slate-900">{{ $assignment->title }}</h3>
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $statusColor }}">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $badgeColor }}"></div>
                                        {{ $status }}
                                    </div>
                                </div>
                                <p class="text-sm text-slate-500 line-clamp-2 mb-4">{{ $assignment->description }}</p>
                                
                                <a href="{{ route('siswa.assignments.show', $assignment) }}" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
                                    Buka Halaman Tugas
                                    <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                            </div>

                            <!-- Right: Grade & Feedback -->
                            <div class="md:w-1/3 flex flex-col justify-center border-t md:border-t-0 md:border-l border-slate-100 pt-4 md:pt-0 md:pl-6">
                                <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Nilai Tugas</span>
                                <div class="mb-4">
                                    @if($submission && $submission->score !== null)
                                        <span class="text-3xl font-bold text-slate-900">{{ $submission->score }}<span class="text-sm font-medium text-slate-400">/100</span></span>
                                    @else
                                        <span class="text-sm font-medium text-slate-500 italic">Belum ada nilai</span>
                                    @endif
                                </div>

                                <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Feedback Guru</span>
                                <div class="text-sm text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100 line-clamp-3" title="{{ $submission->feedback ?? '' }}">
                                    @if($submission && $submission->feedback)
                                        {{ $submission->feedback }}
                                    @else
                                        <span class="italic text-slate-400">Belum ada feedback.</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </x-card>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
