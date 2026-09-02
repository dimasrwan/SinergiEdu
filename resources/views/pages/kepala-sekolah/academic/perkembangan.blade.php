<x-layouts.app>
    <x-slot:title>Perkembangan Siswa</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Perkembangan Siswa" description="Pantau perkembangan nilai siswa secara individual maupun per kelas." />

        <!-- Filter -->
        <x-card>
            <form method="GET" action="{{ route('kepala-sekolah.academic.perkembangan') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <x-input-label for="class_id" :value="__('Kelas')" />
                    <x-select id="class_id" name="class_id" onchange="this.form.submit()">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ (string) $classId === (string) $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div>
                    <x-input-label for="student_id" :value="__('Siswa')" />
                    <x-select id="student_id" name="student_id">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($studentList as $student)
                            <option value="{{ $student->id }}" {{ (string) $selectedStudent === (string) $student->id ? 'selected' : '' }}>{{ $student->user?->name }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div class="flex items-end">
                    <x-button variant="primary" type="submit" class="w-full">Lihat Detail</x-button>
                </div>
            </form>
        </x-card>

        @if($student)
            <!-- Detail Siswa Terpilih -->
            <x-card>
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold text-lg">{{ substr($student->user?->name ?? '?', 0, 1) }}</div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">{{ $student->user?->name }}</h2>
                            <p class="text-sm text-slate-500">NIS: {{ $student->nis }} • NISN: {{ $student->nisn }}</p>
                        </div>
                    </div>
                    <a href="{{ route('kepala-sekolah.academic.student-detail', $student) }}" class="inline-flex items-center text-sm font-semibold text-primary hover:text-primary/80">Lihat Detail Penuh &rarr;</a>
                </div>
            </x-card>

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
                            @forelse($rows as $row)
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
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- Siswa Berprestasi -->
                <section>
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Siswa Berprestasi</h2>
                    <x-card padding="none">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200">
                                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">#</th>
                                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama</th>
                                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($topStudents as $s)
                                        <tr class="hover:bg-slate-50">
                                            <td class="py-3 px-4"><span class="inline-flex items-center justify-center h-6 w-6 rounded-full {{ $loop->iteration <= 3 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }} text-xs font-bold">{{ $loop->iteration }}</span></td>
                                            <td class="py-3 px-4 font-semibold text-slate-900 text-sm">{{ $s->name }}</td>
                                            <td class="py-3 px-4 text-sm font-bold text-slate-900">{{ $s->avg }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="py-8 text-center text-sm text-slate-500">Belum ada data.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </x-card>
                </section>

                <!-- Siswa Perlu Perhatian -->
                <section>
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Perlu Perhatian</h2>
                    <x-card>
                        <div class="space-y-3">
                            @forelse($attentionStudents as $s)
                                <div class="flex items-center justify-between p-3 bg-red-50 rounded-xl border border-red-100">
                                    <p class="text-sm font-semibold text-slate-800">{{ $s->name }}</p>
                                    <span class="text-xs font-bold text-red-600">{{ $s->avg }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">Tidak ada siswa yang membutuhkan perhatian khusus.</p>
                            @endforelse
                        </div>
                    </x-card>
                </section>
            </div>
        @endif
    </div>
</x-layouts.app>