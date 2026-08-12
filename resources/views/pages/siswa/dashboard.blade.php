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
                    <h1 class="text-3xl font-bold tracking-tight mb-2">Hai, Siswa Berprestasi!</h1>
                    <p class="text-blue-50 text-sm max-w-xl mb-4">Minggu ini kamu sudah menyelesaikan 4 tugas. Teruskan semangat belajarmu, ada 2 tugas baru yang menunggu.</p>
                    <div class="flex items-center gap-3 mt-2">
                        <div class="bg-white/20 rounded-full h-2 w-48 overflow-hidden backdrop-blur-sm">
                            <div class="bg-white h-full rounded-full w-[70%]"></div>
                        </div>
                        <span class="text-xs font-semibold">Progres Mingguan: 70%</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('siswa.learning.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-accent text-white hover:bg-accent-hover rounded-xl text-sm font-bold transition shadow-sm">
                        Lanjutkan Belajar &rarr;
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Left Column: Upcoming Tasks & Materials -->
            <div class="xl:col-span-2 space-y-8">
                
                <!-- Upcoming Assignments -->
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-slate-900">Tugas Mendatang</h2>
                        <a href="{{ route('siswa.assignments.index') }}" class="text-sm font-medium text-accent hover:text-accent-hover">Lihat Semua Tugas</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Task Card 1 (Urgent) -->
                        <div class="bg-white border-2 border-red-100 rounded-2xl p-5 hover:border-red-300 transition relative overflow-hidden group shadow-sm">
                            <div class="flex items-start justify-between mb-3">
                                <div class="bg-red-50 text-red-600 px-2.5 py-1 rounded-lg text-xs font-bold flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                    Besok, 23:59
                                </div>
                                <span class="text-xs font-semibold text-slate-400">Matematika Peminatan</span>
                            </div>
                            <h3 class="font-bold text-slate-900 text-base mb-1">Tugas Integral Substitusi</h3>
                            <p class="text-xs text-slate-500 mb-4 line-clamp-2">Kerjakan latihan soal pada halaman 45 buku cetak mengenai aturan integral substitusi trigonometri.</p>
                            <a href="#" class="block w-full text-center py-2 bg-slate-50 hover:bg-red-50 text-red-600 rounded-xl text-sm font-semibold transition">
                                Kerjakan Tugas
                            </a>
                        </div>

                        <!-- Task Card 2 -->
                        <div class="bg-white border border-slate-200 rounded-2xl p-5 hover:border-accent transition relative overflow-hidden shadow-sm">
                            <div class="flex items-start justify-between mb-3">
                                <div class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-lg text-xs font-bold flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                                    Jumat, 10 Nov
                                </div>
                                <span class="text-xs font-semibold text-slate-400">Fisika Lintas Minat</span>
                            </div>
                            <h3 class="font-bold text-slate-900 text-base mb-1">Laporan Praktikum Termodinamika</h3>
                            <p class="text-xs text-slate-500 mb-4 line-clamp-2">Kumpulkan laporan sementara praktikum minggu lalu dalam format PDF.</p>
                            <a href="#" class="block w-full text-center py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-xl text-sm font-semibold transition">
                                Kerjakan Tugas
                            </a>
                        </div>
                    </div>
                </section>

                <!-- Recent Materials -->
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-slate-900">Materi Baru Saja Diunggah</h2>
                        <a href="{{ route('siswa.materials.index') }}" class="text-sm font-medium text-accent hover:text-accent-hover">Materi Lainnya</a>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
                        <a href="#" class="block p-4 border-b border-slate-100 flex items-center justify-between hover:bg-slate-50 transition group">
                            <div class="flex items-center gap-4">
                                <div class="bg-blue-50 text-primary p-2.5 rounded-xl group-hover:scale-110 transition-transform">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-slate-900 text-sm">Modul 4: Termodinamika Dasar</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Fisika • Bapak Budi Santoso</p>
                                </div>
                            </div>
                            <span class="text-accent text-xs font-semibold flex items-center">
                                Baca <svg class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                            </span>
                        </a>
                        <a href="#" class="block p-4 flex items-center justify-between hover:bg-slate-50 transition group">
                            <div class="flex items-center gap-4">
                                <div class="bg-blue-50 text-primary p-2.5 rounded-xl group-hover:scale-110 transition-transform">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-slate-900 text-sm">Video: Konsep Dasar Limit</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Matematika Peminatan • Ibu Siti Aminah</p>
                                </div>
                            </div>
                            <span class="text-accent text-xs font-semibold flex items-center">
                                Tonton <svg class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                            </span>
                        </a>
                    </div>
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
                                <span class="text-lg font-bold text-primary">85.4</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="bg-primary h-2 rounded-full" style="width: 85%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between items-end mb-1.5">
                                <span class="text-xs font-semibold text-slate-500">Tingkat Kehadiran</span>
                                <span class="text-lg font-bold text-success">98%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="bg-success h-2 rounded-full" style="width: 98%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between items-end mb-1.5">
                                <span class="text-xs font-semibold text-slate-500">Penyelesaian Tugas</span>
                                <span class="text-lg font-bold text-orange-500">12/15</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="bg-orange-400 h-2 rounded-full" style="width: 80%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Feedback -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-bold text-slate-900">Feedback Terbaru</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 relative">
                            <!-- Arrow pointing to avatar conceptually -->
                            <div class="flex items-center gap-3 mb-2">
                                <div class="h-8 w-8 bg-blue-100 text-primary rounded-full flex items-center justify-center font-bold text-xs">
                                    BS
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-900">Bapak Budi Santoso</p>
                                    <p class="text-[10px] text-slate-500">Fisika • 2 hari yang lalu</p>
                                </div>
                            </div>
                            <p class="text-sm text-slate-700 italic">"Laporan praktikummu sangat rapi. Pemahaman konsep termodinamika sudah terlihat baik. Pertahankan!"</p>
                            <a href="#" class="mt-3 inline-block text-xs font-semibold text-accent hover:underline">Lihat Detail Nilai</a>
                        </div>
                        
                        <div class="text-center mt-2 border-t border-slate-100 pt-3">
                            <a href="{{ route('siswa.feedbacks.index') }}" class="text-xs font-semibold text-slate-500 hover:text-accent transition">Lihat Semua Feedback</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</x-layouts.app>
