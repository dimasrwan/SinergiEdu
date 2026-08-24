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
                    <div class="flex items-center gap-3 mt-2 text-sm">
                        <x-badge variant="primary">{{ $assignment->classroom->name ?? '-' }}</x-badge>
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
                            <a href="{{ route('guru.assignments.download', $assignment) }}" target="_blank" class="inline-flex items-center gap-2 p-3 w-full bg-blue-50/50 border border-blue-100 rounded-xl text-sm font-semibold text-blue-700 hover:bg-blue-100 transition">
                                <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                Unduh Lampiran Soal
                            </a>
                        </div>
                    @endif
                </x-card>

                <div class="bg-blue-600 rounded-3xl p-6 text-white shadow-sm bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-blend-overlay">
                    <h3 class="text-sm font-bold text-blue-100 mb-1">Status Pengumpulan</h3>
                    <div class="text-4xl font-bold mt-1">
                        {{ $assignment->submissions->count() }} <span class="text-lg font-medium text-blue-200">Siswa</span>
                    </div>
                    <p class="text-sm text-blue-100 mt-2">telah mengumpulkan jawaban untuk tugas ini.</p>
                </div>
            </div>

            <!-- Daftar Pengumpul -->
            <div class="xl:col-span-2">
                <x-card padding="none" class="h-full">
                    <div class="p-6 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-900">Daftar Jawaban Siswa</h3>
                    </div>

                    <x-table>
                        <x-slot:head>
                            <tr>
                                <th class="px-6 py-4">Nama Siswa</th>
                                <th class="px-6 py-4">Waktu Kumpul</th>
                                <th class="px-6 py-4">File Jawaban</th>
                                <th class="px-6 py-4">Catatan</th>
                            </tr>
                        </x-slot:head>
                        <x-slot:body>
                            @forelse($assignment->submissions as $submission)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-950">
                                        {{ $submission->student->user->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-medium {{ $submission->created_at->isAfter($assignment->deadline) ? 'text-red-600' : 'text-slate-700' }}">
                                                {{ $submission->created_at->format('d M Y, H:i') }}
                                            </span>
                                            @if($submission->created_at->isAfter($assignment->deadline))
                                                <span class="text-[10px] uppercase font-bold text-red-500 mt-0.5">Terlambat</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-700 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 hover:text-blue-800 transition">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                            Unduh
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500 italic max-w-xs truncate" title="{{ $submission->notes }}">
                                        {{ $submission->notes ?: 'Tidak ada catatan.' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="h-10 w-10 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                            </svg>
                                            <p>Belum ada siswa yang mengumpulkan jawaban.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </x-slot:body>
                    </x-table>
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.app>
