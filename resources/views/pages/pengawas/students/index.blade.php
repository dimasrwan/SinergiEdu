<x-layouts.app>
    <x-slot:title>Monitoring Siswa</x-slot:title>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <x-page-header title="Monitoring Siswa" description="Pantau data dan perkembangan belajar seluruh siswa di sekolah." />
        </div>

        {{-- Filter --}}
        <form method="GET" action="{{ route('pengawas.students.index') }}" class="bg-white border border-slate-200/60 rounded-2xl p-4 shadow-sm">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama siswa, NIS, atau NISN..."
                        class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                </div>
                <div class="sm:w-56">
                    <select name="class_id" class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-cyan-500 focus:bg-white transition">
                        <option value="">Semua Kelas</option>
                        @foreach($classrooms as $cls)
                            <option value="{{ $cls->id }}" @selected(request('class_id') == $cls->id)>{{ $cls->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-cyan-600 hover:bg-cyan-700 rounded-xl transition shadow-sm">
                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 15.803a7.5 7.5 0 0 0 10.607 0Z"/></svg>
                    Cari
                </button>
                @if(request('search') || request('class_id'))
                    <a href="{{ route('pengawas.students.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Reset</a>
                @endif
            </div>
        </form>

        {{-- Tabel Siswa --}}
        <x-card padding="none">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/50">
                            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Siswa</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">NIS / NISN</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500 hidden md:table-cell">Orang Tua</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500 hidden lg:table-cell">Kelas Aktif</th>
                            <th class="px-5 py-3.5 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($students as $student)
                            @php $activeClass = $student->activeClassroom(); @endphp
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($student->user?->name ?? '?', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900">{{ $student->user?->name ?? '-' }}</div>
                                            <div class="text-xs text-slate-400">{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-mono text-xs text-slate-700">NIS: {{ $student->nis ?? '-' }}</div>
                                    <div class="font-mono text-xs text-slate-500">NISN: {{ $student->nisn ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-4 hidden md:table-cell">
                                    <span class="text-slate-700">{{ $student->parent?->user?->name ?? '-' }}</span>
                                </td>
                                <td class="px-5 py-4 hidden lg:table-cell">
                                    @if($activeClass)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">{{ $activeClass->name }}</span>
                                    @else
                                        <span class="text-slate-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('pengawas.students.show', $student) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-white bg-cyan-600 hover:bg-cyan-700 rounded-lg transition">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-16 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 mb-4 ring-8 ring-slate-50/50">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-700">Tidak ada siswa ditemukan</h3>
                                    <p class="text-sm text-slate-400 mt-1">Coba ubah filter atau kata kunci pencarian.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($students->hasPages())
                <div class="p-5 border-t border-slate-100">
                    {{ $students->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
