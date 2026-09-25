<x-layouts.app>
    <x-slot:title>Detail Guru</x-slot:title>

    <div class="space-y-6">
        <div>
            <a href="{{ route('kepala-sekolah.supervision.teacher-report') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-primary gap-1.5 transition-colors mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Kembali ke Laporan Kinerja Guru
            </a>
            <x-page-header title="Detail Kinerja Guru" :description="$teacher->user?->name" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profil Guru -->
            <div class="space-y-6">
                <x-card>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="h-14 w-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-bold text-xl">{{ substr($teacher->user?->name ?? '?', 0, 1) }}</div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">{{ $teacher->user?->name }}</h2>
                            <p class="text-sm text-slate-500">NIP: {{ $teacher->nip ?? '-' }}</p>
                        </div>
                    </div>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Mata Pelajaran</dt>
                            <dd class="font-semibold text-slate-900">{{ $teacher->subjects->pluck('name')->join(', ') ?: '-' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Kelas Diampu</dt>
                            <dd class="font-semibold text-slate-900">{{ $teacher->classes->count() }}</dd>
                        </div>
                    </dl>
                </x-card>
            </div>

            <!-- Data Kelas -->
            <div class="lg:col-span-2">
                <x-card padding="none">
                    <div class="px-6 py-4 border-b border-slate-200">
                        <h2 class="text-base font-bold text-slate-900">Rekap Nilai per Kelas</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas</th>
                                    <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Jumlah Siswa</th>
                                    <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata</th>
                                    <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Karakter</th>
                                    <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Hafalan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($classRows as $row)
                                    <tr class="hover:bg-slate-50">
                                        <td class="p-6 font-semibold text-slate-900 text-sm">{{ $row->class_name }}</td>
                                        <td class="p-6 text-slate-600 text-sm">{{ $row->student_count }}</td>
                                        <td class="p-6 text-sm font-bold text-slate-900">{{ $row->avg }}</td>
                                        <td class="p-6 text-slate-600 text-sm">{{ $row->avg_character }}</td>
                                        <td class="p-6 text-slate-600 text-sm">{{ $row->avg_memorization }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-10 text-center text-sm text-slate-500">Belum ada data nilai untuk guru ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.app>