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
                    <button type="submit" class="w-full flex items-center justify-center bg-primary hover:bg-primary-hover active:scale-[0.98] text-white border border-transparent rounded-xl px-4 py-2.5 h-[42px] text-[14px] font-medium shadow-2xs transition-all focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <span>Lihat Detail</span>
                    </button>
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
                {{-- Mobile Cards --}}
                <div class="block lg:hidden divide-y divide-slate-100">
                    @forelse($rows as $row)
                        <div class="p-4 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="font-bold text-slate-900 text-base">{{ $row->subject_name }}</h3>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-100 text-blue-800 shrink-0">
                                    Rerata: {{ $row->avg }}
                                </span>
                            </div>

                            <div class="grid grid-cols-3 gap-2 pt-2 text-xs border-t border-slate-100">
                                <div class="bg-slate-50 p-2 rounded-lg text-center">
                                    <span class="text-slate-400 block text-[10px]">Pretest</span>
                                    <span class="font-semibold text-slate-800">{{ $row->avg_pre_test }}</span>
                                </div>
                                <div class="bg-slate-50 p-2 rounded-lg text-center">
                                    <span class="text-slate-400 block text-[10px]">Tugas</span>
                                    <span class="font-semibold text-slate-800">{{ $row->avg_assignment }}</span>
                                </div>
                                <div class="bg-slate-50 p-2 rounded-lg text-center">
                                    <span class="text-slate-400 block text-[10px]">Posttest</span>
                                    <span class="font-semibold text-slate-800">{{ $row->avg_post_test }}</span>
                                </div>
                                <div class="bg-slate-50 p-2 rounded-lg text-center">
                                    <span class="text-slate-400 block text-[10px]">Karakter</span>
                                    <span class="font-semibold text-slate-800">{{ $row->avg_character }}</span>
                                </div>
                                <div class="bg-slate-50 p-2 rounded-lg text-center col-span-2">
                                    <span class="text-slate-400 block text-[10px]">Hafalan</span>
                                    <span class="font-semibold text-slate-800">{{ $row->avg_memorization }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-sm text-slate-500">Belum ada nilai untuk siswa ini.</div>
                    @endforelse
                </div>

                {{-- Desktop Table --}}
                <div class="hidden lg:block">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
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
                                    <td class="p-6 font-semibold text-slate-900">{{ $row->subject_name }}</td>
                                    <td class="p-6 font-bold text-slate-900">{{ $row->avg }}</td>
                                    <td class="p-6 text-slate-600">{{ $row->avg_pre_test }}</td>
                                    <td class="p-6 text-slate-600">{{ $row->avg_assignment }}</td>
                                    <td class="p-6 text-slate-600">{{ $row->avg_post_test }}</td>
                                    <td class="p-6 text-slate-600">{{ $row->avg_character }}</td>
                                    <td class="p-6 text-slate-600">{{ $row->avg_memorization }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-10 text-center text-slate-500">Belum ada nilai untuk siswa ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">

                <!-- Siswa Berprestasi -->
                <section class="min-w-0">
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-4">Siswa Berprestasi</h2>
                    <x-card padding="none">
                        {{-- Mobile list --}}
                        <div class="block sm:hidden divide-y divide-slate-100">
                            @forelse($topStudents as $s)
                                <div class="p-4 flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="inline-flex items-center justify-center h-7 w-7 rounded-lg {{ $loop->iteration <= 3 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }} text-xs font-bold shrink-0">
                                            #{{ $loop->iteration }}
                                        </span>
                                        <p class="font-semibold text-slate-900 text-sm truncate">{{ $s->name }}</p>
                                    </div>
                                    <span class="text-sm font-bold text-slate-900 shrink-0">{{ $s->avg }}</span>
                                </div>
                            @empty
                                <div class="p-6 text-center text-sm text-slate-500">Belum ada data.</div>
                            @endforelse
                        </div>

                        {{-- Desktop table --}}
                        <div class="hidden sm:block">
                            <table class="w-full text-left border-collapse text-sm">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200">
                                        <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">#</th>
                                        <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama</th>
                                        <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($topStudents as $s)
                                        <tr class="hover:bg-slate-50">
                                            <td class="p-6"><span class="inline-flex items-center justify-center h-6 w-6 rounded-lg {{ $loop->iteration <= 3 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }} text-xs font-bold">{{ $loop->iteration }}</span></td>
                                            <td class="p-6 font-semibold text-slate-900">{{ $s->name }}</td>
                                            <td class="p-6 font-bold text-slate-900">{{ $s->avg }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="py-8 text-center text-slate-500">Belum ada data.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </x-card>
                </section>

                <!-- Siswa Perlu Perhatian -->
                <section class="min-w-0">
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-4">Perlu Perhatian</h2>
                    <x-card>
                        <div class="space-y-3">
                            @forelse($attentionStudents as $s)
                                <div class="flex items-center justify-between p-3 bg-red-50 rounded-xl border border-red-100 min-w-0">
                                    <p class="text-xs sm:text-sm font-semibold text-slate-800 truncate">{{ $s->name }}</p>
                                    <span class="text-xs font-bold text-red-600 shrink-0 ml-2">{{ $s->avg }}</span>
                                </div>
                            @empty
                                <p class="text-xs sm:text-sm text-slate-500">Tidak ada siswa yang membutuhkan perhatian khusus.</p>
                            @endforelse
                        </div>
                    </x-card>
                </section>
            </div>
        @endif
    </div>
</x-layouts.app>