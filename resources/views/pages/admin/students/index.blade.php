<x-layouts.app>
    <x-slot:title>Manajemen Siswa</x-slot:title>

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between border-b border-slate-200 pb-5 gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Daftar Siswa</h1>
                <p class="mt-2 text-sm text-slate-500 max-w-2xl">
                    Kelola data murid, penempatan kelas aktif, serta relasi dengan orang tua murid.
                    @if($totalStudents > 0)
                        <span class="inline-flex items-center ml-2 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold border border-slate-200">
                            {{ $totalStudents }} siswa terdaftar
                        </span>
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto shrink-0">
                <x-button variant="primary" href="{{ route('admin.students.create') }}">
                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Siswa
                </x-button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm text-emerald-800 flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Toolbar (Search & Filter) -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-col items-center justify-between gap-4">
            <form action="{{ route('admin.students.index') }}" method="GET" class="w-full flex flex-col sm:flex-row gap-4">
                <!-- Search -->
                <div class="relative w-full sm:max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa atau NIS..." 
                        class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent text-sm transition-shadow">
                </div>
                
                <!-- Filter Kelas -->
                <div class="relative w-full sm:w-56">
                    <select name="class_id" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2.5 text-sm border border-slate-300 rounded-xl bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent appearance-none cursor-pointer">
                        <option value="">Semua Kelas</option>
                        @forelse($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @empty
                            <option value="" disabled>Belum ada kelas tersedia.</option>
                        @endforelse
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Container -->
        <x-card padding="none" class="overflow-visible">
            <div class="overflow-x-auto lg:overflow-visible">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama / NIS</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas Aktif</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <span class="hidden md:inline">Jenis Kelamin</span>
                                <span class="md:hidden">JK</span>
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Lahir</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Orang Tua / Wali</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($students as $student)
                            @php
                                $activeClass = $student->activeClassroom();
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 text-sm">{{ $student->user->name ?? '-' }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">NIS: {{ $student->nis ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($activeClass)
                                        <span class="text-sm font-semibold text-slate-700">{{ $activeClass->name }}</span>
                                    @else
                                        <span class="text-sm text-slate-400 italic">Belum ditempatkan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    <span class="hidden md:inline">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                    <span class="md:hidden font-medium">{{ $student->gender }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    {{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->locale('id')->translatedFormat('d F Y') : 'Belum diisi' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($student->parent)
                                        <div class="font-medium text-slate-800 text-sm">{{ $student->parent->user->name ?? '-' }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5">HP: {{ $student->parent->phone ?? '-' }}</div>
                                    @else
                                        <span class="text-sm text-slate-400 italic">Belum terhubung</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div x-data="{ menuOpen: false }" class="relative inline-block text-left">
                                        <button @click="menuOpen = !menuOpen" @click.away="menuOpen = false" type="button" class="p-2 text-slate-400 hover:text-primary hover:bg-slate-50 rounded-lg transition-colors">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                            </svg>
                                        </button>
                                        <div x-show="menuOpen" x-transition class="absolute right-0 z-10 mt-1 w-48 origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-slate-900/5 focus:outline-none py-1" style="display: none;">
                                            <a href="{{ route('admin.students.show', $student) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors">Lihat Detail</a>
                                            <a href="{{ route('admin.students.edit', $student) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-accent transition-colors">Edit Data</a>
                                            <div class="my-1 border-t border-slate-100"></div>
                                            <button type="button" x-on:click="$dispatch('open-modal', 'delete-student-{{ $student->id }}'); menuOpen = false" class="block w-full text-left px-4 py-2 text-sm text-danger hover:bg-red-50 transition-colors">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Delete Modal -->
                                    <x-modal name="delete-student-{{ $student->id }}" maxWidth="sm">
                                        <div class="p-6 text-left whitespace-normal">
                                            <div class="w-12 h-12 rounded-full bg-red-100 text-danger flex items-center justify-center mb-4">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                            </div>
                                            <h2 class="text-lg font-bold text-slate-900">Konfirmasi Penghapusan</h2>
                                            <p class="mt-2 text-sm text-slate-600">Apakah Anda yakin ingin menghapus data siswa <strong>{{ $student->user->name ?? '' }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
                                            <div class="mt-6 flex justify-end gap-3">
                                                <x-button variant="secondary" x-on:click="$dispatch('close-modal', 'delete-student-{{ $student->id }}')">Batal</x-button>
                                                <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-button variant="danger" type="submit">Hapus Data</x-button>
                                                </form>
                                            </div>
                                        </div>
                                    </x-modal>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-4">
                                            @if(request('search') || request('class_id'))
                                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                                            @else
                                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                                            @endif
                                        </div>
                                        
                                        @if(request('search') || request('class_id'))
                                            <h3 class="text-sm font-bold text-slate-900 mb-1">Tidak ada siswa yang ditemukan.</h3>
                                            <p class="text-xs text-slate-500">Coba ubah kata kunci pencarian atau filter kelas Anda.</p>
                                        @else
                                            <h3 class="text-sm font-bold text-slate-900 mb-1">Belum ada siswa</h3>
                                            <p class="text-xs text-slate-500 mb-4">Belum terdapat data siswa yang terdaftar di sistem.</p>
                                            <x-button variant="primary" href="{{ route('admin.students.create') }}" class="!py-2 !text-xs">
                                                + Tambah Siswa
                                            </x-button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->hasPages())
                <div class="p-5 border-t border-slate-100 bg-slate-50/50">
                    {{ $students->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
