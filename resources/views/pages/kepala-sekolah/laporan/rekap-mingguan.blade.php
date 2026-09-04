<x-layouts.app>
    <x-slot:title>Rekap Mingguan</x-slot:title>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <x-page-header title="Rekap Mingguan" description="Ringkasan komponen nilai dan rangking kelas periode mingguan." />
            <a href="{{ route('kepala-sekolah.reports.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-primary gap-1.5 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Kembali
            </a>
        </div>

        <!-- Komponen Nilai -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            @php
                $components = [
                    ['label' => 'Pretest', 'key' => 'avg_pre_test', 'color' => 'bg-blue-50 text-blue-600 border-blue-100'],
                    ['label' => 'Tugas', 'key' => 'avg_assignment', 'color' => 'bg-emerald-50 text-emerald-600 border-emerald-100'],
                    ['label' => 'Posttest', 'key' => 'avg_post_test', 'color' => 'bg-violet-50 text-violet-600 border-violet-100'],
                    ['label' => 'Karakter', 'key' => 'avg_character', 'color' => 'bg-amber-50 text-amber-600 border-amber-100'],
                    ['label' => 'Hafalan', 'key' => 'avg_memorization', 'color' => 'bg-rose-50 text-rose-600 border-rose-100'],
                ];
            @endphp
            @foreach($components as $component)
                <div class="border border-slate-200 rounded-2xl p-4 {{ $component['color'] }}">
                    <p class="text-xs font-bold uppercase tracking-wider opacity-70">{{ $component['label'] }}</p>
                    <p class="text-2xl font-bold mt-1">{{ $componentAverages[$component['key']] }}</p>
                </div>
            @endforeach
        </div>

        <!-- Rangking Kelas -->
        <x-card padding="none">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-base font-bold text-slate-900">Rangking Kelas</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">#</th>
                            <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas</th>
                            <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tingkat</th>
                            <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($classRankings as $row)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-4"><span class="inline-flex items-center justify-center h-6 w-6 rounded-full {{ $loop->iteration <= 3 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }} text-xs font-bold">{{ $loop->iteration }}</span></td>
                                <td class="py-3 px-4 font-semibold text-slate-900 text-sm">{{ $row['name'] }}</td>
                                <td class="py-3 px-4 text-slate-600 text-sm">{{ $row['grade_level'] }}</td>
                                <td class="py-3 px-4 text-sm font-bold text-slate-900">{{ $row['avg'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-10 text-center text-sm text-slate-500">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.app>