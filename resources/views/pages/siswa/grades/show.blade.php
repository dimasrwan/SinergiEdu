<x-layouts.app>
    <x-slot:title>Detail Nilai: {{ $grade->subject->name ?? '-' }}</x-slot:title>

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="mb-4">
            <a href="{{ route('siswa.grades.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-primary gap-1.5 transition mb-3">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar Nilai
            </a>
        </div>

        <!-- Grade Header -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 shadow-sm mb-6">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900 leading-tight">{{ $grade->subject->name ?? '-' }}</h1>
                    <div class="flex items-center gap-2 text-sm text-slate-500 mt-2">
                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        <span>Guru Pengampu: <span class="text-slate-700 font-semibold">{{ $grade->teacher->user->name ?? '-' }}</span></span>
                    </div>
                </div>
                
                <div class="flex items-center gap-4 bg-slate-50 px-5 py-4 rounded-lg border border-slate-100 shrink-0">
                    <div class="text-right">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Rata-rata Nilai</span>
                        @php $avg = $grade->average_score; @endphp
                        <span class="text-2xl font-bold {{ $avg >= 80 ? 'text-emerald-600' : ($avg >= 60 ? 'text-amber-600' : ($avg > 0 ? 'text-red-600' : 'text-slate-900')) }}">
                            {{ $avg > 0 ? $avg : '-' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-slate-100">
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-3">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kelas</span>
                    <span class="text-[13px] font-semibold text-slate-900">{{ $grade->classroom->name ?? '-' }}</span>
                </div>
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-3">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tahun Ajaran</span>
                    <span class="text-[13px] font-semibold text-slate-900">{{ $grade->academicYear->year ?? '-' }}</span>
                </div>
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-3">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Semester</span>
                    <span class="text-[13px] font-semibold text-slate-900">{{ $grade->semester->name ?? '-' }}</span>
                </div>
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-3">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tugas Dinilai</span>
                    <span class="text-[13px] font-semibold text-slate-900">
                        {{ $assignments->filter(fn($a) => $a->submissions->first()?->score !== null)->count() }} dari {{ $assignments->count() }}
                    </span>
                </div>
            </div>
        </div>

        <h2 class="text-lg font-bold text-slate-900 mb-4 px-1 mt-8">Rincian Tugas & Penilaian</h2>

        @if($assignments->isEmpty())
            <div class="bg-slate-50 border border-slate-200 rounded-2xl py-12 px-6 flex flex-col items-center text-center">
                <div class="h-16 w-16 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-1">Belum Ada Tugas</h3>
                <p class="text-sm text-slate-500">Guru belum memberikan tugas untuk mata pelajaran ini.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($assignments as $assignment)
                    @php
                        $submission = $assignment->submissions->first();
                        $status = 'Belum Mengumpulkan';
                        $statusClass = 'bg-slate-100 text-slate-700';
                        $hasScore = false;
                        
                        if ($submission) {
                            if ($submission->score !== null) {
                                $status = 'Dinilai';
                                $statusClass = 'bg-emerald-50 text-emerald-700 border border-emerald-100';
                                $hasScore = true;
                            } else {
                                $status = 'Menunggu Penilaian';
                                $statusClass = 'bg-amber-50 text-amber-700 border border-amber-100';
                            }
                        } else if (now()->isAfter($assignment->deadline)) {
                            $status = 'Terlambat';
                            $statusClass = 'bg-red-50 text-red-700 border border-red-100';
                        }
                    @endphp
                    
                    <div class="bg-white border border-slate-200 rounded-2xl flex flex-col md:flex-row overflow-hidden shadow-sm hover:shadow-md transition">
                        <!-- Info Tugas -->
                        <div class="p-5 md:p-6 flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-base font-bold text-slate-900">{{ $assignment->title }}</h3>
                                <div class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $statusClass }}">
                                    {{ $status }}
                                </div>
                            </div>
                            <p class="text-[13px] text-slate-500 line-clamp-2 mb-4 leading-relaxed">{{ $assignment->description }}</p>
                            
                            <a href="{{ route('siswa.assignments.show', $assignment) }}" class="inline-flex items-center text-[13px] font-semibold text-primary hover:underline transition">
                                Buka Halaman Tugas &rarr;
                            </a>
                        </div>

                        <!-- Nilai dan Feedback -->
                        <div class="md:w-72 bg-slate-50 border-t md:border-t-0 md:border-l border-slate-200 p-5 md:p-6 flex flex-col justify-center">
                            <div class="mb-4">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nilai Tugas</span>
                                @if($hasScore)
                                    <span class="text-2xl font-bold text-slate-900">{{ $submission->score }}</span>
                                @else
                                    <span class="text-xs font-medium text-slate-500 italic">Belum dinilai</span>
                                @endif
                            </div>
                            
                            <div>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Feedback Guru</span>
                                <div class="text-[12px] text-slate-700 bg-white border border-slate-200 p-3 rounded-xl line-clamp-3" title="{{ $submission->feedback ?? '' }}">
                                    @if($hasScore && $submission->feedback)
                                        {{ $submission->feedback }}
                                    @else
                                        <span class="italic text-slate-400">Belum ada feedback.</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
