<x-layouts.app>
    <x-slot:title>Rekap Semester</x-slot:title>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <x-page-header title="Rekap Semester" description="Ringkasan lengkap performa akademik satu semester." />
            <div class="flex gap-3">
                <a href="{{ route('kepala-sekolah.reports.export-semester-pdf') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    Download PDF
                </a>
                <a href="{{ route('kepala-sekolah.reports.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-primary gap-1.5 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Rata-rata Sekolah -->
        <div class="bg-primary rounded-2xl p-8 text-white shadow-xl shadow-primary/20 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <p class="text-xs text-blue-200 font-semibold uppercase tracking-wider mb-1">Rata-rata Sekolah</p>
                    <h3 class="text-2xl font-bold">{{ $schoolAvgGrade }}</h3>
                </div>
                <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                    @php
                        $components = [
                            ['label' => 'Pretest', 'key' => 'avg_pre_test'],
                            ['label' => 'Tugas', 'key' => 'avg_assignment'],
                            ['label' => 'Posttest', 'key' => 'avg_post_test'],
                            ['label' => 'Karakter', 'key' => 'avg_character'],
                            ['label' => 'Hafalan', 'key' => 'avg_memorization'],
                        ];
                    @endphp
                    @foreach($components as $component)
                        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-3 text-center">
                            <p class="text-xs text-blue-200 font-semibold uppercase tracking-wider">{{ $component['label'] }}</p>
                            <h4 class="text-xl font-bold mt-1">{{ $componentAverages[$component['key']] }}</h4>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Rangking Kelas -->
            <x-card padding="none">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="text-base font-bold text-slate-900">Rangking Kelas</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">#</th>
                                <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas</th>
                                <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($classRankings as $row)
                                <tr class="hover:bg-slate-50">
                                    <td class="p-6"><span class="inline-flex items-center justify-center h-6 w-6 rounded-lg {{ $loop->iteration <= 3 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }} text-xs font-bold">{{ $loop->iteration }}</span></td>
                                    <td class="p-6 font-semibold text-slate-900 text-sm">{{ $row['name'] }}</td>
                                    <td class="p-6 text-sm font-bold text-slate-900">{{ $row['avg'] }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="py-10 text-center text-sm text-slate-500">Belum ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>

            <!-- Analisis Mapel -->
            <x-card padding="none">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="text-base font-bold text-slate-900">Analisis Mata Pelajaran</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Mapel</th>
                                <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata</th>
                                <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Ketuntasan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($subjectAnalysis as $subject)
                                <tr class="hover:bg-slate-50">
                                    <td class="p-6 font-semibold text-slate-900 text-sm">{{ $subject['name'] }}</td>
                                    <td class="p-6 text-sm font-bold text-slate-900">{{ $subject['avg'] }}</td>
                                    <td class="p-6">
                                        <div class="flex items-center gap-2">
                                            <div class="w-20 bg-slate-200 rounded-full h-1.5">
                                                <div class="{{ $subject['pass_rate'] >= 75 ? 'bg-emerald-500' : 'bg-amber-500' }} h-1.5 rounded-full" style="width: {{ min($subject['pass_rate'], 100) }}%"></div>
                                            </div>
                                            <span class="text-xs font-medium text-slate-600">{{ $subject['pass_rate'] }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="py-10 text-center text-sm text-slate-500">Belum ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</x-layouts.app>