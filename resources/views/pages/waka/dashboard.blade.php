<x-layouts.app>
    <x-slot:title>Academic Analytics</x-slot:title>

    <div class="w-full space-y-8">
        
        <!-- Executive Summary Banner -->
        <div class="bg-primary rounded-2xl p-8 text-white shadow-xl shadow-primary/20 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight mb-2">Ikhtisar Kurikulum</h1>
                    <p class="text-blue-200 text-sm max-w-xl">Tahun Ajaran 2026/2027 • Semester Ganjil</p>
                </div>
                <div class="flex flex-wrap gap-4 shrink-0">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 min-w-[140px]">
                        <p class="text-xs text-blue-200 font-semibold uppercase tracking-wider mb-1">Rata-rata Sekolah</p>
                        <h3 class="text-2xl font-bold">{{ number_format($stats['avg_grade'], 1) }}</h3>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 min-w-[140px]">
                        <p class="text-xs text-blue-200 font-semibold uppercase tracking-wider mb-1">Total Pertemuan</p>
                        <h3 class="text-2xl font-bold">{{ $stats['meeting_count'] }}</h3>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 min-w-[140px]">
                        <p class="text-xs text-blue-200 font-semibold uppercase tracking-wider mb-1">Pencapaian Hafalan</p>
                        <h3 class="text-2xl font-bold">Juz {{ $stats['max_juz'] ?? '-' }} : {{ $stats['max_ayat'] ?? '-' }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Analytics & Charts -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Performance Comparison -->
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-slate-900">Perbandingan Performa Kelas (Paralel XI)</h2>
                        <div class="w-36">
                            <x-select :options="[
                                ['value' => '10', 'label' => 'Tingkat 10'],
                                ['value' => '11', 'label' => 'Tingkat 11'],
                                ['value' => '12', 'label' => 'Tingkat 12']
                            ]" selected="11" />
                        </div>
                    </div>
                    
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                        <!-- Mock Bar Chart -->
                        <div class="h-64 w-full flex items-end gap-4 px-2 pb-6 border-b border-slate-100 relative">
                            <!-- Y-Axis markers -->
                            <div class="absolute left-0 bottom-6 top-0 w-full flex flex-col justify-between pointer-events-none text-[10px] text-slate-400">
                                <div class="w-full border-b border-slate-100 border-dashed h-0 flex items-center"><span class="bg-white pr-2 -translate-y-2">100</span></div>
                                <div class="w-full border-b border-slate-100 border-dashed h-0 flex items-center"><span class="bg-white pr-2 -translate-y-2">80</span></div>
                                <div class="w-full border-b border-slate-100 border-dashed h-0 flex items-center"><span class="bg-white pr-2 -translate-y-2">60</span></div>
                            </div>
                            
                            <!-- Bars -->
                            <div class="flex-1 flex flex-col items-center group z-10 ml-8">
                                <div class="w-full max-w-[3rem] bg-blue-500 rounded-t-md h-[85%] group-hover:bg-blue-400 transition cursor-pointer relative">
                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">85.0</div>
                                </div>
                                <span class="text-xs font-semibold text-slate-600 mt-3 truncate w-full text-center">11 IPA 1</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center group z-10">
                                <div class="w-full max-w-[3rem] bg-blue-500 rounded-t-md h-[78%] group-hover:bg-blue-400 transition cursor-pointer relative">
                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">78.4</div>
                                </div>
                                <span class="text-xs font-semibold text-slate-600 mt-3 truncate w-full text-center">11 IPA 2</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center group z-10">
                                <div class="w-full max-w-[3rem] bg-blue-500 rounded-t-md h-[82%] group-hover:bg-blue-400 transition cursor-pointer relative">
                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">82.1</div>
                                </div>
                                <span class="text-xs font-semibold text-slate-600 mt-3 truncate w-full text-center">11 IPA 3</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center group z-10">
                                <div class="w-full max-w-[3rem] bg-sky-500 rounded-t-md h-[72%] group-hover:bg-sky-400 transition cursor-pointer relative">
                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">72.5</div>
                                </div>
                                <span class="text-xs font-semibold text-slate-600 mt-3 truncate w-full text-center">11 IPS 1</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center group z-10">
                                <div class="w-full max-w-[3rem] bg-sky-500 rounded-t-md h-[76%] group-hover:bg-sky-400 transition cursor-pointer relative">
                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">76.0</div>
                                </div>
                                <span class="text-xs font-semibold text-slate-600 mt-3 truncate w-full text-center">11 IPS 2</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Subject Performance Matrix -->
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-slate-900">Matriks Ketercapaian Mata Pelajaran</h2>
                        <a href="{{ route('waka.analytics.index') }}" class="text-sm font-medium text-accent hover:text-accent-hover">Buka Analitik Penuh</a>
                    </div>
                    
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
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
                                                <div class="w-24 bg-slate-200 rounded-full h-1.5"><div class="bg-success h-1.5 rounded-full" style="width: 82%"></div></div>
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
                                                <div class="w-24 bg-slate-200 rounded-full h-1.5"><div class="bg-success h-1.5 rounded-full" style="width: 90%"></div></div>
                                                <span class="text-xs font-medium text-slate-600">90%</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Right Column: Alerts & Actions -->
            <div class="col-span-1 space-y-8">
                
                <!-- Critical Alerts -->
                <section>
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Intervensi Diperlukan</h2>
                    
                    <div class="bg-white border border-red-200 rounded-2xl p-5 shadow-sm shadow-sm-100/50">
                        <div class="flex items-center gap-2 text-red-600 mb-3">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <h3 class="font-bold text-sm">Peringatan Penurunan Nilai</h3>
                        </div>
                        
                        <div class="space-y-3">
                            <!-- Alert Item 1 -->
                            <div class="bg-red-50 p-3 rounded-xl border border-red-100">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-bold text-slate-900 text-sm">11 IPS 1</h4>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded text-red-700 bg-red-200">-12%</span>
                                </div>
                                <p class="text-xs text-slate-600 mt-1">Rata-rata nilai Sosiologi menurun drastis dalam 2 minggu terakhir.</p>
                                <a href="{{ route('waka.monitoring.grades', ['class_id' => 4, 'subject_id' => 1]) }}" class="inline-block mt-2 text-[10px] uppercase tracking-wider font-bold text-red-600 hover:underline">Lihat Detail &rarr;</a>
                            </div>
                            
                            <!-- Alert Item 2 -->
                            <div class="bg-amber-50 p-3 rounded-xl border border-amber-100">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-bold text-slate-900 text-sm">Keterlambatan Input</h4>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded text-amber-700 bg-amber-200">Urgent</span>
                                </div>
                                <p class="text-xs text-slate-600 mt-1">3 Guru belum memasukkan nilai UTS untuk kelas 12 IPA.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Quick Reports -->
                <section>
                    <h2 class="text-base font-bold text-slate-900 mb-4">Laporan Cepat</h2>
                    <div class="space-y-3">
                        <a href="{{ route('waka.monitoring.classes') }}" class="flex items-center justify-between p-4 bg-white border border-slate-200 rounded-lg hover:border-accent hover:shadow-sm transition group">
                            <div class="flex items-center gap-3">
                                <div class="bg-slate-100 text-slate-500 p-2 rounded-lg group-hover:bg-blue-50 group-hover:text-accent transition">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
                                </div>
                                <span class="text-sm font-semibold text-slate-700">Rekap Monitoring Kelas</span>
                            </div>
                            <svg class="h-4 w-4 text-slate-400 group-hover:text-accent" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                        </a>
                        <a href="{{ route('waka.monitoring.evaluations') }}" class="flex items-center justify-between p-4 bg-white border border-slate-200 rounded-lg hover:border-accent hover:shadow-sm transition group">
                            <div class="flex items-center gap-3">
                                <div class="bg-slate-100 text-slate-500 p-2 rounded-lg group-hover:bg-blue-50 group-hover:text-accent transition">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                </div>
                                <span class="text-sm font-semibold text-slate-700">Draft Evaluasi Semester</span>
                            </div>
                            <svg class="h-4 w-4 text-slate-400 group-hover:text-accent" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                        </a>
                    </div>
                </section>

            </div>
        </div>
        
    </div>
</x-layouts.app>
