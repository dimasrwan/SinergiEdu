<x-layouts.app>
    <x-slot:title>Rekap Bulanan</x-slot:title>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <x-page-header title="Rekap Bulanan" description="Analisis mata pelajaran dan performa kelas periode bulanan." />
            <a href="{{ route('kepala-sekolah.reports.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-primary gap-1.5 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Kembali
            </a>
        </div>

        <x-card padding="none">
            <div class="px-4 sm:px-6 py-4 border-b border-slate-200">
                <h2 class="text-base font-bold text-slate-900">Analisis Mata Pelajaran</h2>
            </div>
            
            {{-- Mobile Cards --}}
            <div class="block lg:hidden divide-y divide-slate-100">
                @forelse($subjectAnalysis as $subject)
                    <div class="p-4 space-y-2">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="font-bold text-slate-900 text-sm truncate">{{ $subject['name'] }}</h3>
                            <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 shrink-0">Rerata: {{ $subject['avg'] }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-xs pt-1">
                            <div class="bg-slate-50 p-2 rounded-lg">
                                <span class="text-slate-400 block text-[10px]">Karakter</span>
                                <span class="font-semibold text-slate-700">{{ $subject['avg_character'] }}</span>
                            </div>
                            <div class="bg-slate-50 p-2 rounded-lg">
                                <span class="text-slate-400 block text-[10px]">Hafalan</span>
                                <span class="font-semibold text-slate-700">{{ $subject['avg_memorization'] }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between gap-2 pt-1">
                            <span class="text-xs text-slate-500 font-medium">Ketuntasan:</span>
                            <div class="flex items-center gap-2">
                                <div class="w-20 bg-slate-200 rounded-full h-1.5">
                                    <div class="{{ $subject['pass_rate'] >= 75 ? 'bg-emerald-500' : 'bg-amber-500' }} h-1.5 rounded-full" style="width: {{ min($subject['pass_rate'], 100) }}%"></div>
                                </div>
                                <span class="text-xs font-bold text-slate-700">{{ $subject['pass_rate'] }}%</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-slate-500">Belum ada data.</div>
                @endforelse
            </div>

            {{-- Desktop Table --}}
            <div class="hidden lg:block">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Ketuntasan</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Karakter</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Hafalan</th>
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
                                <td class="p-6 text-slate-600 text-sm">{{ $subject['avg_character'] }}</td>
                                <td class="p-6 text-slate-600 text-sm">{{ $subject['avg_memorization'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-sm text-slate-500">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.app>