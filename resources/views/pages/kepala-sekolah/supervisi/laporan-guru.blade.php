<x-layouts.app>
    <x-slot:title>Laporan Kinerja Guru</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Laporan Kinerja Guru" description="Rekapitulasi kinerja tenaga pendidik berdasarkan aktivitas penilaian, materi, dan feedback." />

        <x-card padding="none">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">#</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Guru</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">NIP</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Mapel</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas Diampu</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Rasio Penilaian</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Materi</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Feedback</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Skor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($teacherReports as $teacher)
                            <tr class="hover:bg-slate-50">
                                <td class="p-6 text-slate-500 text-sm">{{ $loop->iteration }}</td>
                                <td class="p-6">
                                    <a href="{{ route('kepala-sekolah.supervision.teacher-detail', $teacher->id) }}" class="font-semibold text-slate-900 text-sm hover:text-primary">{{ $teacher->name }}</a>
                                </td>
                                <td class="p-6 text-slate-600 text-sm">{{ $teacher->nip ?? '-' }}</td>
                                <td class="p-6 text-slate-600 text-sm">{{ $teacher->subjects ?? '-' }}</td>
                                <td class="p-6 text-slate-600 text-sm">{{ $teacher->classes_count }}</td>
                                <td class="p-6">
                                    <div class="flex items-center gap-2">
                                        <div class="w-20 bg-slate-200 rounded-full h-1.5">
                                            <div class="{{ $teacher->grading_ratio >= 75 ? 'bg-emerald-500' : 'bg-amber-500' }} h-1.5 rounded-full" style="width: {{ min($teacher->grading_ratio, 100) }}%"></div>
                                        </div>
                                        <span class="text-xs font-medium text-slate-600">{{ $teacher->grading_ratio }}%</span>
                                    </div>
                                </td>
                                <td class="p-6 text-slate-600 text-sm">{{ $teacher->materials_count }}</td>
                                <td class="p-6 text-slate-600 text-sm">{{ $teacher->feedbacks_count }}</td>
                                <td class="p-6">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $teacher->score >= 75 ? 'bg-emerald-100 text-emerald-700' : ($teacher->score >= 50 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">{{ $teacher->score }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-10 text-center text-sm text-slate-500">Belum ada data guru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.app>