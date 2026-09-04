<x-layouts.app>
    <x-slot:title>Input Penilaian Siswa</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Penilaian Siswa per Pertemuan" description="Input capaian siswa untuk setiap pertemuan agar perkembangan belajar dapat ditelusuri dari waktu ke waktu." />

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm text-emerald-800 flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-sm text-red-800">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <x-card padding="lg" class="mb-6">
            <form action="{{ route('guru.grades.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <x-input-label for="class_id" :value="__('Pilih Kelas')" />
                    <x-select id="class_id" name="class_id" required onchange="this.form.submit()">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($classes as $classItem)
                            <option value="{{ $classItem->id }}" {{ $selectedClassId == $classItem->id ? 'selected' : '' }}>{{ $classItem->name }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div>
                    <x-input-label for="subject_id" :value="__('Mata Pelajaran')" />
                    <x-select id="subject_id" name="subject_id" required onchange="this.form.submit()">
                        <option value="">-- Semua Mapel --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $selectedSubjectId == $subject->id ? 'selected' : '' }}>{{ $subject->name }} ({{ $subject->code }})</option>
                        @endforeach
                    </x-select>
                </div>
                <div>
                    <x-input-label for="meeting_id" :value="__('Pertemuan')" />
                    <x-select id="meeting_id" name="meeting_id" required onchange="this.form.submit()">
                        <option value="">-- Pilih Pertemuan --</option>
                        @foreach($meetings as $meeting)
                            <option value="{{ $meeting->id }}" {{ $selectedMeetingId == $meeting->id ? 'selected' : '' }}>
                                P{{ $meeting->meeting_number }} · {{ $meeting->meeting_date->format('d M Y') }} · {{ $meeting->topic }}
                            </option>
                        @endforeach
                    </x-select>
                </div>
                <div class="flex items-end">
                    <x-button variant="secondary" type="submit" class="w-full justify-center">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        Segarkan Data
                    </x-button>
                </div>
            </form>
        </x-card>

            @if($academicYear && $semester)
                <div class="mb-4 flex items-center justify-between text-sm bg-blue-50/50 p-4 rounded-lg border border-blue-100">
                    <span class="text-slate-600">Tahun Ajaran: <span class="font-bold text-slate-900">{{ $academicYear->year }}</span></span>
                    <span class="text-slate-600">Semester: <span class="font-bold text-slate-900">{{ $semester->name }}</span></span>
                </div>
            @else
                <div class="mb-4 p-4 text-sm text-red-700 font-semibold bg-red-50 border border-red-100 rounded-xl">Tahun ajaran atau semester aktif belum diatur oleh admin.</div>
            @endif

            <!-- Bulk Input Table -->
            @if($students->isNotEmpty() && $selectedMeetingId)
                <x-card padding="none">
                    <form action="{{ route('guru.grades.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                        <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}">
                        <input type="hidden" name="meeting_id" value="{{ $selectedMeetingId }}">
                        
                        <x-table>
                            <x-slot:head>
                                <tr>
                                    <th class="px-6 py-4 text-left w-64">Nama Siswa</th>
                                    <th class="px-4 py-4 w-28 text-center">Tes Awal</th>
                                    <th class="px-4 py-4 w-28 text-center">Tugas</th>
                                    <th class="px-4 py-4 w-28 text-center">Tes Akhir</th>
                                    <th class="px-4 py-4 w-28 text-center">Karakter</th>
                                    <th class="px-4 py-4 w-28 text-center">Hafalan</th>
                                    <th class="px-4 py-4 w-32 text-center">Juz</th>
                                    <th class="px-4 py-4 w-32 text-center">Ayat</th>
                                    <th class="px-4 py-4 min-w-52">Catatan</th>
                                </tr>
                            </x-slot:head>
                            <x-slot:body>
                                @foreach($students as $index => $student)
                                    @php
                                        $grade = $grades->get($student->id);
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 font-bold text-slate-950">
                                            {{ $student->user->name }}
                                            <input type="hidden" name="grades[{{ $index }}][student_id]" value="{{ $student->id }}">
                                        </td>
                                        <td class="px-4 py-4">
                                            <x-text-input type="number" name="grades[{{ $index }}][pre_test_score]" value="{{ old("grades.{$index}.pre_test_score", $grade?->pre_test_score) }}" min="0" max="100" class="w-full text-center" placeholder="-" />
                                        </td>
                                        <td class="px-4 py-4">
                                            <x-text-input type="number" name="grades[{{ $index }}][assignment_score]" value="{{ old("grades.{$index}.assignment_score", $grade?->assignment_score) }}" min="0" max="100" class="w-full text-center" placeholder="-" />
                                        </td>
                                        <td class="px-4 py-4">
                                            <x-text-input type="number" name="grades[{{ $index }}][post_test_score]" value="{{ old("grades.{$index}.post_test_score", $grade?->post_test_score) }}" min="0" max="100" class="w-full text-center" placeholder="-" />
                                        </td>
                                        <td class="px-4 py-4">
                                            <x-text-input type="number" name="grades[{{ $index }}][character_score]" value="{{ old("grades.{$index}.character_score", $grade?->character_score) }}" min="0" max="100" class="w-full text-center" placeholder="-" />
                                        </td>
                                        <td class="px-4 py-4">
                                            <x-text-input type="number" name="grades[{{ $index }}][memorization_score]" value="{{ old("grades.{$index}.memorization_score", $grade?->memorization_score) }}" min="0" max="100" class="w-full text-center" placeholder="-" />
                                        </td>
                                        <td class="px-4 py-4">
                                            <x-text-input name="grades[{{ $index }}][memorization_juz]" value="{{ old("grades.{$index}.memorization_juz", $grade?->memorization_juz) }}" class="w-full text-center" placeholder="Contoh: 30" />
                                        </td>
                                        <td class="px-4 py-4">
                                            <x-text-input name="grades[{{ $index }}][memorization_ayat]" value="{{ old("grades.{$index}.memorization_ayat", $grade?->memorization_ayat) }}" class="w-full text-center" placeholder="Contoh: 1–20" />
                                        </td>
                                        <td class="px-4 py-4">
                                            <x-text-input name="grades[{{ $index }}][notes]" value="{{ old("grades.{$index}.notes", $grade?->notes) }}" class="w-full" placeholder="Catatan singkat" />
                                        </td>
                                    </tr>
                                @endforeach
                            </x-slot:body>
                        </x-table>

                        <div class="p-6 bg-slate-50/50 border-t border-slate-100 flex justify-end">
                            <x-button variant="primary" type="submit">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Simpan Seluruh Nilai
                            </x-button>
                        </div>
                    </form>
                </x-card>
            @elseif($selectedClassId && $selectedSubjectId && !$selectedMeetingId)
                <x-card padding="lg" class="text-center py-12">
                    <h3 class="text-lg font-bold text-slate-900">Pilih Pertemuan</h3>
                    <p class="mt-1 text-sm text-slate-500">Buat pertemuan pembelajaran terlebih dahulu bila daftar pertemuan masih kosong.</p>
                    <x-button variant="secondary" href="{{ route('guru.learning-meetings.create') }}" class="mt-4">Tambah Pertemuan</x-button>
                </x-card>
            @elseif($selectedClassId && $selectedSubjectId)
                <x-card padding="lg" class="text-center py-16">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-lg bg-slate-100 text-slate-400 mb-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Tidak Ada Siswa</h3>
                    <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Tidak ada siswa yang terdaftar di kelas ini untuk tahun ajaran aktif.</p>
                </x-card>
            @else
                <x-card padding="lg" class="py-16 text-center text-slate-400 text-sm">
                    Silakan pilih <strong class="text-slate-600">Kelas</strong> dan <strong class="text-slate-600">Mata Pelajaran</strong> di atas untuk mulai memasukkan nilai.
                </x-card>
            @endif
    </div>
</x-layouts.app>
