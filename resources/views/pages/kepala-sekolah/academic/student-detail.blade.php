<x-layouts.app>
    <x-slot:title>Detail Siswa</x-slot:title>

    <div class="space-y-6">
        <div>
            <a href="{{ route('kepala-sekolah.academic.perkembangan') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-primary gap-1.5 transition-colors mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Kembali ke Perkembangan Siswa
            </a>
            <x-page-header title="Detail Siswa" :description="$student->user?->name . ' — NIS: ' . $student->nis . ' • NISN: ' . $student->nisn" />
        </div>

        <x-card padding="none">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
                            <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata</th>
                            <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Pretest</th>
                            <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tugas</th>
                            <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Posttest</th>
                            <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Karakter</th>
                            <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Hafalan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($subjectRows as $row)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-4 font-semibold text-slate-900 text-sm">{{ $row->subject_name }}</td>
                                <td class="py-3 px-4 text-sm font-bold text-slate-900">{{ $row->avg }}</td>
                                <td class="py-3 px-4 text-slate-600 text-sm">{{ $row->avg_pre_test }}</td>
                                <td class="py-3 px-4 text-slate-600 text-sm">{{ $row->avg_assignment }}</td>
                                <td class="py-3 px-4 text-slate-600 text-sm">{{ $row->avg_post_test }}</td>
                                <td class="py-3 px-4 text-slate-600 text-sm">{{ $row->avg_character }}</td>
                                <td class="py-3 px-4 text-slate-600 text-sm">{{ $row->avg_memorization }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-sm text-slate-500">Belum ada nilai untuk siswa ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.app>