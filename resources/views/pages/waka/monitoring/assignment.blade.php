<x-layouts.app>
    <x-slot:title>Monitoring Tugas</x-slot:title>

    @php($submissionByStudent = $assignment->submissions->keyBy('student_id'))

    <div class="space-y-6">
        <a href="{{ route('waka.monitoring.learning') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800">← Kembali ke Monitoring Pembelajaran</a>

        <x-page-header title="{{ $assignment->title }}" description="Periksa instruksi tugas dan jawaban yang diunggah siswa. Waka memiliki akses baca saja." />

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="space-y-6 xl:col-span-1">
                <x-card padding="lg">
                    <h2 class="text-sm font-bold uppercase tracking-wide text-slate-500">Informasi Tugas</h2>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div><dt class="text-slate-400">Guru</dt><dd class="font-semibold text-slate-800">{{ $assignment->teacher->user->name ?? '-' }}</dd></div>
                        <div><dt class="text-slate-400">Kelas</dt><dd class="font-semibold text-slate-800">{{ $assignment->classroom->name ?? '-' }}</dd></div>
                        <div><dt class="text-slate-400">Mata pelajaran</dt><dd class="font-semibold text-slate-800">{{ $assignment->subject->name ?? '-' }}</dd></div>
                        @if($assignment->learningMeeting)
                            <div><dt class="text-slate-400">Pertemuan</dt><dd class="font-semibold text-slate-800">Pertemuan {{ $assignment->learningMeeting->meeting_number }} ({{ $assignment->learningMeeting->topic }})</dd></div>
                        @endif
                        @if($assignment->material)
                            <div><dt class="text-slate-400">Materi Terkait</dt><dd class="font-semibold text-slate-800">{{ $assignment->material->title }}</dd></div>
                        @endif
                        <div><dt class="text-slate-400">Tenggat</dt><dd class="font-semibold {{ now()->isAfter($assignment->deadline) ? 'text-red-600' : 'text-slate-800' }}">{{ $assignment->deadline->format('d M Y, H:i') }}</dd></div>
                    </dl>
                    <div class="mt-5 border-t border-slate-100 pt-5 text-sm leading-relaxed text-slate-700 whitespace-pre-wrap">{{ $assignment->description }}</div>
                    @if ($assignment->attachment_path)
                        <div class="mt-5 pt-4 border-t border-slate-100 flex flex-wrap items-center gap-2">
                            <a href="{{ route('waka.monitoring.assignments.preview', $assignment) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1.5 min-h-[38px] rounded-xl bg-blue-50 px-3 py-1.5 text-xs font-bold text-primary hover:bg-blue-100 transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Lihat Lampiran
                            </a>
                            <a href="{{ route('waka.monitoring.assignments.download', $assignment) }}"
                                class="inline-flex items-center gap-1 min-h-[38px] rounded-xl bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.5V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                Unduh
                            </a>
                        </div>
                    @endif
                </x-card>

                <x-card padding="lg">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Partisipasi Pengumpulan</p>
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </div>

                    @php($submissionRate = $enrolledStudents->isNotEmpty() ? min(100, round(($assignment->submissions->count() / $enrolledStudents->count()) * 100)) : 0)

                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $assignment->submissions->count() }}</span>
                        <span class="text-sm font-semibold text-slate-400">/ {{ $enrolledStudents->count() }} siswa</span>
                    </div>

                    <p class="mt-1 text-sm font-medium text-slate-600">
                        <span class="font-bold text-slate-800">{{ $submissionRate }}%</span> siswa sudah mengumpulkan.
                    </p>

                    @if ($enrolledStudents->isNotEmpty())
                        <div class="mt-4 w-full bg-slate-100 h-2.5 rounded-full overflow-hidden border border-slate-200/60">
                            <div class="bg-primary h-full rounded-full transition-all duration-300" style="width: {{ $submissionRate }}%"></div>
                        </div>
                    @endif
                </x-card>
            </div>

            <div class="xl:col-span-2">
                <x-card padding="none" class="overflow-hidden border border-slate-200/75 min-w-0">
                    <div class="border-b border-slate-100 p-4 sm:p-6 min-w-0">
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 truncate">Status dan Jawaban Siswa</h2>
                    </div>
                    <!-- Desktop Table (hidden lg:block) -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-6 py-4">Siswa</th><th class="px-6 py-4">Status</th><th class="px-6 py-4">Waktu Kumpul</th><th class="px-6 py-4">Berkas / Catatan</th></tr></thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($enrolledStudents as $student)
                                    @php($submission = $submissionByStudent->get($student->id))
                                        <tr class="hover:bg-slate-50/70">
                                            <td class="px-6 py-4"><p class="font-semibold text-slate-900">{{ $student->user->name ?? '-' }}</p><p class="text-xs text-slate-400">NIS: {{ $student->nis ?? '-' }}</p></td>
                                            <td class="px-6 py-4">
                                                @if ($submission)
                                                    @php($isLate = $submission->submitted_at && $submission->submitted_at->isAfter($assignment->deadline))
                                                    <x-badge variant="{{ $isLate ? 'danger' : 'success' }}">{{ $isLate ? 'Terlambat' : 'Tepat Waktu' }}</x-badge>
                                                @elseif (now()->isAfter($assignment->deadline))
                                                    <x-badge variant="danger">Belum mengumpulkan</x-badge>
                                                @else
                                                    <x-badge variant="slate">Menunggu</x-badge>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-slate-600">{{ $submission ? $submission->submitted_at?->format('d M Y, H:i') : '-' }}</td>
                                            <td class="px-6 py-4">
                                                @if ($submission)
                                                    <div class="font-bold text-slate-900 mb-1">Nilai: {{ $submission->score ?? '-' }}</div>
                                                    @if ($submission->file_path)
                                                        <div class="flex items-center gap-1.5">
                                                            <a href="{{ route('waka.monitoring.assignments.submissions.preview', ['assignment' => $assignment, 'submission' => $submission]) }}"
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                                class="text-xs font-semibold text-primary hover:text-blue-900 min-h-[36px] inline-flex items-center gap-1">
                                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                </svg>
                                                                Lihat
                                                            </a>
                                                            <span class="text-slate-300">|</span>
                                                            <a href="{{ route('waka.monitoring.assignments.submissions.download', ['assignment' => $assignment, 'submission' => $submission]) }}"
                                                                class="text-xs font-semibold text-slate-600 hover:text-slate-900 min-h-[36px] inline-flex items-center">
                                                                Unduh
                                                            </a>
                                                        </div>
                                                    @elseif ($submission->content)
                                                        <span class="text-xs text-slate-600 truncate block max-w-xs">{{ Str::limit($submission->content, 30) }}</span>
                                                    @else
                                                        <span class="text-xs text-slate-400">-</span>
                                                    @endif
                                                @else
                                                    <span class="text-xs text-slate-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500">Tidak ada siswa pada kelas aktif. Pastikan tahun ajaran aktif telah ditetapkan.</td></tr>
                                endforelse
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View (lg:hidden) -->
                    <div class="lg:hidden p-4 space-y-3 divide-y divide-slate-100">
                        @forelse ($enrolledStudents as $student)
                            @php($submission = $submissionByStudent->get($student->id))
                            <div class="pt-3 first:pt-0 space-y-2 min-w-0">
                                <div class="flex items-center justify-between gap-2 min-w-0">
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-bold text-slate-900 text-sm truncate">{{ $student->user->name ?? '-' }}</h4>
                                        <p class="text-xs text-slate-400">NIS: {{ $student->nis ?? '-' }}</p>
                                    </div>
                                    <div class="shrink-0">
                                        @if ($submission)
                                            @php($isLate = $submission->submitted_at && $submission->submitted_at->isAfter($assignment->deadline))
                                            <x-badge variant="{{ $isLate ? 'danger' : 'success' }}">{{ $isLate ? 'Terlambat' : 'Tepat Waktu' }}</x-badge>
                                        @elseif (now()->isAfter($assignment->deadline))
                                            <x-badge variant="danger">Belum mengumpulkan</x-badge>
                                        @else
                                            <x-badge variant="slate">Menunggu</x-badge>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center justify-between pt-1 text-xs text-slate-600">
                                    <span>Waktu: {{ $submission ? $submission->submitted_at?->format('d M Y, H:i') : '-' }}</span>
                                    @if ($submission)
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-900">Nilai: {{ $submission->score ?? '-' }}</span>
                                            @if ($submission->file_path)
                                                <a href="{{ route('waka.monitoring.assignments.submissions.preview', ['assignment' => $assignment, 'submission' => $submission]) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="font-bold text-primary hover:text-blue-900 min-h-[36px] flex items-center px-1">
                                                    Lihat
                                                </a>
                                                <span class="text-slate-300">|</span>
                                                <a href="{{ route('waka.monitoring.assignments.submissions.download', ['assignment' => $assignment, 'submission' => $submission]) }}"
                                                    class="font-bold text-slate-600 hover:text-slate-900 min-h-[36px] flex items-center px-1">
                                                    Unduh
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center text-slate-500 text-sm">Tidak ada siswa pada kelas aktif.</div>
                        @endforelse
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <x-file-preview-modal />
</x-layouts.app>
