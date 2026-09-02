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
            <x-card padding="md" class="bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-100">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-xl bg-blue-200 flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-700" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-blue-700 uppercase">Siswa</p>
                        <p class="text-2xl font-bold text-blue-900">{{ number_format($totalStudents) }}</p>
                    </div>
                </div>
            </x-card>
            <x-card padding="md" class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-100">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-xl bg-emerald-200 flex items-center justify-center">
                        <svg class="h-6 w-6 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-emerald-700 uppercase">Guru</p>
                        <p class="text-2xl font-bold text-emerald-900">{{ number_format($totalTeachers) }}</p>
                    </div>
                </div>
            </x-card>
            <x-card padding="md" class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-100">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-xl bg-amber-200 flex items-center justify-center">
                        <svg class="h-6 w-6 text-amber-700" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-amber-700 uppercase">Sekolah</p>
                        <p class="text-2xl font-bold text-amber-900">{{ number_format($totalSchools) }}</p>
                    </div>
                </div>
            </x-card>
            <x-card padding="md" class="bg-gradient-to-br from-violet-50 to-purple-50 border border-violet-100">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-xl bg-violet-200 flex items-center justify-center">
                        <svg class="h-6 w-6 text-violet-700" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 3.75l-.259 1.035a3.375 3.375 0 0 0-2.456 2.456L15.75 6l1.036.259a3.375 3.375 0 0 0 2.456 2.456L21 6.75l-1.036.259a3.375 3.375 0 0 0-2.456 2.456Z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-violet-700 uppercase">Rata-rata Nilai</p>
                        <p class="text-2xl font-bold text-violet-900">{{ $avgScore ? number_format($avgScore, 1) : '-' }}</p>
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
                <a href="{{ route('pengawas.evaluations.index') }}" class="text-sm text-primary hover:text-primary-hover font-semibold transition">Lihat Semua</a>
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
                <p class="text-sm text-slate-500 text-center py-4">Belum ada evaluasi.</p>
            @endif
        </x-card>

        {{-- Inspeksi Terakhir --}}
        <x-card padding="md">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-slate-900">Jadwal Inspeksi Terakhir</h3>
                <a href="{{ route('pengawas.inspections.index') }}" class="text-sm text-primary hover:text-primary-hover font-semibold transition">Lihat Semua</a>
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
                <p class="text-sm text-slate-500 text-center py-4">Belum ada jadwal inspeksi.</p>
            @endif
        </x-card>
    </div>
</x-layouts.app>