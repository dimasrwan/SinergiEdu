<x-layouts.app>
    <x-slot:title>Portal Orang Tua</x-slot:title>

    <div class="w-full space-y-8">
        
        <!-- Student Profile Snapshot Banner -->
        <div class="bg-primary rounded-3xl p-8 text-white shadow-xl shadow-primary/20 relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-2xl bg-white/10 border-2 border-white/20 flex items-center justify-center backdrop-blur-sm overflow-hidden shrink-0">
                        <!-- Placeholder Avatar -->
                        <svg class="h-12 w-12 text-blue-100" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight mb-1">Budi Setiawan (Anak Anda)</h1>
                        <p class="text-slate-300 text-sm mb-3">Kelas XI IPA 1 • NISN: 10293847</p>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-200 border border-green-500/30">
                                Kehadiran Sempurna (100%)
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Vitals & Activity -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Academic Vitals (Simple Trends) -->
                <section>
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Perkembangan Akademik (Semester Ini)</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-card padding="md" class="relative overflow-hidden">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Rata-rata Nilai</p>
                                    <h3 class="text-3xl font-bold text-slate-900">85.4</h3>
                                </div>
                                <div class="bg-green-50 text-success p-2 rounded-lg flex items-center text-sm font-bold gap-1">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>
                                    +2.1
                                </div>
                            </div>
                            <!-- Mock Mini Line Chart -->
                            <div class="h-10 w-full flex items-end gap-1 px-1">
                                <div class="w-full bg-blue-100 rounded-t-md h-[40%]"></div>
                                <div class="w-full bg-blue-100 rounded-t-md h-[45%]"></div>
                                <div class="w-full bg-blue-100 rounded-t-md h-[60%]"></div>
                                <div class="w-full bg-blue-100 rounded-t-md h-[55%]"></div>
                                <div class="w-full bg-blue-100 rounded-t-md h-[70%]"></div>
                                <div class="w-full bg-blue-500 rounded-t-md h-[85%]"></div>
                            </div>
                            <div class="flex justify-between text-[10px] text-slate-400 mt-2">
                                <span>Jul</span>
                                <span>Ags</span>
                                <span>Sep</span>
                                <span>Okt</span>
                                <span>Nov</span>
                                <span class="text-primary font-bold">Des</span>
                            </div>
                        </x-card>

                        <x-card padding="md">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Penyelesaian Tugas</p>
                                    <h3 class="text-3xl font-bold text-slate-900">12<span class="text-lg text-slate-400">/15</span></h3>
                                </div>
                                <div class="bg-orange-50 text-orange-600 p-2 rounded-lg text-sm font-bold">
                                    80%
                                </div>
                            </div>
                            <p class="text-sm text-slate-600">Ada <span class="font-bold text-slate-900">3 tugas</span> yang belum dikerjakan minggu ini. Disarankan untuk memantau waktu belajar anak di rumah.</p>
                            <a href="{{ route('orangtua.assignments.index') }}" class="mt-4 inline-block text-sm font-semibold text-accent hover:underline">Lihat Daftar Tugas &rarr;</a>
                        </x-card>
                    </div>
                </section>

                <!-- Recent School Activity -->
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-slate-900">Aktivitas Sekolah Terbaru</h2>
                        <a href="{{ route('orangtua.progress.index') }}" class="text-sm font-medium text-accent hover:text-accent-hover">Laporan Lengkap</a>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-2xl p-6">
                        <div class="relative border-l-2 border-slate-100 ml-3 space-y-8">
                            
                            <!-- Activity Item 1 -->
                            <div class="relative pl-6">
                                <div class="absolute -left-3 top-1 h-6 w-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center ring-4 ring-white">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                </div>
                                <p class="text-xs font-semibold text-slate-500 mb-1">Hari ini, 10:15 WIB</p>
                                <h4 class="font-bold text-slate-900 text-sm">Menyelesaikan Tugas Matematika</h4>
                                <p class="text-sm text-slate-600 mt-1">Budi telah mengumpulkan "Tugas Integral Substitusi" tepat waktu.</p>
                            </div>

                            <!-- Activity Item 2 -->
                            <div class="relative pl-6">
                                <div class="absolute -left-3 top-1 h-6 w-6 bg-blue-100 text-primary rounded-full flex items-center justify-center ring-4 ring-white">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                </div>
                                <p class="text-xs font-semibold text-slate-500 mb-1">Kemarin, 08:00 WIB</p>
                                <h4 class="font-bold text-slate-900 text-sm">Membaca Materi Baru</h4>
                                <p class="text-sm text-slate-600 mt-1">Mengakses "Modul 4: Termodinamika Dasar" pada mata pelajaran Fisika.</p>
                            </div>
                            
                            <!-- Activity Item 3 -->
                            <div class="relative pl-6">
                                <div class="absolute -left-3 top-1 h-6 w-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center ring-4 ring-white">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                </div>
                                <p class="text-xs font-semibold text-slate-500 mb-1">Senin, 09:30 WIB</p>
                                <h4 class="font-bold text-slate-900 text-sm">Tugas Terlewat</h4>
                                <p class="text-sm text-slate-600 mt-1">Melewatkan pengumpulan "Laporan Praktikum Biologi".</p>
                            </div>

                        </div>
                    </div>
                </section>
            </div>

            <!-- Right Column: Teacher Communication -->
            <div class="col-span-1 space-y-8">
                
                <section>
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Catatan Guru</h2>
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm relative">
                        <div class="absolute -top-3 right-5">
                            <span class="relative flex h-6 w-6">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-6 w-6 bg-accent text-white items-center justify-center">
                                  <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" /></svg>
                              </span>
                            </span>
                        </div>
                        <div class="flex items-center gap-3 mb-4 border-b border-slate-100 pb-4">
                            <div class="h-10 w-10 bg-slate-100 rounded-full flex items-center justify-center font-bold text-slate-500">
                                SA
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">Ibu Siti Aminah</h4>
                                <p class="text-xs text-slate-500">Wali Kelas XI IPA 1</p>
                            </div>
                        </div>
                        <p class="text-sm text-slate-700 italic mb-4">"Bapak/Ibu, Budi menunjukkan peningkatan luar biasa di mata pelajaran eksakta bulan ini. Namun mohon didorong untuk lebih aktif saat diskusi kelompok Bahasa Indonesia."</p>
                        
                        <x-button variant="primary" class="w-full justify-center">
                            Balas Pesan Guru
                        </x-button>
                    </div>
                </section>

                <section>
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-center">
                        <div class="bg-white p-3 rounded-xl inline-block shadow-sm mb-3 text-accent">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                        </div>
                        <h3 class="font-bold text-slate-900 mb-2">Panduan Dukungan Belajar</h3>
                        <p class="text-sm text-slate-600 mb-4">Dapatkan tips dari psikolog pendidikan tentang cara mendampingi anak belajar di rumah secara efektif.</p>
                        <a href="{{ route('orangtua.support.index') }}" class="text-sm font-semibold text-accent hover:underline">Baca Panduan</a>
                    </div>
                </section>

            </div>
        </div>
        
    </div>
</x-layouts.app>
