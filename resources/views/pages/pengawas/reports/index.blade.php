<x-layouts.app>
    <x-slot:title>Laporan Kinerja - Pengawas</x-slot:title>

    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <x-page-header title="Laporan Kinerja" description="Ringkasan kinerja akademik, evaluasi, dan jadwal inspeksi." />
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm text-emerald-800 flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Statistik Kartu --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-card padding="sm">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Siswa</span>
                        <h3 class="text-2xl font-bold tracking-tight text-slate-900 mt-1">{{ number_format($totalStudents) }}</h3>
                    </div>
                    <div class="text-blue-600 bg-blue-50/50 p-3 rounded-xl border border-blue-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
                    </div>
                </div>
            </x-card>

            <x-card padding="sm">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Guru</span>
                        <h3 class="text-2xl font-bold tracking-tight text-slate-900 mt-1">{{ number_format($totalTeachers) }}</h3>
                    </div>
                    <div class="text-emerald-600 bg-emerald-50/50 p-3 rounded-xl border border-emerald-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>
                    </div>
                </div>
            </x-card>

            <x-card padding="sm">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Sekolah</span>
                        <h3 class="text-2xl font-bold tracking-tight text-slate-900 mt-1">{{ number_format($totalSchools) }}</h3>
                    </div>
                    <div class="text-amber-600 bg-amber-50/50 p-3 rounded-xl border border-amber-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 22v-4a2 2 0 1 0-4 0v4"/><path d="m18 10 4 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-8l4-2"/><path d="M18 5v17"/><path d="m4 6 8-4 8 4"/><path d="M6 5v17"/><circle cx="12" cy="9" r="2"/></svg>
                    </div>
                </div>
            </x-card>

            <x-card padding="sm">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Rata-rata Nilai</span>
                        <h3 class="text-2xl font-bold tracking-tight text-slate-900 mt-1">{{ $avgScore ? number_format($avgScore, 1) : '-' }}</h3>
                    </div>
                    <div class="text-blue-600 bg-blue-50/50 p-3 rounded-xl border border-blue-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                    </div>
                </div>
            </x-card>
        </div>

        {{-- Statistik Tambahan --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-card padding="md" class="bg-slate-50 border-slate-200">
                <p class="text-xs font-semibold text-slate-500 uppercase">Total Grade Siswa</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($totalGrades) }}</p>
            </x-card>
            <x-card padding="md" class="bg-slate-50 border-slate-200">
                <p class="text-xs font-semibold text-slate-500 uppercase">Feedback Tersedia</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($feedbackGiven) }}</p>
            </x-card>
            <x-card padding="md" class="bg-slate-50 border-slate-200">
                <p class="text-xs font-semibold text-slate-500 uppercase">Tahun Ajaran Aktif</p>
                <p class="text-lg font-bold text-slate-900 mt-1">{{ $activeYear?->year ?? '-' }}</p>
                <p class="text-xs text-slate-500">{{ $activeSemester?->name ?? '-' }}</p>
            </x-card>
        </div>

        {{-- Evaluasi Terakhir --}}
        <x-card padding="md">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-slate-900">Evaluasi Terakhir</h3>
                <a href="{{ route('pengawas.evaluations.index') }}" class="text-sm text-primary hover:text-blue-700 font-semibold transition">Lihat Semua</a>
            </div>
            @if($evaluations->count())
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th class="px-6 py-4">Judul</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </x-slot:head>
                    <x-slot:body>
                        @foreach($evaluations as $eval)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $eval->title }}</td>
                                <td class="px-6 py-4"><x-badge variant="slate">{{ $eval->created_at->format('d M Y, H:i') }}</x-badge></td>
                                <td class="px-6 py-4 text-right"><a href="{{ route('pengawas.evaluations.edit', $eval) }}" class="text-sm text-primary hover:underline font-semibold">Edit</a></td>
                            </tr>
                        @endforeach
                    </x-slot:body>
                </x-table>
            @else
                <div class="flex flex-col items-center gap-2 py-4">
                    <svg class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <p class="text-sm text-slate-500 text-center">Belum ada evaluasi.</p>
                </div>
            @endif
        </x-card>

        {{-- Inspeksi Terakhir --}}
        <x-card padding="md">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-slate-900">Jadwal Inspeksi Terakhir</h3>
                <a href="{{ route('pengawas.inspections.index') }}" class="text-sm text-primary hover:text-blue-700 font-semibold transition">Lihat Semua</a>
            </div>
            @if($inspections->count())
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th class="px-6 py-4">Judul</th>
                            <th class="px-6 py-4">Sekolah</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </x-slot:head>
                    <x-slot:body>
                        @foreach($inspections as $inspection)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $inspection->title }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $inspection->school?->name ?? '-' }}</td>
                                <td class="px-6 py-4"><x-badge variant="slate">{{ $inspection->inspection_date?->format('d M Y') ?? '-' }}</x-badge></td>
                                <td class="px-6 py-4 text-right"><a href="{{ route('pengawas.inspections.show', $inspection) }}" class="text-sm text-primary hover:underline font-semibold">Lihat</a></td>
                            </tr>
                        @endforeach
                    </x-slot:body>
                </x-table>
            @else
                <div class="flex flex-col items-center gap-2 py-4">
                    <svg class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                    <p class="text-sm text-slate-500 text-center">Belum ada jadwal inspeksi.</p>
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>