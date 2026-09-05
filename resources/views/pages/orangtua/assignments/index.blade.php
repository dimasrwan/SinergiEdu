<x-layouts.app>
    <x-slot:title>Daftar Tugas & PR Anak</x-slot:title>

    <div class="space-y-8">
        <x-page-header 
            title="Daftar Tugas Anak" 
            description="Pantau tugas awal, materi, dan PR yang diberikan guru serta status pengerjaannya." 
        />

        <!-- Child Selector -->
        <div class="bg-white border border-slate-200/75 rounded-2xl p-5 shadow-sm">
            <h2 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">Anak yang Dipantau</h2>
            <form action="{{ route('orangtua.assignments.index') }}" method="GET" class="w-full md:max-w-md">
                <x-select name="student_id" onchange="this.form.submit()" :selected="$selectedStudentId" :options="$children->map(fn($c) => ['value' => $c->id, 'label' => $c->user->name ?? 'Anak'])->toArray()" />
            </form>
        </div>

        @if($selectedStudent)
            <div class="space-y-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <x-card padding="sm" class="border border-slate-200">
                        <div class="text-sm text-slate-500 font-medium">Total Tugas</div>
                        <div class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['total_tugas'] ?? 0 }}</div>
                    </x-card>
                    <x-card padding="sm" class="border border-slate-200">
                        <div class="text-sm text-slate-500 font-medium">Sudah Dikumpulkan</div>
                        <div class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['selesai'] ?? 0 }}</div>
                    </x-card>
                    <x-card padding="sm" class="border border-slate-200">
                        <div class="text-sm text-slate-500 font-medium">Menunggu Penilaian</div>
                        <div class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['menunggu_penilaian'] ?? 0 }}</div>
                    </x-card>
                    <x-card padding="sm" class="border border-slate-200">
                        <div class="text-sm text-slate-500 font-medium">Belum Dikumpulkan</div>
                        <div class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['belum_dikumpulkan'] ?? 0 }}</div>
                    </x-card>
                </div>

                <h2 class="text-[15px] font-bold text-slate-900 uppercase tracking-wide">Daftar Tugas Anak</h2>

                <div class="grid grid-cols-1 gap-4">
                    @forelse($assignments as $assignment)
                        @php
                            $submission = $assignment->submissions->first();
                            $isDeadlinePassed = $assignment->deadline && $assignment->deadline->isPast();
                            
                            $statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">Belum Mengumpulkan</span>';
                            
                            if ($submission) {
                                $isLate = $assignment->deadline && $submission->submitted_at && \Carbon\Carbon::parse($submission->submitted_at)->gt($assignment->deadline);
                                
                                if ($submission->score !== null) {
                                    $statusBadge = '<span class="inline-flex items-center px-2 py-1 rounded border text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border-emerald-200/50">Dinilai</span>';
                                } else {
                                    $statusBadge = '<span class="inline-flex items-center px-2 py-1 rounded border text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border-blue-200/50">Menunggu Penilaian</span>';
                                }
                                
                                if ($isLate) {
                                    $statusBadge .= ' <span class="inline-flex items-center px-2 py-1 rounded border text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border-amber-200/50 ml-1.5">Terlambat</span>';
                                }
                            } elseif ($isDeadlinePassed) {
                                $statusBadge = '<span class="inline-flex items-center px-2 py-1 rounded border text-[10px] font-bold uppercase tracking-wider bg-red-50 text-red-700 border-red-200/50">Terlewat</span>';
                            } else {
                                $statusBadge = '<span class="inline-flex items-center px-2 py-1 rounded border text-[10px] font-bold uppercase tracking-wider bg-slate-50 text-slate-600 border-slate-200/50">Belum Dikerjakan</span>';
                            }
                        @endphp
                        <div class="bg-white border border-slate-200/75 rounded-2xl p-5 shadow-sm relative group hover:shadow-md transition">
                            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-3 flex-wrap">
                                        <span class="inline-block px-2 py-1 rounded-md text-[10px] font-bold bg-slate-50 border border-slate-100 text-slate-600 uppercase tracking-wider">
                                            {{ $assignment->subject->name }}
                                        </span>
                                        @if($assignment->learningMeeting)
                                            <span class="inline-block px-2 py-1 rounded-md text-[10px] font-bold bg-blue-50 border border-blue-100 text-blue-700 uppercase tracking-wider">
                                                Pertemuan {{ $assignment->learningMeeting->meeting_number }}
                                            </span>
                                        @endif
                                        @if($assignment->material)
                                            <span class="inline-block px-2 py-1 rounded-md text-[10px] font-bold bg-emerald-50 border border-emerald-100 text-emerald-700 uppercase tracking-wider">
                                                Materi: {{ $assignment->material->title }}
                                            </span>
                                        @endif
                                        {!! $statusBadge !!}
                                    </div>
                                    <h3 class="font-bold text-slate-900 text-[15px] mb-2 leading-snug">{{ $assignment->title }}</h3>
                                    <p class="text-[13px] text-slate-600 mb-4 line-clamp-2 leading-relaxed">{{ Str::limit($assignment->description, 100) }}</p>
                                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-[12px] text-slate-500 font-medium">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                                            Guru: {{ $assignment->teacher->user->name ?? '-' }}
                                        </div>
                                        @if($assignment->deadline)
                                        <div class="flex items-center gap-1.5">
                                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                            Deadline: <span class="{{ $isDeadlinePassed ? 'text-red-600 font-bold' : '' }}">{{ $assignment->deadline->format('d M Y') }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @if($submission && $submission->score)
                                    <div class="shrink-0 md:ml-4 flex flex-col items-center justify-center p-3 bg-emerald-50 text-emerald-700 rounded-xl min-w-[70px] border border-emerald-100">
                                        <span class="text-[10px] font-bold uppercase tracking-wider mb-0.5">Nilai</span>
                                        <span class="text-xl font-black">{{ $submission->score }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="bg-slate-50 border border-slate-200/75 rounded-2xl py-12 px-8 text-center shadow-sm max-w-3xl mx-auto w-full">
                            <div class="h-16 w-16 bg-white border border-slate-200 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Tugas</h3>
                            <p class="text-sm text-slate-500 font-medium">Belum ada tugas yang diberikan oleh guru untuk anak Anda pada periode ini.</p>
                        </div>
                    @endforelse
                    @if(method_exists($assignments, 'links'))
                        <div class="mt-4">{{ $assignments->links() }}</div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
