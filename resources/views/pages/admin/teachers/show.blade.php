<x-layouts.app>
    <x-slot:title>Detail Guru</x-slot:title>



    <div class="max-w-4xl space-y-6 mx-auto" x-data="{}">
        <div class="mb-4">
            <a href="{{ route('admin.teachers.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <div class="bg-white border border-slate-200/60 rounded-3xl p-6 md:p-8 shadow-sm space-y-8">
            <!-- Header Profil -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b pb-6">
                <div class="flex items-center gap-4">
                    <div class="h-16 w-16 bg-blue-100 text-blue-700 font-bold rounded-2xl flex items-center justify-center text-2xl">
                        {{ strtoupper(substr($teacher->user->name ?? 'G', 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-950">{{ $teacher->user->name ?? '-' }}</h1>
                        <p class="text-sm text-slate-500 font-medium">NIP: {{ $teacher->nip ?? '-' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" x-on:click.prevent="$dispatch('open-modal', 'add-assignment')" class="px-4 py-2.5 text-sm font-semibold text-white bg-accent hover:bg-blue-600 rounded-lg transition duration-150 inline-flex items-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Tambah Penugasan
                    </button>
                    <a href="{{ route('admin.teachers.edit', $teacher) }}" class="px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 border rounded-xl transition duration-150">
                        Edit Profil
                    </a>
                </div>
            </div>

            <!-- Detail Informasi -->
            <div class="grid grid-cols-1 gap-8">
                <div class="space-y-4">
                    <h2 class="text-base font-bold text-slate-900 border-b pb-2">Informasi Guru</h2>
                    <div class="space-y-3 text-sm">
                        <div class="grid grid-cols-3 md:grid-cols-6">
                            <span class="text-slate-500">Email</span>
                            <span class="col-span-2 md:col-span-5 font-semibold text-slate-800">{{ $teacher->user->email ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-3 md:grid-cols-6">
                            <span class="text-slate-500">Nomor HP</span>
                            <span class="col-span-2 md:col-span-5 font-semibold text-slate-800">{{ $teacher->phone ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-3 md:grid-cols-6">
                            <span class="text-slate-500">Alamat</span>
                            <span class="col-span-2 md:col-span-5 text-slate-800">{{ $teacher->address ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h2 class="text-base font-bold text-slate-900 border-b pb-2">Penugasan Mengajar</h2>
                    
                    @if(session('success'))
                        <div class="p-4 bg-green-50 border border-green-100 rounded-xl flex items-start gap-3 shadow-sm">
                            <svg class="h-5 w-5 text-green-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div>
                                <h3 class="text-sm font-bold text-emerald-700">Berhasil</h3>
                                <p class="text-sm text-green-700 mt-0.5">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="p-4 bg-red-50 border border-red-100 rounded-xl flex items-start gap-3 shadow-sm">
                            <svg class="h-5 w-5 text-danger mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <div>
                                <h3 class="text-sm font-bold text-red-800">Gagal</h3>
                                <p class="text-sm text-red-700 mt-0.5">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="p-4 bg-red-50 border border-red-100 rounded-xl flex items-start gap-3 shadow-sm">
                            <svg class="h-5 w-5 text-danger mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
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

                    <div class="overflow-x-auto border border-slate-100 rounded-xl">
                        <table class="w-full text-left border-collapse min-w-max">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
                                    <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas</th>
                                    <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Periode</th>
                                    <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($assignments ?? [] as $assignment)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="py-3 px-4">
                                            <p class="text-sm font-bold text-slate-900">{{ $assignment->subject->name }}</p>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="inline-flex items-center text-xs font-semibold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-lg border border-slate-200">
                                                {{ $assignment->classroom->name }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <p class="text-sm text-slate-600">{{ $assignment->academicYear?->year ?? '-' }} • {{ $assignment->semester?->name ?? '-' }}</p>
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <!-- Dropdown Menu / Buttons -->
                                            <div class="flex items-center justify-end gap-1.5 ">
                                                <button type="button" @click="$dispatch('open-modal', 'edit-assignment-{{ $assignment->id }}')" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit" aria-label="Edit guru">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                                </button>
                                                <button type="button" @click="$dispatch('open-modal', 'delete-assignment-{{ $assignment->id }}')" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus" aria-label="Hapus guru">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                                </button>
                                            </div>

                                            <!-- Edit Assignment Modal -->
                                            <x-modal name="edit-assignment-{{ $assignment->id }}" maxWidth="xl">
                                                <form action="{{ route('admin.teacher-assignments.update', $assignment) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="redirect_to" value="teacher">
                                                    <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                                                    
                                                    <div class="p-6 text-left whitespace-normal">
                                                        <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-5">Edit Penugasan Mengajar</h2>
                                                        
                                                        <div class="space-y-5">
                                                            <!-- Readonly Guru Info -->
                                                            <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 flex flex-col gap-1">
                                                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Guru</span>
                                                                <div class="font-bold text-slate-900">{{ $teacher->user->name ?? '-' }}</div>
                                                                <div class="text-xs text-slate-500 font-mono">NIP: {{ $teacher->nip ?? '-' }}</div>
                                                            </div>
                                                            
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                                                <div>
                                                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Mata Pelajaran <span class="text-danger">*</span></label>
                                                                    <select name="subject_id" required class="block w-full py-2.5 px-3 text-sm border border-slate-300 focus:border-accent focus:ring focus:ring-accent/20 rounded-lg-lg bg-white shadow-sm cursor-pointer">
                                                                        <option value="" disabled>-- Pilih Mapel --</option>
                                                                        @foreach($subjects as $sub)
                                                                            <option value="{{ $sub->id }}" @selected($assignment->subject_id == $sub->id)>{{ $sub->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div>
                                                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kelas <span class="text-danger">*</span></label>
                                                                    <select name="class_id" required class="block w-full py-2.5 px-3 text-sm border border-slate-300 focus:border-accent focus:ring focus:ring-accent/20 rounded-lg-lg bg-white shadow-sm cursor-pointer">
                                                                        <option value="" disabled>-- Pilih Kelas --</option>
                                                                        @foreach($classrooms as $cls)
                                                                            <option value="{{ $cls->id }}" @selected($assignment->class_id == $cls->id)>{{ $cls->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div>
                                                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun Ajaran <span class="text-danger">*</span></label>
                                                                    <select name="academic_year_id" required class="block w-full py-2.5 px-3 text-sm border border-slate-300 focus:border-accent focus:ring focus:ring-accent/20 rounded-lg-lg bg-white shadow-sm cursor-pointer">
                                                                        <option value="" disabled>-- Pilih Tahun Ajaran --</option>
                                                                        @foreach($academicYears as $ay)
                                                                            <option value="{{ $ay->id }}" @selected($assignment->academic_year_id == $ay->id)>{{ $ay->year }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div>
                                                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Semester <span class="text-danger">*</span></label>
                                                                    <x-semester-select name="semester_id" required class="block w-full py-2.5 px-3 text-sm border border-slate-300 focus:border-accent focus:ring focus:ring-accent/20 rounded-lg bg-white shadow-sm cursor-pointer" :selected="$assignment->semester_id" empty-label="-- Pilih Semester --" disabled-empty />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="mt-8 flex justify-end gap-3 pt-5 border-t border-slate-100">
                                                            <button type="button" x-on:click.prevent="$dispatch('close-modal', 'edit-assignment-{{ $assignment->id }}')" class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                                                            <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-primary rounded-lg-lg hover:bg-blue-900 transition-colors">Simpan Perubahan</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </x-modal>

                                            <!-- Delete Assignment Modal -->
                                            <x-modal name="delete-assignment-{{ $assignment->id }}" maxWidth="sm">
                                                <div class="p-6 text-left whitespace-normal text-slate-700">
                                                    <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">Hapus Penugasan?</h2>
                                                    
                                                    <div class="mb-4 space-y-1">
                                                        <p class="font-bold">{{ $assignment->subject->name }}</p>
                                                        <p class="text-sm">{{ $assignment->classroom->name }}</p>
                                                        <p class="text-sm">{{ $assignment->academicYear?->year ?? '-' }} • {{ $assignment->semester?->name ?? '-' }}</p>
                                                    </div>
                                                    
                                                    <p class="text-sm mt-4 text-danger font-medium bg-red-50 p-3 rounded-lg border border-red-100">"Penugasan ini akan dihapus."</p>
                                                    
                                                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-100">
                                                        <x-button variant="secondary" x-on:click.prevent="$dispatch('close-modal', 'delete-assignment-{{ $assignment->id }}')">Batal</x-button>
                                                        <form action="{{ route('admin.teacher-assignments.destroy', $assignment) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="redirect_to" value="teacher">
                                                            <x-button variant="danger" type="submit">Hapus</x-button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </x-modal>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-sm text-slate-500">
                                            Belum ada penugasan mengajar untuk guru ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Assignment Modal -->
    <x-modal name="add-assignment" maxWidth="xl">
        <form action="{{ route('admin.teacher-assignments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="redirect_to" value="teacher">
            <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
            
            <div class="p-6 text-left whitespace-normal">
                <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-5">Tambah Penugasan Mengajar</h2>
                
                <div class="space-y-5">
                    <!-- Readonly Guru Info -->
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 flex flex-col gap-1">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Guru</span>
                        <div class="font-bold text-slate-900">{{ $teacher->user->name ?? '-' }}</div>
                        <div class="text-xs text-slate-500 font-mono">NIP: {{ $teacher->nip ?? '-' }}</div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Mata Pelajaran <span class="text-danger">*</span></label>
                            <select name="subject_id" required class="block w-full py-2.5 px-3 text-sm border border-slate-300 focus:border-accent focus:ring focus:ring-accent/20 rounded-lg-lg bg-white shadow-sm cursor-pointer">
                                <option value="" disabled selected>-- Pilih Mapel --</option>
                                @foreach($subjects as $sub)
                                    <option value="{{ $sub->id }}" @selected(old('subject_id') == $sub->id)>{{ $sub->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kelas <span class="text-danger">*</span></label>
                            <select name="class_id" required class="block w-full py-2.5 px-3 text-sm border border-slate-300 focus:border-accent focus:ring focus:ring-accent/20 rounded-lg-lg bg-white shadow-sm cursor-pointer">
                                <option value="" disabled selected>-- Pilih Kelas --</option>
                                @foreach($classrooms as $cls)
                                    <option value="{{ $cls->id }}" @selected(old('class_id') == $cls->id)>{{ $cls->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun Ajaran <span class="text-danger">*</span></label>
                            <select name="academic_year_id" required class="block w-full py-2.5 px-3 text-sm border border-slate-300 focus:border-accent focus:ring focus:ring-accent/20 rounded-lg-lg bg-white shadow-sm cursor-pointer">
                                <option value="" disabled selected>-- Pilih Tahun Ajaran --</option>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" @selected(old('academic_year_id', $academicYears->first()->id ?? null) == $ay->id)>{{ $ay->year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Semester <span class="text-danger">*</span></label>
                            <x-semester-select name="semester_id" required class="block w-full py-2.5 px-3 text-sm border border-slate-300 focus:border-accent focus:ring focus:ring-accent/20 rounded-lg bg-white shadow-sm cursor-pointer" :selected="old('semester_id')" empty-label="-- Pilih Semester --" disabled-empty />
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3 pt-5 border-t border-slate-100">
                    <button type="button" x-on:click.prevent="$dispatch('close-modal', 'add-assignment')" class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg-lg hover:bg-slate-50 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-primary rounded-lg-lg hover:bg-blue-900 transition-colors">Simpan Penugasan</button>
                </div>
            </div>
        </form>
    </x-modal>
</x-layouts.app>
