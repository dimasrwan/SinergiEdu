<x-layouts.app>
    <x-slot:title>Nilai Akademik Anak</x-slot:title>

    <div class="space-y-8">
        <x-page-header title="Nilai Akademik Anak" description="Pantau rekapitulasi nilai akhir semester anak-anak Anda di sini." />

        <x-card padding="lg" class="mb-8">
            <!-- Filter -->
            <form action="{{ route('orangtua.grades.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
            </form>
        </x-card>

        @forelse($children as $child)
            @php
                $grades = $childrenGrades[$child->id] ?? collect();
            @endphp
            <div class="mb-10 last:mb-0">
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-lg border border-blue-200">
                        {{ substr($child->user->name ?? 'A', 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">{{ $child->user->name ?? 'Anak Tidak Diketahui' }}</h2>
                        <p class="text-xs text-slate-500">Kelas Aktif: <span class="font-medium text-slate-700">{{ $child->activeClassroom()->name ?? 'Belum Terdaftar' }}</span></p>
                    </div>
                </div>

                @if($grades->isEmpty())
                    <x-card padding="lg" class="bg-slate-50/50 border-slate-200 border-dashed text-center">
                        <p class="text-sm text-slate-500">Belum ada data nilai untuk semester ini.</p>
                    </x-card>
                @else
                    <x-card padding="none">
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
                    </x-card>
                @endif
            </div>
        @empty
            <x-card padding="lg" class="text-center py-16">
                <p class="text-sm text-slate-500">Anda belum ditautkan dengan data anak (Siswa) mana pun.</p>
            </x-card>
        @endforelse
    </div>
</x-layouts.app>
