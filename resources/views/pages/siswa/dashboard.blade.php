<x-layouts.app>
    <x-slot:title>Ruang Belajar</x-slot:title>

    <div class="w-full space-y-6">
        
        <!-- Hero Minimalist -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100/50 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900 mb-2">Hai, {{ auth()->user()->name }}! 👋</h1>
                @if(!$classroom)
                    <p class="text-slate-600 text-sm max-w-xl">Anda belum didaftarkan pada kelas aktif.<br>Hubungi admin sekolah untuk mendapatkan penempatan kelas.</p>
                @else
                    <p class="text-slate-600 text-sm max-w-xl mb-4">Selamat datang kembali di ruang belajar Anda.</p>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-blue-100 text-xs font-bold text-primary shadow-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" /></svg>
                        {{ $classroom->name }}
                    </span>
                    <span class="sr-only">Kamu telah menyelesaikan {{ $stats['tugas_selesai'] }} dari total {{ $stats['total_tugas'] }} tugas di {{ $classroom->name }}</span>
                @endif
            </div>
            @if($classroom && $upcomingAssignments->isNotEmpty())
                <div class="shrink-0">
                    <a href="{{ route('siswa.assignments.index') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-primary text-white hover:bg-primary/90 rounded-xl text-sm font-bold transition shadow-sm">
                        Lihat Tugas &rarr;
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
                <div class="bg-white border border-slate-200/75 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-primary/20 transition-all group">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-amber-50 rounded-xl text-amber-600 group-hover:scale-110 transition-transform">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        </div>
                        <p class="text-[13px] font-bold text-slate-600">Tugas Aktif</p>
                    </div>
                    <p class="text-3xl font-bold text-slate-900 mb-1">{{ max(0, $stats['total_tugas'] - $stats['tugas_selesai']) }}</p>
                    <p class="text-xs text-slate-500">Belum dikerjakan</p>
                </div>
                
                <!-- Sudah Dikumpulkan -->
                <div class="bg-white border border-slate-200/75 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-primary/20 transition-all group">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-blue-50 rounded-xl text-blue-600 group-hover:scale-110 transition-transform">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 019 9v.375M10.125 2.25A3.375 3.375 0 0113.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 013.375 3.375M9 15l2.25 2.25L15 12" /></svg>
                        </div>
                        <p class="text-[13px] font-bold text-slate-600">Sudah Kumpul</p>
                    </div>
                    <p class="text-3xl font-bold text-slate-900 mb-1">{{ $stats['tugas_selesai'] }}</p>
                    <p class="text-xs text-slate-500">Tugas diselesaikan</p>
                </div>
                
                <!-- Progres -->
                <div class="bg-white border border-slate-200/75 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-primary/20 transition-all group">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-emerald-50 rounded-xl text-emerald-600 group-hover:scale-110 transition-transform">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <p class="text-[13px] font-bold text-slate-600">Progres Belajar</p>
                    </div>
                    <p class="text-3xl font-bold text-slate-900 mb-1">{{ $stats['progres'] }}% <span class="sr-only">Progres Tugas: {{ $stats['progres'] }}%</span></p>
                    <p class="text-xs text-slate-500">Tingkat penyelesaian</p>
                </div>
                
                <!-- Rata-rata Nilai -->
                <div class="bg-white border border-slate-200/75 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-primary/20 transition-all group">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-indigo-50 rounded-xl text-indigo-600 group-hover:scale-110 transition-transform">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                        </div>
                        <p class="text-[13px] font-bold text-slate-600">Rata-rata Nilai</p>
                    </div>
                    <p class="text-3xl font-bold text-slate-900 mb-1">{{ $stats['rata_rata'] }}</p>
                    <p class="text-xs text-slate-500">Semester aktif</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 mt-4">
                
                <div class="lg:col-span-2 space-y-8">
                    <!-- Tugas Terdekat -->
                    <section>
                        <div class="flex items-center justify-between mb-4 px-1">
                            <h2 class="text-[18px] font-bold text-slate-900">Tugas Terdekat</h2>
                            <a href="{{ route('siswa.assignments.index') }}" class="text-[13px] font-bold text-primary hover:text-blue-700 transition">Lihat Semua &rarr;</a>
                        </div>
                        
                        @if($upcomingAssignments->isEmpty())
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-8 text-center flex flex-col items-center">
                                <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-slate-200 mb-3 text-slate-400">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" /></svg>
                                </div>
                                <h3 class="text-[15px] font-bold text-slate-900 mb-1">Belum Ada Tugas</h3>
                                <p class="text-[13px] text-slate-500">Saat ini tidak ada tugas mendatang yang harus dikerjakan.</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($upcomingAssignments as $task)
                                    <div class="bg-white border border-slate-200/75 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm hover:shadow-md hover:border-primary/40 transition group">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[11px] font-bold text-primary uppercase tracking-wider mb-1">{{ $task->subject->name ?? '-' }} &bull; {{ $classroom->name }}</p>
                                            <h3 class="text-[15px] font-bold text-slate-900 mb-2 truncate group-hover:text-primary transition-colors">{{ $task->title }}</h3>
                                            <div class="flex flex-wrap items-center text-[13px] text-slate-500 gap-3">
                                                <div class="flex items-center gap-1.5">
                                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                                    Deadline: <span class="font-semibold text-slate-700">{{ $task->deadline->format('d M Y, H:i') }}</span>
                                                </div>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                                    <span class="font-medium text-slate-500">Belum Dikerjakan</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="shrink-0 flex items-center mt-2 sm:mt-0 w-full sm:w-auto">
                                            <a href="{{ route('siswa.assignments.show', $task->id) }}" class="inline-flex w-full sm:w-auto items-center justify-center px-5 py-2.5 bg-primary text-white hover:bg-primary/90 rounded-xl text-[13px] font-bold transition shadow-sm">
                                                Kerjakan &rarr;
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
                        <div class="flex items-center justify-between mb-4 px-1">
                            <h2 class="text-[18px] font-bold text-slate-900">Materi Terbaru</h2>
                        </div>
                        
                        @if($recentMaterials->isEmpty())
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl py-8 px-4 text-center">
                                <h3 class="text-[14px] font-bold text-slate-900 mb-1">Belum Ada Materi</h3>
                                <p class="text-[13px] text-slate-500">Belum ada materi pembelajaran yang tersedia.</p>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($recentMaterials as $material)
                                    <div class="bg-white border border-slate-200/75 rounded-2xl p-4 shadow-sm hover:shadow-md hover:border-primary/40 transition group">
                                        <div class="flex items-start gap-4">
                                            <div class="mt-1 shrink-0 h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-[10px] font-bold text-primary uppercase tracking-wider mb-1">{{ $material->subject->name ?? '-' }}</p>
                                                <h4 class="text-[14px] font-bold text-slate-900 mb-1 leading-snug truncate group-hover:text-primary transition-colors">{{ $material->title }}</h4>
                                                <p class="text-xs text-slate-500 truncate mb-3">{{ $material->teacher->user->name ?? '-' }}</p>
                                                <a href="{{ route('siswa.materials.show', $material->id) }}" class="inline-flex items-center text-[13px] font-bold text-primary hover:text-blue-700 transition-colors">
                                                    Buka Materi <svg class="ml-1 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" /></svg>
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
                        <div class="flex items-center justify-between mb-4 px-1">
                            <h2 class="text-[18px] font-bold text-slate-900">Feedback Terbaru</h2>
                        </div>
                        
                        @if(!$recentFeedback)
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl py-8 px-4 text-center">
                                <h3 class="text-[14px] font-bold text-slate-900 mb-1">Belum Ada Feedback</h3>
                                <p class="text-[13px] text-slate-500">Guru belum memberikan feedback kepada Anda.</p>
                            </div>
                        @else
                            <div class="bg-blue-50/60 border border-blue-100/60 rounded-2xl p-5 shadow-sm hover:shadow-md transition">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full bg-white text-blue-600 shadow-sm flex items-center justify-center shrink-0 font-bold text-xs border border-blue-100">
                                            {{ strtoupper(substr($recentFeedback->teacher->user->name ?? 'G', 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="text-[13px] font-bold text-slate-900 leading-none mb-1">{{ $recentFeedback->teacher->user->name ?? '-' }}</p>
                                            <p class="text-[10px] font-semibold text-primary uppercase tracking-wider">{{ $recentFeedback->subject->name ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <span class="text-[11px] text-slate-500 font-medium bg-white px-2 py-1 rounded-md border border-slate-100">{{ $recentFeedback->created_at->format('d M') }}</span>
                                </div>
                                
                                <div class="mb-4 pl-3 border-l-2 border-primary/30">
                                    <p class="text-[13px] text-slate-700 line-clamp-3 font-medium italic">"{{ $recentFeedback->message }}"</p>
                                </div>
                                <a href="{{ route('siswa.feedbacks.show', $recentFeedback->id) }}" class="inline-flex items-center text-[13px] font-bold text-primary hover:text-blue-700 transition-colors">
                                    Lihat Feedback <svg class="ml-1 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" /></svg>
                                </a>
                            </div>
                        @endif
                    </section>
                </div>
            </div>
        @endif
        
    </div>
</x-layouts.app>
