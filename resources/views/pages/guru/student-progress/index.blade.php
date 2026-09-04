<x-layouts.app title="Perkembangan Siswa">

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Perkembangan Siswa</h1>
            <p class="mt-1 text-sm text-slate-500">Pantau progres dan nilai siswa dalam kelas Anda.</p>
        </div>
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
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <dt class="text-sm font-medium text-slate-500 truncate">Total Siswa</dt>
                <dd class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">{{ $overview['total_siswa'] }}</dd>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <dt class="text-sm font-medium text-slate-500 truncate">Rata-Rata Nilai</dt>
                <dd class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">{{ $overview['rata_rata'] }}</dd>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <dt class="text-sm font-medium text-slate-500 truncate">Tugas Selesai</dt>
                <dd class="mt-2 text-3xl font-semibold tracking-tight text-green-600">{{ $overview['tugas_selesai'] }}</dd>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <dt class="text-sm font-medium text-slate-500 truncate">Menunggu Penilaian</dt>
                <dd class="mt-2 text-3xl font-semibold tracking-tight text-orange-600">{{ $overview['belum_dinilai'] }}</dd>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 p-4 sm:p-6">
                <form action="{{ route('guru.student-progress.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <label for="search" class="sr-only">Cari Siswa</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
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
                    
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-700">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'class_id', 'subject_id']))
                        <a href="{{ route('guru.student-progress.index') }}" class="inline-flex items-center justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-semibold text-slate-900 sm:pl-6">Siswa</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900">Kelas & Mapel</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900">Tugas</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900">Rata-Rata Nilai</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900">Status</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">Aksi</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($students as $item)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 sm:pl-6">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">
                                        {{ strtoupper(substr($item->student->user->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-slate-900">{{ $item->student->user->name }}</div>
                                        <div class="text-sm text-slate-500">NIS: {{ $item->student->nis }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                <div>{{ $item->classroom->name }}</div>
                                <div class="text-xs">{{ $item->subject->name }}</div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                <div class="font-medium text-slate-900">{{ $item->completed_assignments }} / {{ $item->total_assignments }}</div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                @if($item->avg_score !== null)
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">{{ $item->avg_score }}</span>
                                @else
                                    <span class="text-slate-400 italic">Belum Ada</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                @if($item->status == 'Lengkap')
                                    <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Lengkap</span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">Ada Tunggakan Tugas</span>
                                @endif
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="{{ route('guru.student-progress.show', ['student' => $item->id, 'class_id' => $item->classroom->id, 'subject_id' => $item->subject->id]) }}" class="text-blue-600 hover:text-blue-900">Detail<span class="sr-only">, {{ $item->student->user->name }}</span></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                                @if(request()->hasAny(['search', 'class_id', 'subject_id']))
                                    Siswa tidak ditemukan untuk filter ini.
                                @else
                                    Belum ada siswa yang terdaftar pada kelas Anda.
                                @endif
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
        </div>
    @endif
</div>
</x-layouts.app>
