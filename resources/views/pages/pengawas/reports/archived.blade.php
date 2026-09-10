<x-layouts.app>
    <x-slot:title>Laporan Arsip - Pengawas</x-slot:title>

    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <x-page-header title="Laporan Arsip" description="Daftar laporan dan feedback yang telah diarsipkan." />
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm text-emerald-800 flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <x-card padding="none">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-4 py-4 text-left font-semibold text-slate-700">Siswa</th>
                            <th class="px-4 py-4 text-left font-semibold text-slate-700">Mata Pelajaran</th>
                            <th class="px-4 py-4 text-center font-semibold text-slate-700">Rata-rata</th>
                            <th class="px-4 py-4 text-left font-semibold text-slate-700">Feedback</th>
                            <th class="px-4 py-4 text-center font-semibold text-slate-700">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($archivedGrades as $grade)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-4 py-4">
                                    <div class="font-medium text-slate-900">{{ $grade->student?->user?->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $grade->student?->nis }}</div>
                                </td>
                                <td class="px-4 py-4 text-slate-600">{{ $grade->subject?->name ?? '-' }}</td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg text-sm font-semibold
                                        {{ $grade->average_score >= 80 ? 'bg-emerald-100 text-emerald-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ number_format($grade->average_score, 1) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-600 truncate max-w-xs">
                                    {{ $grade->supervisor_feedback ?? '-' }}
                                </td>
                                <td class="px-4 py-4 text-center text-slate-500">
                                    {{ $grade->updated_at?->format('d M Y') ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                    <p class="text-sm">Belum ada laporan yang diarsipkan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($archivedGrades->hasPages())
                <div class="p-6 border-t border-slate-100">
                    {{ $archivedGrades->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>