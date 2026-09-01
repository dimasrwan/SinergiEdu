<x-layouts.app>
    <x-slot:title>Monitoring Siswa - Pengawas</x-slot:title>

    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Monitoring Siswa</h1>
                <p class="text-sm text-slate-500 mt-1">Pantau perkembangan hasil belajar siswa secara realtime</p>
            </div>
        </div>

        {{-- Filter & Search --}}
        <x-card padding="md">
            <form method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <select name="class_id" onchange="this.form.submit()" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Pilih Kelas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <a href="{{ route('pengawas.students.downloadReport', ['class_id' => $selectedClassId]) }}" 
                   class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition inline-flex items-center gap-2 justify-center">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download Excel
                </a>
            </form>
        </x-card>

        {{-- Daftar Siswa --}}
        <x-card padding="none">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-3 text-left font-semibold text-slate-700">Nama Siswa</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-700">NIS/NISN</th>
                            <th class="px-6 py-3 text-center font-semibold text-slate-700">Rata-rata</th>
                            <th class="px-6 py-3 text-center font-semibold text-slate-700">Tes Akhir</th>
                            <th class="px-6 py-3 text-center font-semibold text-slate-700">Karakter</th>
                            <th class="px-6 py-3 text-center font-semibold text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($students as $student)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-3">
                                    <div class="font-medium text-slate-900">{{ $student->user?->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $student->user?->email }}</div>
                                </td>
                                <td class="px-6 py-3">
                                    <div class="text-sm text-slate-600">{{ $student->nis }}</div>
                                    <div class="text-xs text-slate-400">{{ $student->nisn }}</div>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-sm font-semibold
                                        {{ $student->studentGrades->avg('average_score') >= 80 ? 'bg-emerald-100 text-emerald-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ number_format($student->studentGrades->avg('average_score') ?? 0, 1) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-center text-slate-600">
                                    {{ number_format($student->studentGrades->avg('post_test_score') ?? 0, 1) }}
                                </td>
                                <td class="px-6 py-3 text-center text-slate-600">
                                    {{ number_format($student->studentGrades->avg('character_score') ?? 0, 1) }}
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('pengawas.students.show', $student->id) }}"
                                           class="px-3 py-1.5 text-sm bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
                                            Lihat
                                        </a>
                                        <a href="{{ route('pengawas.feedback.create', ['student_id' => $student->id]) }}"
                                           class="px-3 py-1.5 text-sm bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200 transition">
                                            Feedback
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <p class="text-sm">Tidak ada siswa ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->hasPages())
                <div class="p-6 border-t border-slate-200">
                    {{ $students->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
