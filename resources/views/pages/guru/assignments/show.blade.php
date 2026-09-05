<x-layouts.app>
    <x-slot:title>Detail Tugas: {{ $assignment->title }}</x-slot:title>

    <div class="space-y-6">
        <div class="mb-4">
            <a href="{{ route('guru.assignments.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition mb-3">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-950">{{ $assignment->title }}</h1>
                    <div class="flex items-center gap-3 mt-2 text-sm flex-wrap">
                        <x-badge variant="primary">{{ $assignment->classroom->name ?? '-' }}</x-badge>
                        @if($assignment->learningMeeting)
                            <x-badge variant="info">Pertemuan {{ $assignment->learningMeeting->meeting_number }}</x-badge>
                        @endif
                        @if($assignment->material)
                            <x-badge variant="neutral">Materi: {{ $assignment->material->title }}</x-badge>
                        @endif
                        <span class="text-slate-500">Tenggat Waktu: <strong class="{{ now()->isAfter($assignment->deadline) ? 'text-red-600' : 'text-slate-800' }}">{{ $assignment->deadline->format('d M Y, H:i') }}</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Informasi Tugas -->
            <div class="xl:col-span-1 space-y-6">
                <x-card padding="lg" class="h-auto">
                    <h3 class="text-sm font-bold text-slate-900 mb-3 uppercase tracking-wider">Instruksi Tugas</h3>
                    <div class="text-sm text-slate-700 whitespace-pre-wrap">{{ $assignment->description }}</div>
                    
                    @if($assignment->attachment_path)
                        <div class="mt-5 pt-5 border-t border-slate-100">
                            <h4 class="text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wide">Lampiran Pendukung</h4>
                            <a href="{{ route('guru.assignments.download', $assignment) }}" target="_blank" class="inline-flex items-center gap-2 p-3 w-full bg-blue-50/50 border border-blue-100 rounded-lg text-sm font-semibold text-blue-700 hover:bg-blue-100 transition">
                                <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                Unduh Lampiran Soal
                            </a>
                        </div>
                    @endif
                </x-card>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-blue-600 rounded-3xl p-5 sm:p-6 text-white shadow-sm bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-blend-overlay">
                        <h3 class="text-xs sm:text-sm font-bold text-blue-100 mb-1">Status Pengumpulan</h3>
                        <div class="text-3xl sm:text-4xl font-bold mt-1">
                            {{ $submittedCount }} <span class="text-xl sm:text-2xl font-medium text-blue-200">/ {{ $totalClassStudents }}</span> <span class="text-sm sm:text-lg font-medium text-blue-200">Siswa</span>
                        </div>
                        <p class="text-xs sm:text-sm text-blue-100 mt-2">telah mengumpulkan tugas.</p>
                    </div>
                    <div class="bg-emerald-600 rounded-3xl p-5 sm:p-6 text-white shadow-sm bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-blend-overlay">
                        <h3 class="text-xs sm:text-sm font-bold text-emerald-100 mb-1">Status Penilaian</h3>
                        <div class="text-3xl sm:text-4xl font-bold mt-1">
                            {{ $gradedCount ?? 0 }} <span class="text-xl sm:text-2xl font-medium text-emerald-200">/ {{ $submittedCount }}</span> <span class="text-sm sm:text-lg font-medium text-emerald-200">Siswa</span>
                        </div>
                        <p class="text-xs sm:text-sm text-emerald-100 mt-2">sudah dinilai.</p>
                    </div>
                </div>
            </div>

            <!-- Daftar Pengumpul -->
            <div class="xl:col-span-2">
                <x-card padding="none" class="h-full flex flex-col">
                    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h3 class="text-lg font-bold text-slate-900">Daftar Pengumpulan Siswa</h3>
                        
                        <!-- Search & Filter -->
                        <form action="{{ route('guru.assignments.show', $assignment) }}" method="GET" class="w-full sm:w-auto flex flex-col sm:flex-row gap-2.5 items-center">
                            <div class="relative w-full sm:w-48 md:w-56">
                                <div class="absolute inset-y-0 left-0 pl-4.5 flex items-center pointer-events-none z-10">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/NIS..." class="w-full bg-white border border-slate-200 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 rounded-xl pl-9 pr-4 py-2.5 text-[14px] font-medium text-slate-800 shadow-2xs transition-all placeholder:text-slate-400">
                            </div>
                            <div class="w-full sm:w-48 md:w-56">
                                <x-select name="status" 
                                          onchange="this.form.submit()" 
                                          placeholder="Semua Status" 
                                          :selected="request('status')" 
                                          :options="[
                                              ['value' => 'submitted', 'label' => 'Sudah Mengumpulkan'],
                                              ['value' => 'not_submitted', 'label' => 'Belum Mengumpulkan'],
                                              ['value' => 'late', 'label' => 'Terlambat'],
                                              ['value' => 'graded', 'label' => 'Sudah Dinilai'],
                                              ['value' => 'not_graded', 'label' => 'Belum Dinilai']
                                          ]" />
                            </div>
                            @if(request('search') || request('status'))
                                <a href="{{ route('guru.assignments.show', $assignment) }}" class="inline-flex items-center justify-center p-1.5 text-slate-400 hover:text-slate-600 border border-transparent rounded-lg-lg" title="Clear Filters">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </form>
                    </div>

                    <x-table :headers="['No', 'Nama Siswa', 'Status', 'Waktu Kumpul', 'Nilai', 'Aksi']">
                        @forelse($students as $index => $student)
                            @php
                                $submission = $student->submissions->first();
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $students->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-950">{{ $student->user->name ?? '-' }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">NIS: {{ $student->nis ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($submission)
                                        <x-badge variant="success" class="whitespace-nowrap">Sudah Mengumpulkan</x-badge>
                                        @if($submission->created_at->isAfter($assignment->deadline))
                                            <span class="inline-block mt-1 text-[10px] uppercase font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-full border border-red-200">Terlambat</span>
                                        @endif
                                    @else
                                        <x-badge variant="slate" class="whitespace-nowrap">Belum Mengumpulkan</x-badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($submission)
                                        <div class="flex flex-col">
                                            <span class="font-medium {{ $submission->created_at->isAfter($assignment->deadline) ? 'text-red-600' : 'text-slate-700' }}">
                                                {{ $submission->created_at->format('d M Y, H:i') }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($submission && $submission->score !== null)
                                        <span class="inline-flex items-center justify-center font-bold text-lg {{ $submission->score >= 75 ? 'text-emerald-600' : 'text-amber-600' }}">
                                            {{ $submission->score }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2" x-data="{ openModal: false, openFeedbackModal: false }">
                                        @if($submission)
                                            @if($submission->file_path)
                                                <a href="{{ route('guru.assignments.submissions.download', [$assignment, $submission]) }}" title="Unduh File" class="inline-flex items-center justify-center p-2 rounded-lg-lg text-blue-600 hover:bg-blue-50 transition">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                    </svg>
                                                </a>
                                            @elseif($submission->notes)
                                                <span class="inline-flex items-center justify-center p-2 text-slate-400" title="{{ $submission->notes }}">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                    </svg>
                                                </span>
                                            @endif
                                            
                                            <button @click="openModal = true" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg-lg transition {{ $submission->score !== null ? 'text-slate-700 bg-slate-100 hover:bg-slate-200' : 'text-blue-700 bg-blue-50 hover:bg-blue-100' }}">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                                </svg>
                                                {{ $submission->score !== null ? 'Edit Nilai' : 'Beri Nilai' }}
                                            </button>
                                            
                                            <!-- Feedback Button -->
                                            <button @click="openFeedbackModal = true" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg-lg transition {{ $submission->feedback !== null ? 'text-slate-700 bg-slate-100 hover:bg-slate-200' : 'text-purple-700 bg-purple-50 hover:bg-purple-100' }}">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                                </svg>
                                                {{ $submission->feedback !== null ? 'Edit Feedback' : 'Beri Feedback' }}
                                            </button>
                                            
                                            <!-- Modal Grading -->
                                            <div x-show="openModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
                                                <div x-show="openModal" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
                                                <div class="fixed inset-0 z-10 overflow-y-auto">
                                                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                                        <div x-show="openModal" x-transition.scale.origin.bottom class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                                            <form action="{{ route('guru.assignments.submissions.grade', [$assignment->id, $submission->id]) }}" method="POST">
                                                                @csrf
                                                                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                                                    <div class="sm:flex sm:items-start">
                                                                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                                                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                                                            </svg>
                                                                        </div>
                                                                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                                                            <h3 class="text-base font-semibold leading-6 text-slate-900" id="modal-title">Penilaian: {{ $student->user->name }}</h3>
                                                                            <div class="mt-2">
                                                                                <p class="text-sm text-slate-500 mb-4">Masukkan nilai (0-100) untuk pengumpulan tugas ini.</p>
                                                                                <div>
                                                                                    <label for="score-{{ $submission->id }}" class="block text-sm font-medium leading-6 text-slate-900">Nilai</label>
                                                                                    <div class="mt-1 relative rounded-md shadow-sm">
                                                                                        <input type="number" name="score" id="score-{{ $submission->id }}" class="block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6" placeholder="0-100" min="0" max="100" value="{{ $submission->score }}" required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                                                    <button type="submit" class="inline-flex w-full justify-center rounded-lg-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Simpan Penilaian</button>
                                                                    <button type="button" @click="openModal = false" class="mt-3 inline-flex w-full justify-center rounded-lg-lg bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto">Batal</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Feedback -->
                                            <div x-show="openFeedbackModal" class="relative z-50" aria-labelledby="modal-feedback-title" role="dialog" aria-modal="true" style="display: none;">
                                                <div x-show="openFeedbackModal" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
                                                <div class="fixed inset-0 z-10 overflow-y-auto">
                                                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                                        <div x-show="openFeedbackModal" x-transition.scale.origin.bottom class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                                            <form action="{{ route('guru.assignments.submissions.feedback', [$assignment->id, $submission->id]) }}" method="POST">
                                                                @csrf
                                                                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                                                    <div class="sm:flex sm:items-start">
                                                                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                                                                            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                                                            </svg>
                                                                        </div>
                                                                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                                                            <h3 class="text-base font-semibold leading-6 text-slate-900" id="modal-feedback-title">Feedback untuk: {{ $student->user->name }}</h3>
                                                                            <div class="mt-2 text-sm text-slate-500 mb-3 flex items-center justify-between">
                                                                                <div>Nilai: <span class="font-bold text-slate-900">{{ $submission->score !== null ? $submission->score : 'Belum Dinilai' }}</span></div>
                                                                            </div>
                                                                            <div class="mt-2">
                                                                                <label for="feedback-{{ $submission->id }}" class="block text-sm font-medium leading-6 text-slate-900">Tulis feedback untuk siswa...</label>
                                                                                <div class="mt-1 relative rounded-md shadow-sm">
                                                                                    <textarea name="feedback" id="feedback-{{ $submission->id }}" rows="4" class="block w-full rounded-md border-0 py-1.5 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-purple-600 sm:text-sm sm:leading-6">{{ $submission->feedback }}</textarea>
                                                                                </div>
                                                                                <p class="mt-1 text-xs text-slate-500">Kosongkan isian ini lalu simpan jika ingin menghapus feedback.</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                                                    <button type="submit" class="inline-flex w-full justify-center rounded-lg-lg bg-purple-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-purple-500 sm:ml-3 sm:w-auto">Simpan Feedback</button>
                                                                    <button type="button" @click="openFeedbackModal = false" class="mt-3 inline-flex w-full justify-center rounded-lg-lg bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto">Batal</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-slate-400 italic text-xs">Belum Mengumpulkan</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-10 w-10 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                        </svg>
                                        <p>
                                            @if(request('search') || request('status'))
                                                Tidak ada hasil ditemukan.
                                            @else
                                                Belum ada siswa pada kelas ini.
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </x-table>
                    
                    @if($students->hasPages())
                        <div class="p-4 border-t border-slate-100">
                            {{ $students->links() }}
                        </div>
                    @endif
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.app>
