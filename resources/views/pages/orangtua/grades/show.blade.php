<x-layouts.app>
    <x-slot:title>Detail Nilai - {{ $grade->subject->name ?? '-' }}</x-slot:title>

    <div class="space-y-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('orangtua.grades.index', ['student_id' => $student->id]) }}" class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
            </a>
            <x-page-header title="Detail Nilai: {{ $grade->subject->name ?? '-' }}" description="Rincian performa nilai dan tugas anak Anda pada mata pelajaran ini." />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                <!-- Info Section -->
                <x-card padding="md" class="border border-slate-200">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <div class="text-xs text-slate-500 font-medium">Nama Anak</div>
                            <div class="text-sm font-bold text-slate-900 mt-1">{{ $student->user->name ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500 font-medium">Kelas</div>
                            <div class="text-sm font-bold text-slate-900 mt-1">{{ $studentClass->classroom->name ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500 font-medium">Guru Pengampu</div>
                            <div class="text-sm font-bold text-slate-900 mt-1">{{ $grade->teacher->user->name ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500 font-medium">Tahun / Semester</div>
                            <div class="text-sm font-bold text-slate-900 mt-1">{{ $grade->academicYear->year ?? '-' }} - {{ $grade->semester->name ?? '-' }}</div>
                        </div>
                    </div>
                </x-card>

                <!-- Tugas List -->
                <h3 class="text-lg font-bold text-slate-900">Rincian Tugas</h3>
                <div class="space-y-4">
                    @forelse($assignments as $assignment)
                        @php
                            $submission = $assignment->submissions->first();
                            $isDeadlinePassed = $assignment->deadline && $assignment->deadline->isPast();
                            
                            $statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">Belum Mengumpulkan</span>';
                            
                            if ($submission) {
                                $isLate = $assignment->deadline && $submission->submitted_at && \Carbon\Carbon::parse($submission->submitted_at)->gt($assignment->deadline);
                                
                                if ($submission->score !== null) {
                                    $statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">Dinilai</span>';
                                } else {
                                    $statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">Menunggu Penilaian</span>';
                                }
                                
                                if ($isLate) {
                                    $statusBadge .= ' <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-50 text-orange-700 border border-orange-200 ml-1">Terlambat</span>';
                                }
                            } elseif ($isDeadlinePassed) {
                                $statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">Terlewat</span>';
                            }
                        @endphp
                        <x-card padding="none" class="overflow-hidden border border-slate-200">
                            <div class="p-4 md:p-6">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="mb-2">
                                            {!! $statusBadge !!}
                                        </div>
                                        <h4 class="text-base font-bold text-slate-900 mb-1">{{ $assignment->title }}</h4>
                                        <div class="text-xs text-slate-500">
                                            Deadline: {{ $assignment->deadline ? $assignment->deadline->format('d M Y, H:i') : '-' }}
                                        </div>
                                        @if($submission && $submission->feedback)
                                            <div class="mt-3 p-3 bg-blue-50 border border-blue-100 rounded-lg">
                                                <div class="text-xs font-bold text-blue-800 mb-1">Komentar Guru:</div>
                                                <p class="text-sm text-blue-900">{{ $submission->feedback }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    @if($submission && $submission->score !== null)
                                        <div class="flex flex-col items-center justify-center p-3 bg-slate-50 rounded-xl min-w-[80px] border border-slate-200">
                                            <span class="text-[10px] font-bold uppercase text-slate-500">Nilai</span>
                                            <span class="text-xl font-black {{ $submission->score >= 80 ? 'text-emerald-600' : ($submission->score >= 60 ? 'text-amber-600' : 'text-red-600') }}">
                                                {{ $submission->score }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </x-card>
                    @empty
                        <div class="text-center bg-slate-50 border border-slate-200 py-10 rounded-xl">
                            <p class="text-slate-500">Belum ada tugas pada mata pelajaran ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Summary Sidebar -->
            <div class="space-y-6">
                <x-card padding="md" class="border border-slate-200 bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-900 mb-4 uppercase tracking-wider">Ringkasan</h3>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-slate-500">Nilai Rata-rata</span>
                        @php $avg = $grade->average_score; @endphp
                        <span class="text-lg font-bold {{ $avg >= 80 ? 'text-emerald-600' : ($avg >= 60 ? 'text-amber-600' : ($avg > 0 ? 'text-red-600' : 'text-slate-800')) }}">
                            {{ $avg > 0 ? $avg : '-' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                        <span class="text-sm text-slate-500">Tugas Dinilai</span>
                        @php
                            $totalAssignments = $assignments->count();
                            $gradedSubmissions = $assignments->filter(function($a) {
                                $sub = $a->submissions->first();
                                return $sub && $sub->score !== null;
                            })->count();
                        @endphp
                        <span class="text-sm font-bold text-slate-900">{{ $gradedSubmissions }} / {{ $totalAssignments }}</span>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.app>
