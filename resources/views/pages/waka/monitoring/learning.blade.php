<x-layouts.app>
    <x-slot:title>Monitoring Pembelajaran</x-slot:title>

    <div class="space-y-6">
        <x-page-header
            title="Monitoring Pembelajaran"
            description="Pantau materi yang diunggah guru, tugas awal, serta tingkat pengumpulan jawaban dari seluruh kelas."
        />

        <x-card padding="lg">
            <form method="GET" action="{{ route('waka.monitoring.learning') }}" class="grid grid-cols-1 gap-4 md:grid-cols-5">
                <div>
                    <x-input-label for="class_id" value="Kelas" class="mb-1.5" />
                    <x-select id="class_id" name="class_id">
                        <option value="">Semua kelas</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" @selected(($filters['class_id'] ?? null) == $class->id)>{{ $class->name }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div>
                    <x-input-label for="subject_id" value="Mapel" class="mb-1.5" />
                    <x-select id="subject_id" name="subject_id">
                        <option value="">Semua mapel</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(($filters['subject_id'] ?? null) == $subject->id)>{{ $subject->name }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div>
                    <x-input-label for="academic_year_id" value="Tahun" class="mb-1.5" />
                    <x-select id="academic_year_id" name="academic_year_id">
                        <option value="">Semua Tahun</option>
                        @foreach ($academicYears as $year)
                            <option value="{{ $year->id }}" @selected(($filters['academic_year_id'] ?? null) == $year->id)>{{ $year->year }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div>
                    <x-input-label for="semester_id" value="Semester" class="mb-1.5" />
                    <x-select id="semester_id" name="semester_id">
                        <option value="">Semua Semester</option>
                        @foreach ($semesters as $semester)
                            <option value="{{ $semester->id }}" @selected(($filters['semester_id'] ?? null) == $semester->id)>{{ $semester->name }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div class="flex items-end gap-2">
                    <x-button variant="primary" type="submit" class="flex-1 justify-center">Filter</x-button>
                    @if (! empty($filters))
                        <x-button variant="secondary" href="{{ route('waka.monitoring.learning') }}">Reset</x-button>
                    @endif
                </div>
            </form>
        </x-card>

        <section class="space-y-4">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Materi Pembelajaran</h2>
                    <p class="text-sm text-slate-500">Ringkasan materi, PDF, dan video yang dipublikasikan guru.</p>
                </div>
                <x-badge variant="primary">{{ $materials->total() }} materi</x-badge>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($materials as $material)
                    <x-card padding="lg" class="flex flex-col">
                        <div class="mb-3 flex items-start justify-between gap-3">
                            <x-badge variant="primary">{{ $material->subject->name ?? '-' }}</x-badge>
                            <span class="text-xs text-slate-400">{{ $material->created_at->format('d M Y') }}</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">{{ $material->title }}</h3>
                        <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">{{ $material->description ?: 'Guru belum menambahkan ringkasan materi.' }}</p>
                        <div class="mt-4 border-t border-slate-100 pt-4 text-xs text-slate-500">
                            <p><span class="font-semibold text-slate-700">Guru:</span> {{ $material->teacher->user->name ?? '-' }}</p>
                            <p class="mt-1"><span class="font-semibold text-slate-700">Kelas:</span> {{ $material->classroom->name ?? '-' }}</p>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @if ($material->file_path)
                                <a href="{{ asset('storage/'.$material->file_path) }}" target="_blank" class="inline-flex items-center rounded-lg-lg bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100">Buka PDF</a>
                            @endif
                            @if ($material->video_path)
                                <a href="{{ asset('storage/'.$material->video_path) }}" target="_blank" class="inline-flex items-center rounded-lg-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-100">Putar Video</a>
                            @endif
                            @if (! $material->file_path && ! $material->video_path)
                                <span class="text-xs italic text-slate-400">Materi teks</span>
                            @endif
                        </div>
                    </x-card>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-12 text-center text-sm text-slate-500">
                        Tidak ada materi yang sesuai dengan filter.
                    </div>
                @endforelse
            </div>
            @if ($materials->hasPages())
                <div>{{ $materials->links() }}</div>
            @endif
        </section>

        <section class="space-y-4 pt-2">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Tugas dan Pengumpulan Siswa</h2>
                    <p class="text-sm text-slate-500">Buka detail tugas untuk memeriksa dokumen jawaban dan partisipasi setiap siswa.</p>
                </div>
                <x-badge variant="primary">{{ $assignments->total() }} tugas</x-badge>
            </div>

            <x-card padding="none">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[780px] text-left text-sm">
                        <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-6 py-4">Tugas</th>
                                <th class="px-6 py-4">Guru / Kelas</th>
                                <th class="px-6 py-4">Mata Pelajaran</th>
                                <th class="px-6 py-4">Tenggat</th>
                                <th class="px-6 py-4 text-center">Terkumpul</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($assignments as $assignment)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-6 py-4 font-semibold text-slate-900">{{ $assignment->title }}</td>
                                    <td class="px-6 py-4 text-slate-600">
                                        <p>{{ $assignment->teacher->user->name ?? '-' }}</p>
                                        <p class="mt-0.5 text-xs text-slate-400">{{ $assignment->classroom->name ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">{{ $assignment->subject->name ?? '-' }}</td>
                                    <td class="px-6 py-4 {{ now()->isAfter($assignment->deadline) ? 'text-red-600' : 'text-slate-600' }}">{{ $assignment->deadline->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 text-center font-bold text-blue-700">{{ $assignment->submissions_count }}</td>
                                    <td class="px-6 py-4 text-right"><a href="{{ route('waka.monitoring.assignments.show', $assignment) }}" class="text-xs font-semibold text-blue-700 hover:text-blue-900">Lihat jawaban →</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-12 text-center text-slate-500">Tidak ada tugas yang sesuai dengan filter.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
            @if ($assignments->hasPages())
                <div>{{ $assignments->links() }}</div>
            @endif
        </section>
    </div>
</x-layouts.app>
