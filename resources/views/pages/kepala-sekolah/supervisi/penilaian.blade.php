<x-layouts.app>
    <x-slot:title>Status Penilaian Guru</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Status Penilaian Guru" description="Pantau status kelengkapan penilaian guru per kelas dan mata pelajaran." />

        <!-- Statistik -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <x-card>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Kelas-Mapel</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $total }}</p>
            </x-card>
            <x-card>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Selesai Dinilai</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $completed }}</p>
            </x-card>
            <x-card>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Belum Dinilai</p>
                <p class="text-2xl font-bold text-orange-600 mt-1">{{ $pending }}</p>
            </x-card>
        </div>

        <x-card padding="none">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Guru</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($gradingStatus as $status)
                            <tr class="hover:bg-slate-50">
                                <td class="p-6">
                                    <a href="{{ route('kepala-sekolah.supervision.teacher-detail', $status->teacher_id) }}" class="font-semibold text-slate-900 text-sm hover:text-primary">{{ $status->teacher_name }}</a>
                                </td>
                                <td class="p-6 text-slate-600 text-sm">{{ $status->class_name }}</td>
                                <td class="p-6 text-slate-600 text-sm">{{ $status->subject_name }}</td>
                                <td class="p-6">
                                    @if($status->status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-700">Selesai</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-orange-100 text-amber-700">Belum Dinilai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-10 text-center text-sm text-slate-500">Belum ada data penilaian. Pastikan penugasan guru sudah diatur.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.app>