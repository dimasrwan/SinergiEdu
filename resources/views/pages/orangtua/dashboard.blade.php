<x-layouts.app>
    <x-slot:title>Portal Orang Tua</x-slot:title>

    <div class="w-full space-y-6">
        
        <!-- Child Profile Snapshot Banner & Selector -->
        @if(!$selectedStudent)
        <div class="bg-slate-50 border border-slate-200/75 rounded-2xl py-12 px-6 flex flex-col items-center text-center max-w-2xl mx-auto shadow-2xs">
            <div class="h-16 w-16 bg-blue-50 border border-blue-100 text-primary rounded-full flex items-center justify-center mb-4 shadow-2xs">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Anak Terdaftar</h3>
            <p class="text-sm text-slate-500 font-medium">Anda belum memiliki anak yang terdaftar pada sistem sekolah ini.</p>
        </div>
        @else

        <!-- 1. Child Context / Profile Header (With SinergiEdu Blue Accent) -->
        <div class="bg-gradient-to-r from-blue-50/70 via-blue-50/30 to-white border border-blue-100 border-l-4 border-l-primary rounded-2xl px-5 py-4 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Profile Info -->
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-primary text-white flex items-center justify-center shadow-xs shrink-0 font-bold text-base">
                    {{ strtoupper(substr($selectedStudent->user->name, 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-[17px] font-bold text-slate-900 leading-tight">{{ $selectedStudent->user->name }}</h1>
                    <p class="text-slate-500 text-[12px] font-medium mt-0.5">
                        @if($classroom)
                            <span class="inline-flex items-center gap-1 text-primary font-bold">Kelas {{ $classroom->name }}</span>
                        @else
                            Belum Ada Kelas Aktif
                        @endif
                    </p>
                </div>
            </div>

            <!-- Selector & Periode -->
            <div class="flex flex-col sm:items-end gap-1.5 w-full sm:w-auto border-t sm:border-t-0 border-slate-100 pt-3 sm:pt-0">
                @if($children->count() > 1)
                <form action="{{ route('orangtua.dashboard') }}" method="GET" class="w-full sm:w-56">
                    <x-select name="student_id" onchange="this.form.submit()" :selected="$selectedStudentId" :options="$children->map(fn($c) => ['value' => $c->id, 'label' => $c->user->name])->toArray()" />
                </form>
                @endif
                
                <div class="inline-flex items-center gap-1.5 text-primary text-[11px] font-bold tracking-wide sm:self-end">
                    @if($activeYear && $activeSemester)
                        <svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        <span>{{ $activeYear->year }} &bull; {{ $activeSemester->name }}</span>
                    @else
                        Tidak Ada Periode
                    @endif
                </div>
            </div>
        </div>

        @if(!$classroom || !$activeYear || !$activeSemester)
            <div class="bg-slate-50 border border-slate-200/75 rounded-2xl py-12 px-6 flex flex-col items-center text-center max-w-2xl mx-auto shadow-2xs">
                <div class="h-16 w-16 bg-blue-50 border border-blue-100 text-primary rounded-full flex items-center justify-center mb-4 shadow-2xs">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Kelas Aktif</h3>
                <p class="text-sm text-slate-500 font-medium">Anak Anda belum memiliki penempatan kelas pada periode akademik saat ini.</p>
            </div>
        @else

        <!-- 2. Main Grid (70% Left / 30% Right with 24px gap) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- LEFT / PRIMARY CONTENT (≈70% -> col-span-8) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- 3. Ringkasan Perkembangan (Exactly 2 Cards with Blue Value Accent) -->
                <section>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-1 h-3.5 bg-primary rounded-full"></span>
                        <h2 class="text-[12px] font-bold text-slate-700 uppercase tracking-wider">Ringkasan Perkembangan</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Card 1: Rata-Rata Nilai -->
                        <div class="bg-white border border-slate-200/75 rounded-2xl p-5 shadow-2xs flex flex-col justify-between h-full hover:border-blue-200/80 transition-colors">
                            <div>
                                <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Rata-Rata Nilai</h3>
                                @if($stats['rata_nilai'] !== null)
                                    <div class="text-3xl font-bold text-primary mb-1 leading-none">{{ $stats['rata_nilai'] }}</div>
                                @else
                                    <div class="text-2xl font-bold text-slate-400 mb-1 leading-none">N/A</div>
                                @endif
                            </div>
                            <p class="text-[12px] text-slate-500 font-medium mt-3 pt-3 border-t border-slate-100">Dari seluruh mata pelajaran aktif.</p>
                        </div>

                        <!-- Card 2: Penyelesaian Tugas -->
                        <div class="bg-white border border-slate-200/75 rounded-2xl p-5 shadow-2xs flex flex-col justify-between h-full hover:border-blue-200/80 transition-colors">
                            <div>
                                <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Penyelesaian Tugas</h3>
                                <div class="flex items-baseline gap-1 mb-1 leading-none">
                                    <span class="text-3xl font-bold text-primary">{{ $stats['tugas_selesai'] }}</span>
                                    <span class="text-lg font-semibold text-slate-400">/ {{ $stats['tugas_selesai'] + $stats['tugas_aktif'] + $stats['menunggu_penilaian'] }}</span>
                                </div>
                            </div>
                            <p class="text-[12px] text-slate-500 font-medium mt-3 pt-3 border-t border-slate-100">
                                @if($stats['tugas_aktif'] > 0)
                                    <span class="text-amber-600 font-semibold">{{ $stats['tugas_aktif'] }} tugas</span> belum dikerjakan.
                                @else
                                    Semua tugas telah diselesaikan.
                                @endif
                            </p>
                        </div>
                    </div>
                </section>

                <!-- 5. Tugas Terdekat -->
                <section>
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-1 h-3.5 bg-primary rounded-full"></span>
                            <h2 class="text-[12px] font-bold text-slate-700 uppercase tracking-wider">Tugas Terdekat</h2>
                        </div>
                        <a href="{{ route('orangtua.assignments.index', ['student_id' => $selectedStudentId]) }}" class="text-[12px] font-bold text-primary hover:text-blue-700 transition">Lihat Semua &rarr;</a>
                    </div>
                    
                    @if($upcomingAssignments->isEmpty())
                        <div class="text-center py-8 bg-white rounded-2xl border border-slate-200/75 shadow-2xs">
                            <p class="text-[12px] text-slate-500 font-medium">Saat ini tidak ada tugas mendatang.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($upcomingAssignments->take(3) as $assignment)
                                @php
                                    $submission = $assignment->submissions->first();
                                    $statusText = 'Belum Dikerjakan';
                                    $statusClass = 'text-slate-600 bg-slate-50 border-slate-200/60';
                                    
                                    if ($submission) {
                                        if ($submission->status === 'submitted' || $submission->status === 'late') {
                                            $statusText = 'Menunggu Penilaian';
                                            $statusClass = 'text-blue-700 bg-blue-50 border-blue-200/50';
                                        } elseif ($submission->status === 'graded') {
                                            $statusText = 'Dinilai: ' . $submission->score;
                                            $statusClass = 'text-emerald-700 bg-emerald-50 border-emerald-200/50';
                                        }
                                    } elseif ($assignment->due_date && $assignment->due_date < now()) {
                                        $statusText = 'Terlambat';
                                        $statusClass = 'text-red-700 bg-red-50 border-red-200/50';
                                    }
                                @endphp
                                <a href="{{ route('orangtua.assignments.index', ['student_id' => $selectedStudentId]) }}" class="flex items-center justify-between bg-white border border-slate-200/75 rounded-2xl p-4 shadow-2xs hover:border-blue-300 hover:bg-blue-50/20 transition group">
                                    <div class="min-w-0 pr-4">
                                        <h4 class="font-bold text-slate-900 text-[14px] group-hover:text-primary transition-colors truncate mb-1">{{ $assignment->title }}</h4>
                                        <p class="text-[12px] text-slate-500 font-medium">
                                            <span class="font-bold text-primary uppercase tracking-wide">{{ $assignment->subject->name ?? '-' }}</span>
                                            @if($assignment->due_date)
                                                <span class="mx-1.5 text-slate-300">&bull;</span>
                                                Deadline: {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y') }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider border {{ $statusClass }}">
                                        {{ $statusText }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>

            <!-- RIGHT / SUPPORTING CONTENT (≈30% -> col-span-4) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- 4. Catatan Guru -->
                <section>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-1 h-3.5 bg-primary rounded-full"></span>
                        <h2 class="text-[12px] font-bold text-slate-700 uppercase tracking-wider">Catatan Guru</h2>
                    </div>
                    
                    @if($recentFeedbacks->isEmpty())
                        <div class="bg-white border border-slate-200/75 rounded-2xl p-5 shadow-2xs text-center py-6">
                            <p class="text-[12px] text-slate-500 font-medium">Belum ada catatan atau masukan.</p>
                        </div>
                    @else
                        <div>
                            @foreach($recentFeedbacks->take(1) as $feedback)
                                <div class="bg-white border border-slate-200/75 rounded-2xl p-5 shadow-2xs flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="w-9 h-9 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-[11px] font-bold text-primary shrink-0">
                                                {{ strtoupper(substr($feedback->teacher->user->name ?? 'G', 0, 2)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <h4 class="font-bold text-slate-900 text-[13px] leading-tight truncate">{{ $feedback->teacher->user->name ?? '-' }}</h4>
                                                <p class="text-[11px] font-semibold text-primary/80 truncate">{{ $feedback->subject->name ?? 'Wali Kelas' }}</p>
                                            </div>
                                        </div>
                                        <p class="text-[13px] text-slate-700 italic line-clamp-4 leading-relaxed font-medium mb-3">"{{ $feedback->message }}"</p>
                                    </div>
                                    <div class="text-[11px] font-medium text-slate-400 border-t border-slate-100 pt-2.5">
                                        {{ $feedback->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            @endforeach
                            <div class="mt-3">
                                <a href="{{ route('orangtua.feedbacks.index', ['student_id' => $selectedStudentId]) }}" class="inline-flex items-center text-[12px] font-bold text-primary hover:text-blue-700 transition">
                                    Lihat Semua Feedback &rarr;
                                </a>
                            </div>
                        </div>
                    @endif
                </section>

                <!-- 6. Dukungan Belajar Anak -->
                <section class="bg-white border border-slate-200/75 rounded-2xl p-5 shadow-2xs">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-1 h-3 bg-primary rounded-full"></span>
                        <h3 class="text-[12px] font-bold text-slate-900">Dukungan Belajar Anak</h3>
                    </div>
                    <p class="text-[12px] text-slate-500 font-medium mb-3 leading-relaxed">Catat dukungan yang Anda berikan minggu ini.</p>
                    <a href="{{ route('orangtua.support.index', ['student_id' => $selectedStudentId]) }}" class="inline-flex items-center text-[12px] font-bold text-primary hover:text-blue-700 transition">
                        Tulis Dukungan &rarr;
                    </a>
                </section>

            </div>
        </div>
        @endif
        
        @endif
    </div>
</x-layouts.app>
