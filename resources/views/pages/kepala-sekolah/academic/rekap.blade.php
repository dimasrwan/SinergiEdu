<x-layouts.app>
    <x-slot:title>Rekapitulasi Nilai</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Rekapitulasi Nilai" description="Rekap rata-rata nilai siswa berdasarkan semester, kelas, dan mata pelajaran.">
            <x-slot:actions>
                <a href="{{ route('kepala-sekolah.reports.export-rekap-excel') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    Export Excel
                </a>
            </x-slot:actions>
        </x-page-header>

        <!-- Filter -->
        <x-card>
            <form method="GET" action="{{ route('kepala-sekolah.academic.rekap') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <x-input-label for="semester_id" :value="__('Semester')" />
                    <x-semester-select id="semester_id" name="semester_id" :selected="$semesterId" empty-label="-- Semua Semester --" />
                </div>
                <div>
                    <x-input-label for="class_id" :value="__('Kelas')" />
                    <x-select id="class_id" name="class_id">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ (string) $classId === (string) $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div>
                    <x-input-label for="subject_id" :value="__('Mata Pelajaran')" />
                    <x-select id="subject_id" name="subject_id">
                        <option value="">-- Semua Mapel --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ (string) $subjectId === (string) $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div class="flex items-end">
                    <x-button variant="primary" type="submit" class="w-full">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                        Terapkan Filter
                    </x-button>
                </div>
            </form>
        </x-card>

        <!-- Tabel Rekap -->
        <x-card padding="none">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Siswa</th>
                            <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">NISN</th>
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
                                <td class="p-6">
                                    <a href="{{ route('kepala-sekolah.academic.student-detail', $row['student_id']) }}" class="font-semibold text-slate-900 text-sm hover:text-primary">{{ $row['name'] }}</a>
                                </td>
                                <td class="p-6 text-slate-600 text-sm">{{ $row['nisn'] }}</td>
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
                                <td colspan="10" class="py-10 text-center text-sm text-slate-500">Belum ada data nilai untuk filter yang dipilih.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.app>