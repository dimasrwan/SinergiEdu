<x-layouts.app>
    <x-slot:title>Laporan & Export</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Laporan & Export" description="Kelola rekap laporan berkala dan unduh berkas rekap nilai." />

        <!-- Filter Semester -->
        <x-card>
            <form method="GET" action="{{ route('kepala-sekolah.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <x-input-label for="semester_id" :value="__('Semester')" />
                    <x-semester-select id="semester_id" name="semester_id" onchange="this.form.submit()" :selected="$selectedSemester" empty-label="-- Semua Semester --" />
                </div>
            </form>
        </x-card>

        <!-- Tombol Aksi Laporan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('kepala-sekolah.reports.weekly') }}" class="p-5 bg-white border border-slate-200 rounded-2xl hover:border-primary hover:shadow-sm transition group">
                <div class="h-11 w-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4 group-hover:bg-blue-100 transition">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/></svg>
                </div>
                <h3 class="font-bold text-slate-900">Rekap Mingguan</h3>
                <p class="text-sm text-slate-500 mt-1">Ringkasan komponen nilai & rangking kelas mingguan.</p>
            </a>

            <a href="{{ route('kepala-sekolah.reports.monthly') }}" class="p-5 bg-white border border-slate-200 rounded-2xl hover:border-primary hover:shadow-sm transition group">
                <div class="h-11 w-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4 group-hover:bg-emerald-100 transition">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                </div>
                <h3 class="font-bold text-slate-900">Rekap Bulanan</h3>
                <p class="text-sm text-slate-500 mt-1">Analisis mata pelajaran & performa kelas bulanan.</p>
            </a>

            <a href="{{ route('kepala-sekolah.reports.semester') }}" class="p-5 bg-white border border-slate-200 rounded-2xl hover:border-primary hover:shadow-sm transition group">
                <div class="h-11 w-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4 group-hover:bg-blue-100 transition">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z"/></svg>
                </div>
                <h3 class="font-bold text-slate-900">Rekap Semester</h3>
                <p class="text-sm text-slate-500 mt-1">Ringkasan semester lengkap beserta laporan PDF.</p>
            </a>

            <a href="{{ route('kepala-sekolah.reports.export-rekap-excel') }}" class="p-5 bg-white border border-slate-200 rounded-2xl hover:border-emerald-500 hover:shadow-sm transition group">
                <div class="h-11 w-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4 group-hover:bg-amber-100 transition">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                </div>
                <h3 class="font-bold text-slate-900">Export Excel</h3>
                <p class="text-sm text-slate-500 mt-1">Unduh rekap nilai dalam format CSV/Excel.</p>
            </a>
        </div>

        <!-- Tabel Rekap -->
        <x-card padding="none">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-base font-bold text-slate-900">Rekap Nilai Siswa</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">#</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Pretest</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Tugas</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Posttest</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Karakter</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Hafalan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($rows as $row)
                            <tr class="hover:bg-slate-50">
                                <td class="p-6 text-slate-500 text-sm">{{ $loop->iteration }}</td>
                                <td class="p-6 font-semibold text-slate-900 text-sm">{{ $row['name'] }}</td>
                                <td class="p-6 text-slate-600 text-sm">{{ $row['class_name'] }}</td>
                                <td class="p-6 text-sm font-bold text-slate-900">{{ $row['avg'] }}</td>
                                <td class="p-6 text-slate-600 text-sm">{{ $row['avg_pre_test'] }}</td>
                                <td class="p-6 text-slate-600 text-sm">{{ $row['avg_assignment'] }}</td>
                                <td class="p-6 text-slate-600 text-sm">{{ $row['avg_post_test'] }}</td>
                                <td class="p-6 text-slate-600 text-sm">{{ $row['avg_character'] }}</td>
                                <td class="p-6 text-slate-600 text-sm">{{ $row['avg_memorization'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-10 text-center text-sm text-slate-500">Belum ada data nilai untuk ditampilkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.app>