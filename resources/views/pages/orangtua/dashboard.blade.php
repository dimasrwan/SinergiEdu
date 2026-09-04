<x-layouts.app>
    <x-slot:title>Portal Orang Tua</x-slot:title>

    <div class="w-full space-y-8">
        
        <!-- Child Selector (Jika Anak > 1) -->
        @if($children->count() > 1)
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row items-center gap-4">
            <span class="text-sm font-semibold text-slate-700">Pilih Anak:</span>
            <form action="{{ route('orangtua.dashboard') }}" method="GET" class="shrink-0 flex items-center w-full sm:w-auto">
                <div class="bg-slate-50 border border-slate-200 rounded-xl flex items-center p-1 relative shadow-sm overflow-hidden w-full sm:w-auto">
                    <select name="student_id" onchange="this.form.submit()" class="bg-transparent border-none text-sm font-semibold text-slate-700 focus:ring-0 pl-3 pr-8 py-1.5 cursor-pointer outline-none appearance-none z-10 w-full sm:w-48">
                        @foreach($children as $child)
                            <option value="{{ $child->id }}" {{ (int)$selectedStudentId === $child->id ? 'selected' : '' }}>
                                {{ $child->user->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
            </form>
        </div>
        @endif

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

        <!-- Student Profile Snapshot Banner -->
        <div class="bg-primary rounded-3xl p-8 text-white shadow-xl shadow-primary/20 relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-2xl bg-white/10 border-2 border-white/20 flex items-center justify-center backdrop-blur-sm overflow-hidden shrink-0">
                        <svg class="h-12 w-12 text-blue-100" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight mb-1">{{ $selectedStudent->user->name }} (Anak Anda)</h1>
                        <p class="text-slate-300 text-sm mb-3">
                            @if($classroom)
                                Kelas {{ $classroom->name }}
                            @else
                                Belum Ada Kelas Aktif
                            @endif
                            @if($selectedStudent->nisn)
                                • NISN: {{ $selectedStudent->nisn }}
                            @elseif($selectedStudent->nis)
                                • NIS: {{ $selectedStudent->nis }}
                            @endif
                        </p>
                        <div class="flex items-center gap-2 text-xs font-medium bg-white/10 text-white border border-white/20 rounded-lg px-3 py-1 w-max">
                            @if($activeYear && $activeSemester)
                                TA {{ $activeYear->year }} - {{ $activeSemester->name }}
                            @else
                                Tidak Ada Periode Aktif
                            @endif
                        </div>
                    </div>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Vitals & Activity -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Academic Vitals (Simple Trends) -->
                <section>
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Ringkasan Akademik</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-card padding="md" class="relative overflow-hidden">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Rata-rata Nilai</p>
                                    <h3 class="text-3xl font-bold {{ $stats['rata_nilai'] !== null ? 'text-slate-900' : 'text-slate-400' }}">
                                        {{ $stats['rata_nilai'] !== null ? $stats['rata_nilai'] : 'N/A' }}
                                    </h3>
                                </div>
                            </div>
                            <p class="text-sm text-slate-600">
                                @if($stats['rata_nilai'] !== null)
                                    Rata-rata nilai untuk seluruh mata pelajaran semester ini.
                                @else
                                    Belum ada nilai yang diinputkan guru semester ini.
                                @endif
                            </p>
                            <a href="{{ route('orangtua.grades.index', ['student_id' => $selectedStudentId]) }}" class="mt-4 inline-block text-sm font-semibold text-accent hover:underline">Lihat Rincian Nilai &rarr;</a>
                        </x-card>

                        <x-card padding="md">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Penyelesaian Tugas</p>
                                    <h3 class="text-3xl font-bold text-slate-900">{{ $stats['tugas_selesai'] }}<span class="text-lg text-slate-400">/{{ $stats['tugas_selesai'] + $stats['tugas_aktif'] + $stats['menunggu_penilaian'] }}</span></h3>
                                </div>
                            </div>
                            <p class="text-sm text-slate-600">Ada <span class="font-bold text-slate-900">{{ $stats['tugas_aktif'] }} tugas</span> yang belum dikerjakan. <span class="font-bold text-slate-900">{{ $stats['menunggu_penilaian'] }} tugas</span> sedang menunggu penilaian.</p>
                            <a href="{{ route('orangtua.assignments.index', ['student_id' => $selectedStudentId]) }}" class="mt-4 inline-block text-sm font-semibold text-accent hover:underline">Lihat Daftar Tugas &rarr;</a>
                        </x-card>
                    </div>
                </section>

                <!-- Tugas Terdekat -->
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-slate-900">Tugas Terdekat</h2>
                        <a href="{{ route('orangtua.assignments.index', ['student_id' => $selectedStudentId]) }}" class="text-sm font-medium text-accent hover:text-accent-hover">Lihat Semua</a>
                    </div>
                    
                    @if($upcomingAssignments->isEmpty())
                        <div class="bg-white border border-slate-200 rounded-2xl p-6 text-center shadow-sm">
                            <p class="text-sm text-slate-500">Belum ada tugas yang diberikan oleh guru.</p>
                        </div>
                    @else
                        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                            <ul class="divide-y divide-slate-100">
                                @foreach($upcomingAssignments as $assignment)
                                    @php
                                        $submission = $assignment->submissions->first();
                                        $statusText = 'Belum Dikerjakan';
                                        $statusClass = 'bg-slate-100 text-slate-600';
                                        
                                        if ($submission) {
                                            if ($submission->status === 'submitted' || $submission->status === 'late') {
                                                $statusText = 'Menunggu Penilaian';
                                                $statusClass = 'bg-amber-100 text-amber-700';
                                            } elseif ($submission->status === 'graded') {
                                                $statusText = 'Dinilai: ' . $submission->score;
                                                $statusClass = 'bg-green-100 text-green-700';
                                            }
                                        } elseif ($assignment->due_date && $assignment->due_date < now()) {
                                            $statusText = 'Terlambat';
                                            $statusClass = 'bg-red-100 text-red-700';
                                        }
                                    @endphp
                                    <li class="p-4 sm:p-5 hover:bg-slate-50 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                        <div>
                                            <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-bold bg-blue-50 text-primary uppercase tracking-wider mb-2">
                                                {{ $assignment->subject->name ?? '-' }}
                                            </span>
                                            <h4 class="font-bold text-slate-900 text-sm mb-1">{{ $assignment->title }}</h4>
                                            @if($assignment->due_date)
                                                <p class="text-xs text-slate-500">Tenggat: {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y, H:i') }}</p>
                                            @endif
                                        </div>
                                        <div class="shrink-0">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $statusClass }}">
                                                {{ $statusText }}
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </section>
                
                <!-- Nilai Terbaru -->
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-slate-900">Nilai Terbaru</h2>
                        <a href="{{ route('orangtua.grades.index', ['student_id' => $selectedStudentId]) }}" class="text-sm font-medium text-accent hover:text-accent-hover">Lihat Semua</a>
                    </div>
                    
                    @if($recentGrades->isEmpty())
                        <div class="bg-white border border-slate-200 rounded-2xl p-6 text-center shadow-sm">
                            <p class="text-sm text-slate-500">Belum ada nilai yang dipublikasikan oleh guru.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            @foreach($recentGrades as $grade)
                                @php $avg = $grade->average_score; @endphp
                                <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm flex flex-col items-center justify-center text-center">
                                    <span class="text-xs font-semibold text-slate-500 mb-2 truncate w-full">{{ $grade->subject->name ?? '-' }}</span>
                                    <div class="flex items-center justify-center h-12 w-12 rounded-lg {{ $avg >= 80 ? 'bg-emerald-50 text-emerald-600' : ($avg >= 60 ? 'bg-amber-50 text-amber-600' : ($avg > 0 ? 'bg-red-50 text-red-600' : 'bg-slate-50 text-slate-500')) }}">
                                        <span class="text-lg font-bold">{{ $avg > 0 ? $avg : '-' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>
                
            </div>

            <!-- Right Column: Teacher Communication -->
            <div class="col-span-1 space-y-8">
                
                <!-- Feedback Terbaru -->
                <section>
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Catatan & Masukan Guru</h2>
                    
                    @if($recentFeedbacks->isEmpty())
                        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm text-center">
                            <p class="text-sm text-slate-500">Belum ada feedback dari guru.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($recentFeedbacks as $feedback)
                                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm relative">
                                    <div class="flex items-center gap-3 mb-4 border-b border-slate-100 pb-4">
                                        <div class="h-10 w-10 bg-slate-100 rounded-full flex items-center justify-center font-bold text-slate-500 shrink-0">
                                            {{ strtoupper(substr($feedback->teacher->user->name ?? 'G', 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-slate-900 text-sm truncate">{{ $feedback->teacher->user->name ?? '-' }}</h4>
                                            <p class="text-xs text-slate-500 truncate">{{ $feedback->subject->name ?? 'Wali Kelas' }}</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-slate-700 italic mb-4 line-clamp-3">"{{ $feedback->message }}"</p>
                                    
                                    <p class="text-xs text-slate-400">{{ $feedback->created_at->diffForHumans() }}</p>
                                </div>
                            @endforeach
                            <a href="{{ route('orangtua.feedbacks.index', ['student_id' => $selectedStudentId]) }}" class="block text-center text-sm font-semibold text-accent hover:underline mt-2">Lihat Semua Feedback</a>
                        </div>
                    @endif
                </section>

                <section>
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-center">
                        <div class="bg-white p-3 rounded-xl inline-block shadow-sm mb-3 text-accent">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                        </div>
                        <h3 class="font-bold text-slate-900 mb-2">Dukungan Belajar</h3>
                        <p class="text-sm text-slate-600 mb-4">Berikan umpan balik atau rencana aksi Anda terhadap perkembangan anak.</p>
                        <a href="{{ route('orangtua.support.index', ['student_id' => $selectedStudentId]) }}" class="text-sm font-semibold text-accent hover:underline">Tulis Dukungan</a>
                    </div>
                </section>

            </div>
        </div>
        @endif
        
        @endif
    </div>
</x-layouts.app>
