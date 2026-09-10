<x-layouts.app>
    <x-slot:title>Academic Analytics</x-slot:title>

    <div class="w-full space-y-6 sm:space-y-8 min-w-0">
        
        <!-- Executive Summary Banner -->
        <div class="bg-primary rounded-2xl p-5 sm:p-6 md:p-8 text-white shadow-xl shadow-primary/20 relative overflow-hidden min-w-0">
            <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6 min-w-0">
                <div class="min-w-0">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight mb-1 sm:mb-2 truncate">Ikhtisar Kurikulum</h1>
                    <p class="text-blue-200 text-xs sm:text-sm font-medium truncate">Tahun Ajaran 2026/2027 &bull; Semester Ganjil</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 shrink-0 w-full md:w-auto">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-3.5 sm:p-4 min-w-0">
                        <p class="text-[10px] sm:text-xs text-blue-200 font-semibold uppercase tracking-wider mb-1 truncate">Rata-rata Sekolah</p>
                        <h3 class="text-xl sm:text-2xl font-bold">{{ number_format($stats['avg_grade'], 1) }}</h3>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-3.5 sm:p-4 min-w-0">
                        <p class="text-[10px] sm:text-xs text-blue-200 font-semibold uppercase tracking-wider mb-1 truncate">Total Pertemuan</p>
                        <h3 class="text-xl sm:text-2xl font-bold">{{ $stats['meeting_count'] }}</h3>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-3.5 sm:p-4 min-w-0">
                        <p class="text-[10px] sm:text-xs text-blue-200 font-semibold uppercase tracking-wider mb-1 truncate">Pencapaian Hafalan</p>
                        <h3 class="text-xl sm:text-2xl font-bold truncate">Juz {{ $stats['max_juz'] ?? '-' }} : {{ $stats['max_ayat'] ?? '-' }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left Column: Analytics & Charts (≈70% -> lg:col-span-8) -->
            <div class="lg:col-span-8 space-y-6 sm:space-y-8 min-w-0">
                
                <!-- Performance Comparison -->
                <section class="min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 min-w-0">
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 truncate">Perbandingan Performa Kelas (Paralel XI)</h2>
                        <div class="w-full sm:w-40 shrink-0">
                            <x-select :options="[
                                ['value' => '10', 'label' => 'Tingkat 10'],
                                ['value' => '11', 'label' => 'Tingkat 11'],
                                ['value' => '12', 'label' => 'Tingkat 12']
                            ]" selected="11" />
                        </div>
                    </div>
                    
                    <div class="bg-white border border-slate-200/75 rounded-2xl p-4 sm:p-6 shadow-2xs min-w-0">
                        <!-- Desktop/Tablet Bar Chart -->
                        <div class="hidden sm:flex h-64 w-full items-end gap-3 sm:gap-4 px-2 pb-6 border-b border-slate-100 relative min-w-0">
                            <!-- Y-Axis markers -->
                            <div class="absolute left-0 bottom-6 top-0 w-full flex flex-col justify-between pointer-events-none text-[10px] text-slate-400">
                                <div class="w-full border-b border-slate-100 border-dashed h-0 flex items-center"><span class="bg-white pr-2 -translate-y-2">100</span></div>
                                <div class="w-full border-b border-slate-100 border-dashed h-0 flex items-center"><span class="bg-white pr-2 -translate-y-2">80</span></div>
                                <div class="w-full border-b border-slate-100 border-dashed h-0 flex items-center"><span class="bg-white pr-2 -translate-y-2">60</span></div>
                            </div>
                            
                            <!-- Bars -->
                            <div class="flex-1 flex flex-col items-center group z-10 ml-6 min-w-0">
                                <div class="w-full max-w-[2.5rem] sm:max-w-[3rem] bg-blue-500 rounded-t-md h-[85%] group-hover:bg-blue-400 transition cursor-pointer relative">
                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] sm:text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">85.0</div>
                                </div>
                                <span class="text-[11px] sm:text-xs font-semibold text-slate-600 mt-3 truncate w-full text-center">11 IPA 1</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center group z-10 min-w-0">
                                <div class="w-full max-w-[2.5rem] sm:max-w-[3rem] bg-blue-500 rounded-t-md h-[78%] group-hover:bg-blue-400 transition cursor-pointer relative">
                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] sm:text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">78.4</div>
                                </div>
                                <span class="text-[11px] sm:text-xs font-semibold text-slate-600 mt-3 truncate w-full text-center">11 IPA 2</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center group z-10 min-w-0">
                                <div class="w-full max-w-[2.5rem] sm:max-w-[3rem] bg-blue-500 rounded-t-md h-[82%] group-hover:bg-blue-400 transition cursor-pointer relative">
                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] sm:text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">82.1</div>
                                </div>
                                <span class="text-[11px] sm:text-xs font-semibold text-slate-600 mt-3 truncate w-full text-center">11 IPA 3</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center group z-10 min-w-0">
                                <div class="w-full max-w-[2.5rem] sm:max-w-[3rem] bg-sky-500 rounded-t-md h-[72%] group-hover:bg-sky-400 transition cursor-pointer relative">
                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] sm:text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">72.5</div>
                                </div>
                                <span class="text-[11px] sm:text-xs font-semibold text-slate-600 mt-3 truncate w-full text-center">11 IPS 1</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center group z-10 min-w-0">
                                <div class="w-full max-w-[2.5rem] sm:max-w-[3rem] bg-sky-500 rounded-t-md h-[76%] group-hover:bg-sky-400 transition cursor-pointer relative">
                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] sm:text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">76.0</div>
                                </div>
                                <span class="text-[11px] sm:text-xs font-semibold text-slate-600 mt-3 truncate w-full text-center">11 IPS 2</span>
                            </div>
                        </div>

                        <!-- Mobile Summary Cards (sm:hidden) -->
                        <div class="sm:hidden space-y-2.5">
                            <div class="flex items-center justify-between p-3 bg-slate-50 border border-slate-100 rounded-xl">
                                <span class="font-bold text-slate-900 text-xs">11 IPA 1</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-20 bg-slate-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-blue-500 h-full rounded-full" style="width: 85%"></div>
                                    </div>
                                    <span class="font-bold text-blue-600 text-xs min-w-[32px] text-right">85.0</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-slate-50 border border-slate-100 rounded-xl">
                                <span class="font-bold text-slate-900 text-xs">11 IPA 2</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-20 bg-slate-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-blue-500 h-full rounded-full" style="width: 78.4%"></div>
                                    </div>
                                    <span class="font-bold text-blue-600 text-xs min-w-[32px] text-right">78.4</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-slate-50 border border-slate-100 rounded-xl">
                                <span class="font-bold text-slate-900 text-xs">11 IPA 3</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-20 bg-slate-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-blue-500 h-full rounded-full" style="width: 82.1%"></div>
                                    </div>
                                    <span class="font-bold text-blue-600 text-xs min-w-[32px] text-right">82.1</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-slate-50 border border-slate-100 rounded-xl">
                                <span class="font-bold text-slate-900 text-xs">11 IPS 1</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-20 bg-slate-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-sky-500 h-full rounded-full" style="width: 72.5%"></div>
                                    </div>
                                    <span class="font-bold text-sky-600 text-xs min-w-[32px] text-right">72.5</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-slate-50 border border-slate-100 rounded-xl">
                                <span class="font-bold text-slate-900 text-xs">11 IPS 2</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-20 bg-slate-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-sky-500 h-full rounded-full" style="width: 76%"></div>
                                    </div>
                                    <span class="font-bold text-sky-600 text-xs min-w-[32px] text-right">76.0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Subject Performance Matrix -->
                <section class="min-w-0">
                    <div class="flex items-center justify-between mb-4 min-w-0">
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 truncate">Matriks Ketercapaian Mata Pelajaran</h2>
                        <a href="{{ route('waka.analytics.index') }}" class="text-xs sm:text-sm font-bold text-primary hover:text-blue-700 transition shrink-0">Analitik Penuh &rarr;</a>
                    </div>
                    
                    <div class="bg-white border border-slate-200/75 rounded-2xl overflow-hidden shadow-2xs min-w-0">
                        <!-- Desktop Table (hidden lg:block) -->
                        <div class="hidden lg:block overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200">
                                        <th class="px-4 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
                                        <th class="px-4 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">KKM</th>
                                        <th class="px-4 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nilai Rata-rata</th>
                                        <th class="px-4 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Distribusi (>KKM)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-4 font-semibold text-slate-900 text-sm">Matematika Peminatan</td>
                                        <td class="px-4 py-4 text-slate-600 text-sm">75</td>
                                        <td class="px-4 py-4 text-sm font-bold text-slate-900">76.4</td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-24 bg-slate-200 rounded-full h-1.5"><div class="bg-amber-500 h-1.5 rounded-full" style="width: 55%"></div></div>
                                                <span class="text-xs font-medium text-slate-600">55%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-4 font-semibold text-slate-900 text-sm">Fisika</td>
                                        <td class="px-4 py-4 text-slate-600 text-sm">75</td>
                                        <td class="px-4 py-4 text-sm font-bold text-slate-900">81.2</td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-24 bg-slate-200 rounded-full h-1.5"><div class="bg-emerald-500 h-1.5 rounded-full" style="width: 82%"></div></div>
                                                <span class="text-xs font-medium text-slate-600">82%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-4 font-semibold text-slate-900 text-sm">Biologi</td>
                                        <td class="px-4 py-4 text-slate-600 text-sm">78</td>
                                        <td class="px-4 py-4 text-sm font-bold text-slate-900">85.0</td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-24 bg-slate-200 rounded-full h-1.5"><div class="bg-emerald-500 h-1.5 rounded-full" style="width: 90%"></div></div>
                                                <span class="text-xs font-medium text-slate-600">90%</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Cards (lg:hidden) -->
                        <div class="lg:hidden p-4 space-y-3 divide-y divide-slate-100">
                            <div class="pt-2 first:pt-0 space-y-2 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-bold text-slate-900 text-sm truncate">Matematika Peminatan</h4>
                                    <span class="text-xs font-bold text-primary">Rata-rata: 76.4</span>
                                </div>
                                <div class="flex items-center justify-between text-xs text-slate-500">
                                    <span>KKM: <strong class="text-slate-700">75</strong></span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-20 bg-slate-200 rounded-full h-1.5"><div class="bg-amber-500 h-1.5 rounded-full" style="width: 55%"></div></div>
                                        <span class="font-semibold text-slate-600">55% >KKM</span>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-3 space-y-2 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-bold text-slate-900 text-sm truncate">Fisika</h4>
                                    <span class="text-xs font-bold text-primary">Rata-rata: 81.2</span>
                                </div>
                                <div class="flex items-center justify-between text-xs text-slate-500">
                                    <span>KKM: <strong class="text-slate-700">75</strong></span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-20 bg-slate-200 rounded-full h-1.5"><div class="bg-emerald-500 h-1.5 rounded-full" style="width: 82%"></div></div>
                                        <span class="font-semibold text-slate-600">82% >KKM</span>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-3 space-y-2 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-bold text-slate-900 text-sm truncate">Biologi</h4>
                                    <span class="text-xs font-bold text-primary">Rata-rata: 85.0</span>
                                </div>
                                <div class="flex items-center justify-between text-xs text-slate-500">
                                    <span>KKM: <strong class="text-slate-700">78</strong></span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-20 bg-slate-200 rounded-full h-1.5"><div class="bg-emerald-500 h-1.5 rounded-full" style="width: 90%"></div></div>
                                        <span class="font-semibold text-slate-600">90% >KKM</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Right Column: Alerts & Actions (≈30% -> lg:col-span-4) -->
            <div class="lg:col-span-4 space-y-6 sm:space-y-8 min-w-0">
                
                <!-- Critical Alerts -->
                <section class="min-w-0">
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-3 sm:mb-4 truncate">Intervensi Diperlukan</h2>
                    
                    <div class="bg-white border border-red-200/80 rounded-2xl p-4 sm:p-5 shadow-2xs min-w-0">
                        <div class="flex items-center gap-2 text-red-600 mb-3 min-w-0">
                            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <h3 class="font-bold text-xs sm:text-sm truncate">Peringatan Penurunan Nilai</h3>
                        </div>
                        
                        <div class="space-y-3 min-w-0">
                            <!-- Alert Item 1 -->
                            <div class="bg-red-50/80 p-3 rounded-xl border border-red-100 min-w-0">
                                <div class="flex justify-between items-start gap-2">
                                    <h4 class="font-bold text-slate-900 text-xs sm:text-sm truncate">11 IPS 1</h4>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded text-red-700 bg-red-200 shrink-0">-12%</span>
                                </div>
                                <p class="text-xs text-slate-600 mt-1 leading-relaxed break-words">Rata-rata nilai Sosiologi menurun drastis dalam 2 minggu terakhir.</p>
                                <a href="{{ route('waka.monitoring.grades', ['class_id' => 4, 'subject_id' => 1]) }}" class="inline-flex items-center gap-1 mt-2 text-[10px] uppercase tracking-wider font-bold text-red-600 hover:underline min-h-[44px]">Lihat Detail &rarr;</a>
                            </div>
                            
                            <!-- Alert Item 2 -->
                            <div class="bg-amber-50/80 p-3 rounded-xl border border-amber-100 min-w-0">
                                <div class="flex justify-between items-start gap-2">
                                    <h4 class="font-bold text-slate-900 text-xs sm:text-sm truncate">Keterlambatan Input</h4>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded text-amber-700 bg-amber-200 shrink-0">Urgent</span>
                                </div>
                                <p class="text-xs text-slate-600 mt-1 leading-relaxed break-words">3 Guru belum memasukkan nilai UTS untuk kelas 12 IPA.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Quick Reports -->
                <section class="min-w-0">
                    <h2 class="text-base font-bold text-slate-900 mb-3 sm:mb-4 truncate">Laporan Cepat</h2>
                    <div class="space-y-3 min-w-0">
                        <a href="{{ route('waka.monitoring.classes') }}" class="flex items-center justify-between p-3.5 sm:p-4 bg-white border border-slate-200/75 rounded-2xl hover:border-primary hover:shadow-2xs transition group min-w-0 min-h-[44px]">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="bg-slate-100 text-slate-500 p-2 rounded-xl group-hover:bg-blue-50 group-hover:text-primary transition shrink-0">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
                                </div>
                                <span class="text-xs sm:text-sm font-semibold text-slate-700 group-hover:text-primary transition truncate">Rekap Monitoring Kelas</span>
                            </div>
                            <svg class="h-4 w-4 text-slate-400 group-hover:text-primary shrink-0 ml-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                        </a>
                        <a href="{{ route('waka.monitoring.evaluations') }}" class="flex items-center justify-between p-3.5 sm:p-4 bg-white border border-slate-200/75 rounded-2xl hover:border-primary hover:shadow-2xs transition group min-w-0 min-h-[44px]">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="bg-slate-100 text-slate-500 p-2 rounded-xl group-hover:bg-blue-50 group-hover:text-primary transition shrink-0">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                </div>
                                <span class="text-xs sm:text-sm font-semibold text-slate-700 group-hover:text-primary transition truncate">Draft Evaluasi Semester</span>
                            </div>
                            <svg class="h-4 w-4 text-slate-400 group-hover:text-primary shrink-0 ml-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                        </a>
                    </div>
                </section>

            </div>
        </div>
        
    </div>
</x-layouts.app>

