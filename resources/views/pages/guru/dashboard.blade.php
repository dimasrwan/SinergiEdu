<x-layouts.app>
    <x-slot:title>Teaching Workspace</x-slot:title>

    <div class="w-full space-y-8">
        
        <!-- Welcome Banner & Quick Actions -->
        <div class="bg-primary rounded-3xl p-8 text-white shadow-lg shadow-primary/20 relative overflow-hidden">
            <!-- Decorative shape -->
            <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight mb-2">Selamat Datang, Bapak/Ibu Guru!</h1>
                    @if($missingContext)
                        <p class="text-amber-200 text-sm max-w-xl font-medium">Konfigurasi akademik aktif belum tersedia. Harap hubungi Admin Sekolah.</p>
                    @else
                        @if($totalUnscoredSubmissions > 0)
                            <p class="text-blue-100 text-sm max-w-xl">Anda memiliki {{ $totalUnscoredSubmissions }} tugas siswa yang belum dinilai. Mari mulai kegiatan mengajar dan pantau perkembangan siswa Anda.</p>
                        @else
                            <p class="text-blue-100 text-sm max-w-xl">Semua tugas siswa sudah dinilai. Mari mulai kegiatan mengajar dan pantau perkembangan siswa Anda.</p>
                        @endif
                    @endif
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('guru.materials.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg text-sm font-semibold transition backdrop-blur-sm border border-white/10">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Tambah Materi
                    </a>
                    <a href="{{ route('guru.assignments.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white text-primary hover:bg-slate-50 rounded-lg text-sm font-semibold transition shadow-sm">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Buat Tugas Baru
                    </a>
                </div>
            </div>
        </div>

        @if($missingContext)
            <!-- Empty state untuk konteks akademik yang hilang -->
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-8 text-center max-w-2xl mx-auto">
                <div class="inline-flex items-center justify-center p-3 bg-amber-100 text-amber-600 rounded-lg mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Konteks Akademik Tidak Aktif</h3>
                <p class="text-slate-600 mb-4">Tahun ajaran atau semester aktif belum diatur oleh admin. Statistik dan kelas tidak dapat ditampilkan secara akurat.</p>
            </div>
        @else
            <!-- Teaching Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-card padding="md" class="border-l-4 border-l-accent flex items-start gap-4">
                    <div class="p-3 bg-sky-50 text-accent rounded-xl">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.68 0-5.302.2-7.854.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900">{{ $activeClassesCount }}</h3>
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-500 mt-1">Kelas Aktif</p>
                    </div>
                </x-card>
                
                <x-card padding="md" class="border-l-4 border-l-orange-400 flex items-start gap-4">
                    <div class="p-3 bg-orange-50 text-orange-500 rounded-xl">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900">{{ $unscoredTasksCount }}</h3>
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-500 mt-1">Tugas Menunggu</p>
                    </div>
                </x-card>

                <x-card padding="md" class="border-l-4 border-l-success flex items-start gap-4">
                    <div class="p-3 bg-green-50 text-success rounded-xl">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900">{{ $totalStudentsCount }}</h3>
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-500 mt-1">Total Siswa Diajar</p>
                    </div>
                </x-card>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Classes & Submissions -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Kelas Aktif Saya -->
                    <section>
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-slate-900">Kelas Aktif Saya</h2>
                            <a href="{{ route('guru.classes.index') }}" class="text-sm font-medium text-accent hover:text-accent-hover">Lihat Semua</a>
                        </div>
                        
                        @if($activeClasses->isEmpty())
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-center">
                                <p class="text-sm text-slate-500">Anda belum memiliki kelas aktif yang ditugaskan.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($activeClasses as $assignment)
                                    <a href="{{ route('guru.classes.show', $assignment) }}" class="block bg-white border border-slate-200 rounded-2xl p-5 hover:border-accent hover:shadow-sm transition group relative overflow-hidden">
                                        <div class="absolute top-0 right-0 w-2 h-full bg-accent opacity-0 group-hover:opacity-100 transition"></div>
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h3 class="font-bold text-slate-900 text-lg">{{ $assignment->classroom->name }}</h3>
                                                <p class="text-xs text-slate-500">{{ $assignment->subject->name }}</p>
                                            </div>
                                            <span class="bg-blue-50 text-primary text-xs font-bold px-2.5 py-1 rounded-lg">Tk {{ $assignment->classroom->grade_level }}</span>
                                        </div>
                                        <div class="flex items-center justify-between border-t border-slate-100 pt-4 mt-2">
                                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                                                {{ $assignment->classroom->students_count ?? 0 }} Siswa
                                            </div>
                                            <span class="text-xs font-semibold text-accent group-hover:underline">Masuk Kelas &rarr;</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    <!-- Tugas Menunggu Penilaian -->
                    <section>
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-slate-900">Perlu Penilaian</h2>
                            <a href="{{ route('guru.assignments.index') }}" class="text-sm font-medium text-accent hover:text-accent-hover">Lihat Semua Tugas</a>
                        </div>
                        
                        @if($needGradingAssignments->isEmpty())
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-center">
                                <p class="text-sm text-slate-500">Bagus! Tidak ada tugas siswa yang belum dinilai.</p>
                            </div>
                        @else
                            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
                                @foreach($needGradingAssignments as $task)
                                    <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between hover:bg-slate-50 transition gap-4">
                                        <div class="flex items-center gap-4">
                                            <div class="bg-orange-100 text-orange-600 p-2.5 rounded-xl shrink-0">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-slate-900 text-sm">{{ $task->title }}</h4>
                                                <p class="text-xs text-slate-500 mt-0.5">{{ $task->classroom->name }} • {{ $task->unscored_submissions_count }} Pengumpulan Baru</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('guru.assignments.show', $task->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg-lg text-xs font-semibold transition shrink-0">
                                            Nilai Sekarang
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>
                </div>

                <!-- Right Column: Activity Timeline -->
                <div class="col-span-1">
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 h-full">
                        <h2 class="text-base font-bold text-slate-900 mb-6">Aktivitas Mengajar</h2>
                        
                        <div class="text-center py-10">
                            <div class="inline-flex items-center justify-center p-3 bg-slate-50 text-slate-400 rounded-lg mb-3">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            </div>
                            <p class="text-sm text-slate-500">Belum ada aktivitas mengajar terbaru.</p>
                        </div>
                        
                    </div>
                </div>
            </div>
        @endif
        
    </div>
</x-layouts.app>
