<x-layouts.app title="Perkembangan Siswa">

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <x-page-header title="Perkembangan Siswa" description="Pantau progres dan nilai siswa dalam kelas Anda." />
    </div>

    @if(!$hasActiveContext)
        <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-4">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-yellow-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Periode Akademik Aktif Belum Tersedia</h3>
                    <p class="mt-1 text-sm text-yellow-700">Hubungi admin sekolah untuk mengatur Tahun Ajaran dan Semester aktif.</p>
                </div>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-card padding="sm">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Siswa</span>
                        <h3 class="text-2xl font-bold tracking-tight text-slate-900 mt-1">{{ $overview['total_siswa'] }}</h3>
                    </div>
                    <div class="text-blue-600 bg-blue-50/50 p-3 rounded-xl border border-blue-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
                    </div>
                </div>
            </x-card>

            <x-card padding="sm">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Rata-Rata Nilai</span>
                        <h3 class="text-2xl font-bold tracking-tight text-slate-900 mt-1">{{ $overview['rata_rata'] }}</h3>
                    </div>
                    <div class="text-blue-600 bg-blue-50/50 p-3 rounded-xl border border-blue-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                    </div>
                </div>
            </x-card>

            <x-card padding="sm">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Tugas Selesai</span>
                        <h3 class="text-2xl font-bold tracking-tight text-slate-900 mt-1">{{ $overview['tugas_selesai'] }}</h3>
                    </div>
                    <div class="text-emerald-600 bg-emerald-50/50 p-3 rounded-xl border border-emerald-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>
                    </div>
                </div>
            </x-card>

            <x-card padding="sm">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Menunggu Penilaian</span>
                        <h3 class="text-2xl font-bold tracking-tight text-slate-900 mt-1">{{ $overview['belum_dinilai'] }}</h3>
                    </div>
                    <div class="text-amber-600 bg-amber-50/50 p-3 rounded-xl border border-amber-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16.5 12"/></svg>
                    </div>
                </div>
            </x-card>
        </div>

        <x-card padding="none">
            <div class="border-b border-slate-200 p-4 sm:p-6">
                <form action="{{ route('guru.student-progress.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <label for="search" class="sr-only">Cari Siswa</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full rounded-lg border-0 py-2 pl-10 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6" placeholder="Cari nama atau NIS...">
                        </div>
                    </div>
                    
                    <div class="sm:w-48">
                        <label for="class_id" class="sr-only">Kelas</label>
                        <select id="class_id" name="class_id" class="block w-full rounded-lg border-0 py-2 pl-3 pr-10 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="sm:w-48">
                        <label for="subject_id" class="sr-only">Mata Pelajaran</label>
                        <select id="subject_id" name="subject_id" class="block w-full rounded-lg border-0 py-2 pl-3 pr-10 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6">
                            <option value="">Semua Mapel</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'class_id', 'subject_id']))
                        <a href="{{ route('guru.student-progress.index') }}" class="inline-flex items-center justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
            
            <div class="overflow-x-auto w-full">
                <table class="w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th scope="col" class="px-4 py-4 text-left uppercase tracking-wider text-xs font-semibold text-slate-500">Siswa</th>
                            <th scope="col" class="px-4 py-4 text-left uppercase tracking-wider text-xs font-semibold text-slate-500">Kelas & Mapel</th>
                            <th scope="col" class="px-4 py-4 text-left uppercase tracking-wider text-xs font-semibold text-slate-500">Tugas</th>
                            <th scope="col" class="px-4 py-4 text-left uppercase tracking-wider text-xs font-semibold text-slate-500">Rata-Rata Nilai</th>
                            <th scope="col" class="px-4 py-4 text-left uppercase tracking-wider text-xs font-semibold text-slate-500">Status</th>
                            <th scope="col" class="px-4 py-4 relative">
                                <span class="sr-only">Aksi</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($students as $item)
                        <tr>
                            <td class="whitespace-nowrap px-4 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-lg bg-blue-100 flex items-center justify-center text-blue-700 font-bold">
                                        {{ strtoupper(substr($item->student->user->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-slate-900">{{ $item->student->user->name }}</div>
                                        <div class="text-sm text-slate-500">NIS: {{ $item->student->nis }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-500">
                                <div>{{ $item->classroom->name }}</div>
                                <div class="text-xs">{{ $item->subject->name }}</div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-500">
                                <div class="font-medium text-slate-900">{{ $item->completed_assignments }} / {{ $item->total_assignments }}</div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-500">
                                @if($item->avg_score !== null)
                                    <span class="bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded-full text-xs font-medium">{{ $item->avg_score }}</span>
                                @else
                                    <span class="text-slate-400 italic">Belum Ada</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-500">
                                @if($item->status == 'Lengkap')
                                    <span class="bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full text-xs font-medium">Lengkap</span>
                                @else
                                    <span class="bg-amber-100 text-amber-800 px-2.5 py-0.5 rounded-full text-xs font-medium">Ada Tunggakan Tugas</span>
                                @endif
                            </td>
                            <td class="relative whitespace-nowrap px-4 py-4 text-right text-sm font-medium">
                                <a href="{{ route('guru.student-progress.show', ['student' => $item->id, 'class_id' => $item->classroom->id, 'subject_id' => $item->subject->id]) }}" class="inline-flex items-center justify-center rounded-lg bg-white px-3 py-1.5 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">Detail<span class="sr-only">, {{ $item->student->user->name }}</span></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center w-full">
                                <div class="flex flex-col items-center justify-center space-y-3 mx-auto max-w-lg">
                                    <svg class="h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                    </svg>
                                    <div>
                                        <h3 class="text-base font-semibold text-slate-900">Belum ada siswa</h3>
                                        <p class="mt-1 text-sm text-slate-500">
                                            @if(request()->hasAny(['search', 'class_id', 'subject_id']))
                                                Siswa tidak ditemukan untuk filter ini.
                                            @else
                                                Belum ada siswa yang terdaftar pada kelas Anda.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($students->hasPages())
            <div class="border-t border-slate-200 p-4 sm:p-6">
                {{ $students->links() }}
            </div>
            @endif
        </x-card>
    @endif
</div>
</x-layouts.app>
