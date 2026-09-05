<x-layouts.app>
    <x-slot:title>Portal Orang Tua</x-slot:title>

    <div class="w-full space-y-8">
        
        <!-- Child Profile Snapshot Banner & Selector -->
        @if(!$selectedStudent)
        <div class="bg-slate-50 border border-slate-200 rounded-2xl py-12 px-6 flex flex-col items-center text-center max-w-2xl mx-auto">
            <div class="h-16 w-16 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Anak Terdaftar</h3>
            <p class="text-sm text-slate-500">Anda belum memiliki anak yang terdaftar pada sistem sekolah ini.</p>
        </div>
        @else

        <div class="bg-white border border-slate-200/75 rounded-2xl p-5 md:p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-5">
            <!-- Profile Info -->
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-slate-50 border border-slate-200 text-primary flex items-center justify-center shadow-sm shrink-0 font-bold text-lg">
                    {{ strtoupper(substr($selectedStudent->user->name, 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-[17px] font-bold text-slate-900 leading-tight">{{ $selectedStudent->user->name }}</h1>
                    <p class="text-slate-500 text-[12px] font-medium mt-0.5">
                        @if($classroom)
                            Kelas {{ $classroom->name }}
                        @else
                            Belum Ada Kelas Aktif
                        @endif
                    </p>
                </div>
            </div>

            <!-- Selector & Periode -->
            <div class="flex flex-col md:items-end gap-3 w-full md:w-auto border-t md:border-t-0 border-slate-100 pt-4 md:pt-0">
                @if($children->count() > 1)
                <form action="{{ route('orangtua.dashboard') }}" method="GET" class="w-full md:w-56">
                    <x-select name="student_id" onchange="this.form.submit()" :selected="$selectedStudentId" :options="$children->map(fn($c) => ['value' => $c->id, 'label' => $c->user->name])->toArray()" />
                </form>
                @endif
                
                <div class="inline-flex items-center gap-1.5 text-slate-500 text-[11px] font-bold uppercase tracking-wider md:self-end">
                    @if($activeYear && $activeSemester)
                        {{ $activeYear->year }} &bull; {{ $activeSemester->name }}
                    @else
                        Tidak Ada Periode
                    @endif
                </div>
            </div>
        </div>

        @if(!$classroom || !$activeYear || !$activeSemester)
            <div class="bg-slate-50 border border-slate-200 rounded-2xl py-12 px-6 flex flex-col items-center text-center max-w-2xl mx-auto">
                <div class="h-16 w-16 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Kelas Aktif</h3>
                <p class="text-sm text-slate-500">Anak Anda belum memiliki penempatan kelas pada periode akademik saat ini.</p>
            </div>
        @else

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            
            <!-- Left Column: Vitals & Activity -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Academic Vitals (Simple Trends) -->
                <section>
                    <h2 class="text-[13px] font-bold text-slate-900 mb-3 uppercase tracking-wider">Ringkasan Perkembangan</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-white border border-slate-200/60 rounded-xl p-5 shadow-sm flex flex-col justify-between">
                            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Rata-Rata Nilai</h3>
                            @if($stats['rata_nilai'] !== null)
                                <div class="text-3xl font-bold text-slate-900 mb-1">{{ $stats['rata_nilai'] }}</div>
                            @else
                                <div class="text-2xl font-bold text-slate-300 mb-1">N/A</div>
                            @endif
                            <p class="text-[12px] text-slate-500 font-medium">Dari seluruh mata pelajaran aktif.</p>
                        </div>

                        <div class="bg-white border border-slate-200/60 rounded-xl p-5 shadow-sm flex flex-col justify-between">
                            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Penyelesaian Tugas</h3>
                            <div class="flex items-baseline gap-1.5 mb-1">
                                <span class="text-3xl font-bold text-slate-900">{{ $stats['tugas_selesai'] }}</span>
                                <span class="text-lg font-bold text-slate-400">/ {{ $stats['tugas_selesai'] + $stats['tugas_aktif'] + $stats['menunggu_penilaian'] }}</span>
                            </div>
                            <p class="text-[12px] text-slate-500 font-medium">
                                @if($stats['tugas_aktif'] > 0)
                                    <span class="text-amber-600 font-bold">{{ $stats['tugas_aktif'] }} tugas</span> belum dikerjakan.
                                @else
                                    Semua tugas telah diselesaikan.
                                @endif
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Tugas Terdekat -->
                <section>
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-[13px] font-bold text-slate-900 uppercase tracking-wider">Tugas Terdekat</h2>
                        <a href="{{ route('orangtua.assignments.index', ['student_id' => $selectedStudentId]) }}" class="text-[12px] font-bold text-primary hover:text-blue-700 transition">Lihat Semua &rarr;</a>
                    </div>
                    
                    @if($upcomingAssignments->isEmpty())
                        <div class="text-center py-6 bg-slate-50/50 rounded-xl border border-slate-100 border-dashed">
                            <p class="text-[12px] text-slate-500 font-medium">Saat ini tidak ada tugas mendatang.</p>
                        </div>
                    @else
                        <div class="flex flex-col gap-3">
                            @foreach($upcomingAssignments->take(3) as $assignment)
                                @php
                                    $submission = $assignment->submissions->first();
                                    $statusText = 'Belum Dikerjakan';
                                    $statusClass = 'text-slate-600 bg-slate-100 border-slate-200/50';
                                    
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
                                <a href="{{ route('orangtua.assignments.index', ['student_id' => $selectedStudentId]) }}" class="flex items-center justify-between bg-white border border-slate-200/60 rounded-xl p-4 shadow-sm hover:border-primary/40 transition group">
                                    <div class="min-w-0 pr-4">
                                        <h4 class="font-bold text-slate-900 text-[14px] group-hover:text-primary transition-colors truncate mb-1">{{ $assignment->title }}</h4>
                                        <p class="text-[11px] text-slate-500 font-medium">
                                            <span class="font-bold text-slate-400 uppercase">{{ $assignment->subject->name ?? '-' }}</span>
                                            @if($assignment->due_date)
                                                <span class="mx-1.5">&bull;</span>
                                                Deadline: {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y') }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="shrink-0 inline-flex items-center px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider border {{ $statusClass }}">
                                        {{ $statusText }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>

            <!-- Right Column: Teacher Communication -->
            <div class="col-span-1 space-y-8 lg:pl-2">
                
                <!-- Feedback Terbaru -->
                <section>
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-[13px] font-bold text-slate-900 uppercase tracking-wider">Catatan Guru</h2>
                    </div>
                    
                    @if($recentFeedbacks->isEmpty())
                        <div class="text-center py-6 bg-slate-50/50 rounded-xl border border-slate-100 border-dashed">
                            <p class="text-[12px] text-slate-500 font-medium">Belum ada catatan atau masukan.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($recentFeedbacks->take(1) as $feedback)
                                <div class="bg-white border border-slate-200/60 rounded-xl p-5 shadow-sm hover:border-slate-300 transition relative">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-[12px] font-bold text-slate-600 shrink-0">
                                            {{ strtoupper(substr($feedback->teacher->user->name ?? 'G', 0, 2)) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-900 text-[13px] leading-tight">{{ $feedback->teacher->user->name ?? '-' }}</h4>
                                            <p class="text-[11px] font-semibold text-slate-500">{{ $feedback->subject->name ?? 'Wali Kelas' }}</p>
                                        </div>
                                    </div>
                                    <p class="text-[13px] text-slate-700 italic line-clamp-4 leading-relaxed mb-4">"{{ $feedback->message }}"</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-[11px] font-medium text-slate-400">{{ $feedback->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @endforeach
                            <a href="{{ route('orangtua.feedbacks.index', ['student_id' => $selectedStudentId]) }}" class="block text-center text-[12px] font-bold text-primary hover:text-blue-700 transition mt-2 py-2">Lihat Semua Feedback &rarr;</a>
                        </div>
                    @endif
                </section>

                <!-- Dukungan Belajar Link -->
                <section class="mt-6 pt-6 border-t border-slate-100">
                    <div class="flex flex-col">
                        <h3 class="text-[12px] font-bold text-slate-900 mb-1">Dukungan Belajar Anak</h3>
                        <p class="text-[11px] text-slate-500 mb-3">Catat dukungan yang Anda berikan minggu ini.</p>
                        <a href="{{ route('orangtua.support.index', ['student_id' => $selectedStudentId]) }}" class="inline-flex items-center text-[12px] font-bold text-primary hover:text-blue-700 transition w-max">
                            Tulis Dukungan &rarr;
                        </a>
                    </div>
                </section>
            </div>
        </div>
        @endif
        
        @endif
    </div>
</x-layouts.app>
