<x-layouts.app>
    <x-slot:title>Ruang Belajar</x-slot:title>

    <div class="w-full space-y-8">
        
        <!-- Learning Banner -->
        <div class="bg-primary rounded-3xl p-8 text-white shadow-lg shadow-primary/20 relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            <div class="absolute left-1/2 bottom-0 w-32 h-32 bg-white/5 rounded-full blur-2xl translate-y-1/2"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight mb-2">Hai, {{ auth()->user()->name }}!</h1>
                    @if(!$classroom)
                        <p class="text-blue-50 text-sm max-w-xl mb-4">Periode akademik aktif atau kelas Anda belum tersedia. Hubungi admin sekolah.</p>
                    @else
                        <p class="text-blue-50 text-sm max-w-xl mb-4">
                            Kamu telah menyelesaikan {{ $stats['tugas_selesai'] }} dari total {{ $stats['total_tugas'] }} tugas di kelas {{ $classroom->name }}. Terus semangat belajarnya!
                        </p>
                        <div class="flex items-center gap-3 mt-2">
                            <div class="bg-white/20 rounded-full h-2 w-48 overflow-hidden backdrop-blur-sm">
                                <div class="bg-white h-full rounded-full" style="width: {{ $stats['progres'] }}%"></div>
                            </div>
                            <span class="text-xs font-semibold">Progres Tugas: {{ $stats['progres'] }}%</span>
                        </div>
                    @endif
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('siswa.assignments.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-accent text-white hover:bg-accent-hover rounded-xl text-sm font-bold transition shadow-sm">
                        Lihat Tugas &rarr;
                    </a>
                </div>
            </div>
        </div>

        @if(!$classroom)
            <div class="bg-slate-50 border border-slate-200 rounded-3xl p-10 text-center">
                <svg class="mx-auto h-12 w-12 text-slate-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h3 class="text-lg font-bold text-slate-900">Belum Ada Kelas Aktif</h3>
                <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto">Anda belum didaftarkan pada kelas aktif untuk periode akademik saat ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <!-- Left Column: Upcoming Tasks & Materials -->
                <div class="xl:col-span-2 space-y-8">
                    
                    <!-- Upcoming Assignments -->
                    <section>
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-slate-900">Tugas Mendatang</h2>
                            <a href="{{ route('siswa.assignments.index') }}" class="text-sm font-medium text-accent hover:text-accent-hover">Lihat Semua Tugas</a>
                        </div>
                        
                        @if($upcomingAssignments->isEmpty())
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-center">
                                <p class="text-sm text-slate-500">Tidak ada tugas mendatang. Pertahankan kerja bagusmu!</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($upcomingAssignments as $task)
                                    @php
                                        $isUrgent = $task->deadline->diffInHours(now()) <= 48;
                                    @endphp
                                    <div class="bg-white border {{ $isUrgent ? 'border-red-100 hover:border-red-300' : 'border-slate-200 hover:border-accent' }} rounded-2xl p-5 transition relative overflow-hidden shadow-sm flex flex-col h-full">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="{{ $isUrgent ? 'bg-red-50 text-red-600' : 'bg-slate-100 text-slate-600' }} px-2.5 py-1 rounded-lg text-xs font-bold flex items-center gap-1.5">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    @if($isUrgent)
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                                    @endif
                                                </svg>
                                                {{ $task->deadline->format('d M, H:i') }}
                                            </div>
                                            <span class="text-xs font-semibold text-slate-400">{{ $task->subject->name ?? '-' }}</span>
                                        </div>
                                        <h3 class="font-bold text-slate-900 text-base mb-1">{{ $task->title }}</h3>
                                        <p class="text-xs text-slate-500 mb-4 line-clamp-2 flex-grow">{{ $task->description }}</p>
                                        <a href="{{ route('siswa.assignments.show', $task->id) }}" class="block w-full text-center py-2 {{ $isUrgent ? 'bg-slate-50 hover:bg-red-50 text-red-600' : 'bg-slate-50 hover:bg-slate-100 text-slate-700' }} rounded-xl text-sm font-semibold transition mt-auto">
                                            Kerjakan Tugas
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    <!-- Recent Materials -->
                    <section>
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-slate-900">Materi Baru Saja Diunggah</h2>
                            <a href="{{ route('siswa.materials.index') }}" class="text-sm font-medium text-accent hover:text-accent-hover">Materi Lainnya</a>
                        </div>
                        
                        @if($recentMaterials->isEmpty())
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-center">
                                <p class="text-sm text-slate-500">Belum ada materi terbaru.</p>
                            </div>
                        @else
                            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
                                @foreach($recentMaterials as $material)
                                    <a href="{{ route('siswa.materials.show', $material->id) }}" class="block p-4 {{ !$loop->last ? 'border-b border-slate-100' : '' }} flex items-center justify-between hover:bg-slate-50 transition group">
                                        <div class="flex items-center gap-4">
                                            <div class="bg-blue-50 text-primary p-2.5 rounded-xl group-hover:scale-110 transition-transform">
                                                @if($material->video_path)
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" /></svg>
                                                @else
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                                                @endif
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-slate-900 text-sm">{{ $material->title }}</h4>
                                                <p class="text-xs text-slate-500 mt-0.5">{{ $material->subject->name ?? '-' }} &bull; {{ $material->teacher->user->name ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <span class="text-accent text-xs font-semibold flex items-center shrink-0">
                                            {{ $material->video_path ? 'Tonton' : 'Baca' }} <svg class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </section>
                </div>

                <!-- Right Column: Progress & Feedback -->
                <div class="col-span-1 space-y-8">
                    <!-- Vitals Summary -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-6">
                        <h2 class="text-base font-bold text-slate-900 mb-6">Status Akademik</h2>
                        
                        <div class="space-y-5">
                            <div>
                                <div class="flex justify-between items-end mb-1.5">
                                    <span class="text-xs font-semibold text-slate-500">Rata-rata Nilai (Semester ini)</span>
                                    <span class="text-lg font-bold text-primary">{{ $stats['rata_rata'] }}</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    @php $avgScore = is_numeric($stats['rata_rata']) ? $stats['rata_rata'] : 0; @endphp
                                    <div class="bg-primary h-2 rounded-full" style="width: {{ min(100, $avgScore) }}%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between items-end mb-1.5">
                                    <span class="text-xs font-semibold text-slate-500">Penyelesaian Tugas</span>
                                    <span class="text-lg font-bold text-orange-500">{{ $stats['tugas_selesai'] }}/{{ $stats['total_tugas'] }}</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-orange-400 h-2 rounded-full" style="width: {{ $stats['progres'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Feedback -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-base font-bold text-slate-900">Feedback Terbaru</h2>
                        </div>
                        
                        @if(!$recentFeedback)
                            <div class="text-center py-6">
                                <p class="text-sm text-slate-500">Belum ada feedback dari guru.</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 relative">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="h-8 w-8 bg-blue-100 text-primary rounded-full flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($recentFeedback->teacher->user->name ?? 'G', 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-900">{{ $recentFeedback->teacher->user->name ?? 'Guru' }}</p>
                                            <p class="text-[10px] text-slate-500">{{ $recentFeedback->subject->name ?? '-' }} &bull; {{ $recentFeedback->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-slate-700 italic">"{{ $recentFeedback->message }}"</p>
                                    <a href="{{ route('siswa.feedbacks.show', $recentFeedback->id) }}" class="mt-3 inline-block text-xs font-semibold text-accent hover:underline">Lihat Detail</a>
                                </div>
                                
                                <div class="text-center mt-2 border-t border-slate-100 pt-3">
                                    <a href="{{ route('siswa.feedbacks.index') }}" class="text-xs font-semibold text-slate-500 hover:text-accent transition">Lihat Semua Feedback</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        
    </div>
</x-layouts.app>
