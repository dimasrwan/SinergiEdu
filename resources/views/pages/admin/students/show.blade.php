<x-layouts.app>
    <x-slot:title>Detail Siswa - {{ $student->user->name }}</x-slot:title>



    <div class="w-full" x-data="{}">
        <!-- Header & Breadcrumb -->
        <div class="mb-6 flex flex-col items-start gap-4">
            <a href="{{ route('admin.students.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <div class="w-full flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Detail Profil Siswa</h1>
                    <p class="mt-1 text-sm text-slate-500">Informasi lengkap data diri, wali murid, dan penempatan akademik siswa.</p>
                </div>
                <div class="flex gap-2 w-full sm:w-auto shrink-0">
                    <x-button variant="secondary" href="{{ route('admin.students.edit', $student) }}" class="flex-1 sm:flex-none justify-center">Edit Data Siswa</x-button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Kolom Kiri: Profil Utama -->
            <div class="lg:col-span-4 space-y-6">
                <x-card padding="none" class="overflow-hidden">
                    <!-- Blue Header/Cover -->
                    <div class="h-28 bg-gradient-to-br from-primary to-blue-800"></div>
                    
                    <div class="px-6 pb-6 relative">
                        <!-- Avatar -->
                        <div class="relative -mt-12 mb-4">
                            <div class="w-24 h-24 rounded-2xl bg-white p-1 shadow-sm border border-slate-100">
                                <div class="w-full h-full bg-slate-50 rounded-xl flex items-center justify-center text-slate-300">
                                    <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Basic Info -->
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">{{ $student->user->name ?? 'Tanpa Nama' }}</h2>
                            <p class="text-sm font-mono font-semibold text-slate-500 mt-1">NIS: {{ $student->nis ?? '-' }}</p>
                        </div>
                        
                        <div class="mt-6 border-t border-slate-100 pt-6 space-y-5">
                            <!-- Info Baris -->
                            <div>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Email Akses</p>
                                <p class="text-sm font-medium text-slate-900">{{ $student->user->email ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jenis Kelamin</p>
                                <p class="text-sm font-medium text-slate-900">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Lahir</p>
                                <p class="text-sm font-medium text-slate-900">
                                    {{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->locale('id')->translatedFormat('d F Y') : 'Belum diisi' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Kolom Kanan: Detail Lanjutan -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Penugasan Kelas -->
                <!-- Penempatan & Riwayat Kelas -->
                <x-card padding="none" class="overflow-hidden">
                    <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <svg class="h-5 w-5 text-accent" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" /></svg>
                            Penempatan & Riwayat Kelas
                        </h3>
                        <button type="button" x-on:click.prevent="$dispatch('open-modal', 'add-placement')" class="px-4 py-2 text-xs font-semibold text-white bg-accent hover:bg-blue-600 rounded-lg-lg transition duration-150 inline-flex items-center gap-1.5">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Tambah Penempatan
                        </button>
                    </div>
                    
                    <div class="p-6 md:p-8 space-y-6">
                        @if(session('success'))
                            <div class="p-4 bg-green-50 border border-green-100 rounded-xl flex items-start gap-3 shadow-sm mb-4">
                                <svg class="h-5 w-5 text-green-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <div>
                                    <h3 class="text-sm font-bold text-emerald-700">Berhasil</h3>
                                    <p class="text-sm text-green-700 mt-0.5">{{ session('success') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="p-4 bg-red-50 border border-red-100 rounded-xl flex items-start gap-3 shadow-sm mb-4">
                                <svg class="h-5 w-5 text-red-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <div>
                                    <h3 class="text-sm font-bold text-red-800">Gagal Menyimpan Data</h3>
                                    <ul class="list-disc pl-5 mt-1 text-sm text-red-700">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-3">
                            <h4 class="text-sm font-bold text-slate-800">Kelas Aktif Saat Ini</h4>
                            @if($activeClass)
                                <div class="flex items-center gap-4 bg-gradient-to-br from-blue-50 to-white p-5 rounded-lg border border-blue-100 shadow-sm">
                                    <div class="w-14 h-14 bg-white rounded-xl shadow-sm border border-blue-100 flex items-center justify-center text-primary shrink-0">
                                        <span class="text-lg font-bold">{{ $activeClass->formatted_grade_level }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-primary/80 uppercase tracking-wide">Tahun Ajaran Aktif</p>
                                        <p class="text-2xl font-bold text-slate-900 mt-0.5">{{ $activeClass->name }}</p>
                                        <p class="text-sm text-slate-500 mt-1">Siswa ini terdaftar di kelas ini.</p>
                                    </div>
                                </div>
                            @else
                                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-center">
                                    <p class="text-sm font-bold text-slate-900">Siswa Belum Ditempatkan di Tahun Ajaran Aktif</p>
                                    <p class="text-sm text-slate-500 mt-1">Siswa ini belum dialokasikan ke dalam kelas aktif pada tahun ajaran ini.</p>
                                </div>
                            @endif
                        </div>

                        <div class="pt-4 border-t border-slate-100 space-y-3">
                            <h4 class="text-sm font-bold text-slate-800">Semua Riwayat Penempatan</h4>
                            <div class="overflow-x-auto border border-slate-100 rounded-xl">
                                <table class="w-full text-left border-collapse min-w-max">
                                    <thead>
                                        <tr class="bg-slate-50 border-b border-slate-100">
                                            <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tahun Ajaran</th>
                                            <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas</th>
                                            <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @forelse($placements ?? [] as $placement)
                                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                                <td class="py-3 px-4">
                                                    <p class="text-sm font-bold text-slate-900">{{ $placement->academicYear->year }}</p>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span class="inline-flex items-center text-xs font-semibold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-lg border border-slate-200">
                                                        {{ $placement->classroom->name }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4 text-right">
                                                    <div class="flex items-center justify-end gap-2 ">
                                                        <button type="button" x-on:click.prevent="$dispatch('open-modal', 'edit-placement-{{ $placement->id }}')" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Penempatan">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                                        </button>
                                                        <button type="button" x-on:click.prevent="$dispatch('open-modal', 'delete-placement-{{ $placement->id }}')" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Penempatan">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                                        </button>

                                                        <!-- Edit Placement Modal -->
                                                        <x-modal name="edit-placement-{{ $placement->id }}" maxWidth="xl">
                                                            <form action="{{ route('admin.student-placements.update', $placement) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="redirect_to" value="student">
                                                                <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                                
                                                                <div class="p-6 text-left whitespace-normal">
                                                                    <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-5">Edit Penempatan Siswa</h2>
                                                                    
                                                                    <div class="space-y-5">
                                                                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 flex flex-col gap-1">
                                                                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Siswa</span>
                                                                            <div class="font-bold text-slate-900">{{ $student->user->name ?? '-' }}</div>
                                                                            <div class="text-xs text-slate-500 font-mono">NIS: {{ $student->nis ?? '-' }}</div>
                                                                        </div>
                                                                        
                                                                         <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                                                            <div>
                                                                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kelas <span class="text-danger">*</span></label>
                                                                                <x-select name="class_id" required placeholder="-- Pilih Kelas --" :selected="old('class_id', $placement->class_id)" :options="$classes->map(fn($cls) => ['value' => $cls->id, 'label' => $cls->name])->toArray()" />
                                                                            </div>
                                                                            <div>
                                                                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun Ajaran <span class="text-danger">*</span></label>
                                                                                <x-select name="academic_year_id" required placeholder="-- Pilih Tahun Ajaran --" :selected="old('academic_year_id', $placement->academic_year_id)" :options="$academicYears->map(fn($ay) => ['value' => $ay->id, 'label' => $ay->year])->toArray()" />
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="mt-8 flex justify-end gap-3 pt-5 border-t border-slate-100">
                                                                        <button type="button" x-on:click.prevent="$dispatch('close-modal', 'edit-placement-{{ $placement->id }}')" class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                                                                        <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-primary rounded-lg-lg hover:bg-blue-900 transition-colors">Simpan Penempatan</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </x-modal>

                                                        <!-- Delete Placement Modal -->
                                                        <x-modal name="delete-placement-{{ $placement->id }}" maxWidth="sm">
                                                            <div class="p-6 text-left whitespace-normal text-slate-700">
                                                                <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">Hapus Penempatan?</h2>
                                                                
                                                                <div class="mb-4 space-y-1">
                                                                    <p class="font-bold">{{ $student->user->name ?? '-' }}</p>
                                                                    <p class="text-sm">{{ $placement->classroom->name }}</p>
                                                                    <p class="text-sm">{{ $placement->academicYear?->year ?? '-' }}</p>
                                                                </div>
                                                                
                                                                <p class="text-sm mt-4 text-danger font-medium bg-red-50 p-3 rounded-lg border border-red-100">Menghapus penempatan ini tidak akan menghapus data akademik yang terlanjur terkait jika ada.</p>
                                                                
                                                                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-100">
                                                                    <x-button variant="secondary" x-on:click.prevent="$dispatch('close-modal', 'delete-placement-{{ $placement->id }}')">Batal</x-button>
                                                                    <form action="{{ route('admin.student-placements.destroy', $placement) }}" method="POST" class="inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <input type="hidden" name="redirect_to" value="student">
                                                                        <x-button variant="danger" type="submit">Hapus</x-button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </x-modal>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="py-8 text-center text-sm text-slate-500">
                                                    Belum ada histori penempatan kelas.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- Add Placement Modal -->
                <x-modal name="add-placement" maxWidth="xl">
                    <form action="{{ route('admin.student-placements.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="redirect_to" value="student">
                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                        
                        <div class="p-6 text-left whitespace-normal">
                            <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-5">Tambah Penempatan Siswa</h2>
                            
                            <div class="space-y-5">
                                <!-- Readonly Siswa Info -->
                                <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 flex flex-col gap-1">
                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Siswa</span>
                                    <div class="font-bold text-slate-900">{{ $student->user->name ?? '-' }}</div>
                                    <div class="text-xs text-slate-500 font-mono">NIS: {{ $student->nis ?? '-' }}</div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kelas <span class="text-danger">*</span></label>
                                        <x-select name="class_id" required placeholder="-- Pilih Kelas --" :selected="old('class_id')" :options="$classes->map(fn($cls) => ['value' => $cls->id, 'label' => $cls->name])->toArray()" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun Ajaran <span class="text-danger">*</span></label>
                                        <x-select name="academic_year_id" required placeholder="-- Pilih Tahun Ajaran --" :selected="old('academic_year_id', $academicYears->first()->id ?? null)" :options="$academicYears->map(fn($ay) => ['value' => $ay->id, 'label' => $ay->year])->toArray()" />
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end gap-3 pt-5 border-t border-slate-100">
                                <button type="button" x-on:click.prevent="$dispatch('close-modal', 'add-placement')" class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg-lg hover:bg-slate-50 transition-colors">Batal</button>
                                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-primary rounded-lg-lg hover:bg-blue-900 transition-colors">Simpan Penempatan</button>
                            </div>
                        </div>
                    </form>
                </x-modal>

                <!-- Orang Tua / Wali -->
                <x-card padding="lg">
                    <h3 class="text-base font-bold text-slate-900 mb-5 flex items-center gap-2">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                        Informasi Orang Tua / Wali
                    </h3>
                    
                    @if($student->parent)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                            <div>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap Wali</p>
                                <p class="text-sm font-medium text-slate-900">{{ $student->parent->user->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor Handphone (Aktif)</p>
                                <p class="text-sm font-medium text-slate-900">{{ $student->parent->phone ?? '-' }}</p>
                            </div>
                            <div class="md:col-span-2 pt-2 border-t border-slate-50">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Alamat Tempat Tinggal</p>
                                <p class="text-sm font-medium text-slate-900 leading-relaxed">{{ $student->parent->address ?? 'Alamat belum dilengkapi.' }}</p>
                            </div>
                        </div>
                    @else
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-center">
                            <p class="text-sm font-bold text-slate-900">Belum Terhubung dengan Wali</p>
                            <p class="text-sm text-slate-500 mt-1">Akun siswa ini belum dikaitkan dengan data orang tua/wali manapun.</p>
                        </div>
                    @endif
                </x-card>

                <!-- Data Akademik - Empty State -->
                <x-card padding="lg">
                    <h3 class="text-base font-bold text-slate-900 mb-5 flex items-center gap-2">
                        <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                        Laporan Akademik Terkini
                    </h3>
                    
                    <div class="bg-slate-50 border border-dashed border-slate-300 rounded-2xl p-8 text-center">
                        <div class="mx-auto w-12 h-12 bg-white shadow-sm rounded-full flex items-center justify-center text-slate-300 mb-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m-7.5 11.25h.008v.008H12v-.008zM12 15h.008v.008H12V15z" /></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-900">Belum ada Rekam Akademik</p>
                        <p class="text-sm text-slate-500 mt-1.5 max-w-sm mx-auto">Data riwayat nilai rapor, absen, tugas, dan aktivitas belajar lainnya akan muncul di sini nanti.</p>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.app>
