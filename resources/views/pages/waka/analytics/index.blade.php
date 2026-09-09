<x-layouts.app>
    <x-slot:title>Analitik Akademik</x-slot:title>
    
    <div class="space-y-6">
        <x-page-header title="Analitik Akademik Sekolah" description="Monitoring tren performa pembelajaran dan komparasi rerata nilai antar mata pelajaran." />

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-card class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-slate-900 text-base">Tren Rerata Akademik</h2>
                        <p class="text-xs text-slate-500">Rekap per semester / periode berjalan</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                        {{ count($performanceTrend) }} Periode Recorded
                    </span>
                </div>

                <div class="space-y-3 pt-2">
                    @forelse($performanceTrend as $idx => $val)
                        <div>
                            <div class="flex justify-between text-xs font-semibold text-slate-700 mb-1">
                                <span>Periode T{{ $idx + 1 }}</span>
                                <span class="font-mono text-blue-700">{{ $val }}</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ min(100, max(0, $val)) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-4">Belum ada data tren performa.</p>
                    @endforelse
                </div>
            </x-card>

            <x-card class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-slate-900 text-base">Komparasi Mata Pelajaran</h2>
                        <p class="text-xs text-slate-500">Perbandingan rata-rata capaian nilai mapel</p>
                    </div>
                </div>

                <div class="space-y-3.5 pt-2">
                    @forelse($subjectComparison as $sub)
                        @php
                            $avgVal = is_array($sub) ? ($sub['avg'] ?? 0) : ($sub->avg ?? 0);
                            $nameVal = is_array($sub) ? ($sub['name'] ?? '-') : ($sub->name ?? '-');
                        @endphp
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-900 truncate pr-2">{{ $nameVal }}</span>
                                <span class="font-mono font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">{{ $avgVal }}</span>
                            </div>
                            <div class="w-full bg-slate-200/70 rounded-full h-2 overflow-hidden">
                                <div class="bg-emerald-600 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, max(0, $avgVal)) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-4">Belum ada data komparasi mapel.</p>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>
</x-layouts.app>