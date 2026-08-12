<x-layouts.app>
    <x-slot:title>Nilai Akademik</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Nilai Akademik" description="Lihat rekapitulasi nilai Anda berdasarkan tahun ajaran dan semester." />

        <x-card padding="lg" class="mb-6">
            <!-- Filter -->
            <form action="{{ route('siswa.grades.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <x-input-label for="academic_year_id" :value="__('Tahun Ajaran')" />
                    <x-select id="academic_year_id" name="academic_year_id" onchange="this.form.submit()">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $selectedAcademicYearId == $year->id ? 'selected' : '' }}>
                                {{ $year->year }} {{ $year->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </x-select>
                </div>
                <div>
                    <x-input-label for="semester_id" :value="__('Semester')" />
                    <x-select id="semester_id" name="semester_id" onchange="this.form.submit()">
                        @foreach($semesters as $sem)
                            <option value="{{ $sem->id }}" {{ $selectedSemesterId == $sem->id ? 'selected' : '' }}>
                                {{ $sem->name }} {{ $sem->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </x-select>
                </div>
                <div class="hidden md:flex items-end text-sm text-slate-400 pb-2">
                    <p>Pilih filter untuk melihat riwayat nilai.</p>
                </div>
            </form>
        </x-card>

        @if($grades->isEmpty())
            <x-card padding="lg" class="text-center py-16">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Belum Ada Nilai</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Guru belum mengunggah rekapitulasi nilai untuk Anda di periode yang dipilih.</p>
            </x-card>
        @else
        <x-table>
            <x-slot:head>
                <tr>
                    <th class="px-6 py-4 text-left">Mata Pelajaran</th>
                    <th class="px-4 py-4 w-24 text-center">Tes Awal</th>
                    <th class="px-4 py-4 w-24 text-center">Tugas</th>
                    <th class="px-4 py-4 w-24 text-center">Tes Akhir</th>
                    <th class="px-4 py-4 w-24 text-center">Karakter</th>
                    <th class="px-4 py-4 w-24 text-center">Hafalan</th>
                    <th class="px-6 py-4 w-32 text-center">Rata-Rata</th>
                </tr>
            </x-slot:head>
            <x-slot:body>
                @foreach($grades as $grade)
                    <tr class="hover:bg-slate-50/50 transition-colors text-center group">
                        <td class="px-6 py-4 text-left">
                            <div class="font-bold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $grade->subject->name ?? '-' }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">Guru: <span class="font-medium text-slate-600">{{ $grade->teacher->user->name ?? '-' }}</span></div>
                        </td>
                        <td class="px-4 py-4 font-semibold text-slate-700">{{ $grade->pre_test_score ?? '-' }}</td>
                        <td class="px-4 py-4 font-semibold text-slate-700">{{ $grade->assignment_score ?? '-' }}</td>
                        <td class="px-4 py-4 font-semibold text-slate-700">{{ $grade->post_test_score ?? '-' }}</td>
                        <td class="px-4 py-4 font-semibold text-slate-700">{{ $grade->character_score ?? '-' }}</td>
                        <td class="px-4 py-4 font-semibold text-slate-700">{{ $grade->memorization_score ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @php
                                $avg = $grade->average_score;
                            @endphp
                            <div class="flex items-center justify-center gap-2">
                                <span class="inline-flex items-center justify-center h-10 w-12 rounded-xl {{ $avg >= 80 ? 'bg-emerald-100 text-emerald-700 shadow-sm shadow-emerald-200' : ($avg >= 60 ? 'bg-amber-100 text-amber-700 shadow-sm shadow-amber-200' : ($avg > 0 ? 'bg-red-100 text-red-700 shadow-sm shadow-red-200' : 'bg-slate-100 text-slate-600')) }} font-bold text-base border border-white">
                                    {{ $avg > 0 ? $avg : '-' }}
                                </span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-slot:body>
        </x-table>
        @endif
    </div>
</x-layouts.app>
