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
                        <a href="{{ asset('storage/'.$assignment->attachment_path) }}" target="_blank" class="mt-5 inline-flex rounded-lg-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100">Unduh lampiran tugas</a>
                    @endif
                </x-card>

                <x-card padding="lg" class="bg-blue-700 text-white">
                    <p class="text-xs font-semibold uppercase tracking-wide text-blue-100">Partisipasi Pengumpulan</p>
                    <p class="mt-2 text-2xl font-bold">{{ $assignment->submissions->count() }} / {{ $enrolledStudents->count() }}</p>
                    <p class="mt-1 text-sm text-blue-100">siswa sudah mengumpulkan.</p>
                    @if ($enrolledStudents->isNotEmpty())
                        <div class="mt-4 h-2 overflow-hidden rounded-full bg-blue-950/30"><div class="h-full rounded-full bg-white" style="width: {{ min(100, round(($assignment->submissions->count() / $enrolledStudents->count()) * 100)) }}%"></div></div>
                    @endif
                </x-card>
            </div>

            <div class="xl:col-span-2">
                <x-card padding="none">
                    <div class="border-b border-slate-100 p-6">
                        <h2 class="text-lg font-bold text-slate-900">Status dan Jawaban Siswa</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[700px] text-left text-sm">
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
                                                    <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank" class="text-xs font-semibold text-blue-700 hover:text-blue-900">Buka jawaban</a>
                                                @else
                                                    <span class="text-xs text-slate-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500">Tidak ada siswa pada kelas aktif. Pastikan tahun ajaran aktif telah ditetapkan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.app>
