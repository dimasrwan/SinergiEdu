<x-layouts.app>
    <x-slot:title>Analitik Mata Pelajaran</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Analitik Mata Pelajaran" description="Tingkat penguasaan setiap mata pelajaran beserta rincian per kelas." />

        <x-card padding="none">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Kode</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Karakter</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Hafalan</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Ketuntasan</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Rincian Per Kelas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($subjectAnalysis as $subject)
                            <tr class="hover:bg-slate-50 align-top">
                                <td class="py-4 px-4 font-semibold text-slate-900 text-sm">{{ $subject['name'] }}</td>
                                <td class="py-4 px-4 text-slate-500 text-xs font-bold">{{ $subject['code'] }}</td>
                                <td class="py-4 px-4 text-sm font-bold text-slate-900">{{ $subject['avg'] }}</td>
                                <td class="py-4 px-4 text-slate-600 text-sm">{{ $subject['avg_character'] }}</td>
                                <td class="py-4 px-4 text-slate-600 text-sm">{{ $subject['avg_memorization'] }}</td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-20 bg-slate-200 rounded-full h-1.5">
                                            <div class="{{ $subject['pass_rate'] >= 75 ? 'bg-emerald-500' : 'bg-amber-500' }} h-1.5 rounded-full" style="width: {{ min($subject['pass_rate'], 100) }}%"></div>
                                        </div>
                                        <span class="text-xs font-medium text-slate-600">{{ $subject['pass_rate'] }}%</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="space-y-1">
                                        @forelse($subject['per_class'] as $pc)
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="text-slate-500 w-24 truncate">{{ $pc['class_name'] }}</span>
                                                <div class="flex-1 bg-slate-100 rounded-lg h-1.5">
                                                    <div class="{{ $pc['avg'] >= 75 ? 'bg-emerald-500' : 'bg-amber-500' }} h-1.5 rounded-full" style="width: {{ min($pc['avg'], 100) }}%"></div>
                                                </div>
                                                <span class="font-semibold text-slate-700 w-8 text-right">{{ $pc['avg'] }}</span>
                                            </div>
                                        @empty
                                            <span class="text-xs text-slate-400">Belum ada data.</span>
                                        @endforelse
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-sm text-slate-500">Belum ada data mata pelajaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.app>