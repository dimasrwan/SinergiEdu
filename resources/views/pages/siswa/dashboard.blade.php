<x-layouts.app>
    <x-slot:title>Ruang Belajar</x-slot:title>

    <div class="w-full space-y-6">
        
        <!-- Hero Minimalist -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900 mb-2">Hai, {{ auth()->user()->name }}!</h1>
                @if(!$classroom)
                    <p class="text-slate-500 text-sm max-w-xl">Anda belum didaftarkan pada kelas aktif.<br>Hubungi admin sekolah untuk mendapatkan penempatan kelas.</p>
                @else
                    <p class="text-slate-500 text-sm max-w-xl">Selamat datang kembali di ruang belajar Anda.</p>
                    <span class="sr-only">Kamu telah menyelesaikan {{ $stats['tugas_selesai'] }} dari total {{ $stats['total_tugas'] }} tugas di {{ $classroom->name }}</span>
                @endif
            </div>
            @if($classroom && $upcomingAssignments->isNotEmpty())
                <div class="shrink-0">
                    <a href="{{ route('siswa.assignments.index') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-primary text-white hover:bg-primary/90 rounded-lg text-sm font-bold transition">
                        Lihat Tugas
                    </a>
                </div>
            @endif
        </div>

        @if(!$classroom)
            <div class="bg-slate-50 border border-slate-200 rounded-2xl py-12 px-6 flex flex-col items-center text-center max-w-2xl mx-auto mt-8">
                <div class="h-16 w-16 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Kelas Aktif</h3>
                <p class="text-sm text-slate-500">Anda belum didaftarkan pada kelas aktif pada periode akademik saat ini. Hubungi admin sekolah untuk informasi lebih lanjut.</p>
            </div>
        @else
            <!-- 4 KPI Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <!-- Tugas Aktif -->
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                    <p class="text-[13px] font-semibold text-slate-500 uppercase tracking-wide mb-1">Tugas Aktif</p>
                    <p class="text-3xl font-bold text-slate-900">{{ max(0, $stats['total_tugas'] - $stats['tugas_selesai']) }}</p>
                    <p class="text-xs text-slate-400 mt-2">Belum diselesaikan</p>
                </div>
                
                <!-- Sudah Dikumpulkan -->
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                    <p class="text-[13px] font-semibold text-slate-500 uppercase tracking-wide mb-1">Sudah Kumpul</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $stats['tugas_selesai'] }}</p>
                    <p class="text-xs text-slate-400 mt-2">Dari {{ $stats['total_tugas'] }} tugas total</p>
                </div>
                
                <!-- Progres -->
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                    <p class="text-[13px] font-semibold text-slate-500 uppercase tracking-wide mb-1">Progres Selesai</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $stats['progres'] }}% <span class="sr-only">Progres Tugas: {{ $stats['progres'] }}%</span></p>
                    <p class="text-xs text-slate-400 mt-2">Penyelesaian tugas kelas</p>
                </div>
                
                <!-- Rata-rata Nilai -->
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                    <p class="text-[13px] font-semibold text-slate-500 uppercase tracking-wide mb-1">Rata-rata Nilai</p>
                    <p class="text-3xl font-bold text-primary">{{ $stats['rata_rata'] }}</p>
                    <p class="text-xs text-slate-400 mt-2">Semester ini</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 mt-4">
                
                <div class="lg:col-span-2 space-y-8">
                    <!-- Tugas Terdekat -->
                    <section>
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-[18px] font-bold text-slate-900">Tugas Terdekat</h2>
                            <a href="{{ route('siswa.assignments.index') }}" class="text-sm font-semibold text-primary hover:underline">Lihat Semua</a>
                        </div>
                        
                        @if($upcomingAssignments->isEmpty())
                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-8 text-center">
                                <div class="inline-flex h-12 w-12 items-center justify-center rounded-lg bg-slate-200/50 mb-3 text-slate-400">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" /></svg>
                                </div>
                                <h3 class="text-[15px] font-bold text-slate-900 mb-1">Belum Ada Tugas</h3>
                                <p class="text-sm text-slate-500">Saat ini tidak ada tugas mendatang yang harus dikerjakan.</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($upcomingAssignments as $task)
                                    <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm hover:border-primary/30 transition">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-primary uppercase tracking-wider mb-1">{{ $task->subject->name ?? '-' }} &bull; {{ $classroom->name }}</p>
                                            <h3 class="text-base font-bold text-slate-900 mb-1 truncate">{{ $task->title }}</h3>
                                            <div class="flex items-center text-sm text-slate-500 gap-2">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                                Deadline: {{ $task->deadline->format('d M Y, H:i') }}
                                            </div>
                                        </div>
                                        <div class="shrink-0 flex items-center justify-between sm:block mt-2 sm:mt-0">
                                            <span class="sm:hidden text-xs font-medium text-slate-500">Status: Belum Dikerjakan</span>
                                            <a href="{{ route('siswa.assignments.show', $task->id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg-lg text-sm font-semibold transition">
                                                Kerjakan
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>
                </div>

                <div class="lg:col-span-1 space-y-8">
                    <!-- Materi Terbaru -->
                    <section>
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-[18px] font-bold text-slate-900">Materi Terbaru</h2>
                        </div>
                        
                        @if($recentMaterials->isEmpty())
                            <div class="bg-slate-50 border border-slate-200 rounded-xl py-8 px-4 text-center">
                                <h3 class="text-[14px] font-bold text-slate-900 mb-1">Belum Ada Materi</h3>
                                <p class="text-[13px] text-slate-500">Belum ada materi pembelajaran yang tersedia untuk kelas Anda.</p>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($recentMaterials as $material)
                                    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm group">
                                        <div class="flex items-start gap-3">
                                            <div class="mt-0.5 shrink-0 h-8 w-8 rounded-lg bg-blue-50 text-primary flex items-center justify-center">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-[10px] font-bold text-primary uppercase tracking-wider mb-0.5">{{ $material->subject->name ?? '-' }}</p>
                                                <h4 class="text-sm font-bold text-slate-900 mb-1 truncate">{{ $material->title }}</h4>
                                                <p class="text-xs text-slate-500 truncate mb-3">Oleh: {{ $material->teacher->user->name ?? '-' }}</p>
                                                <a href="{{ route('siswa.materials.show', $material->id) }}" class="inline-flex text-[13px] font-semibold text-primary hover:underline">
                                                    Buka Materi &rarr;
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    <!-- Feedback Terbaru -->
                    <section>
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-[18px] font-bold text-slate-900">Feedback Terbaru</h2>
                        </div>
                        
                        @if(!$recentFeedback)
                            <div class="bg-slate-50 border border-slate-200 rounded-xl py-8 px-4 text-center">
                                <h3 class="text-[14px] font-bold text-slate-900 mb-1">Belum Ada Feedback</h3>
                                <p class="text-[13px] text-slate-500">Guru belum memberikan feedback kepada Anda.</p>
                            </div>
                        @else
                            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="h-6 w-6 rounded-full bg-slate-100 flex items-center justify-center shrink-0">
                                        <svg class="h-3 w-3 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                                    </div>
                                    <p class="text-xs font-bold text-slate-900">{{ $recentFeedback->teacher->user->name ?? '-' }}</p>
                                    <span class="text-xs text-slate-400 ml-auto">{{ $recentFeedback->created_at->format('d/m') }}</span>
                                </div>
                                <p class="text-[11px] font-semibold text-primary uppercase tracking-wider mb-1">{{ $recentFeedback->subject->name ?? '-' }}</p>
                                <p class="text-[13px] text-slate-700 line-clamp-3 mb-3">"{{ $recentFeedback->message }}"</p>
                                <a href="{{ route('siswa.feedbacks.show', $recentFeedback->id) }}" class="inline-flex text-[13px] font-semibold text-primary hover:underline">
                                    Lihat Feedback &rarr;
                                </a>
                            </div>
                        @endif
                    </section>
                </div>
            </div>
        @endif
        
    </div>
</x-layouts.app>
