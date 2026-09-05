<x-layouts.app>
    <x-slot:title>Tambah Penempatan Siswa</x-slot:title>

    <div class="w-full space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                @if(request('redirect_to') === 'student' && request('student_id'))
                    <a href="{{ route('admin.students.show', request('student_id')) }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition mb-1">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Kembali ke Detail Siswa
                    </a>
                @else
                    <a href="{{ route('admin.student-placements.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition mb-1">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Kembali ke Daftar Penempatan
                    </a>
                @endif
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Penempatan Siswa</h1>
                <p class="text-sm text-slate-500">Tentukan kelas bagi siswa yang belum terdaftar di tahun ajaran aktif.</p>
            </div>

            <!-- Context Badge -->
            <div class="flex items-center gap-2 px-3.5 py-2 bg-white border border-slate-200 rounded-xl shadow-xs shrink-0 self-start sm:self-auto">
                <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <div class="text-xs">
                    <span class="text-slate-400 font-medium">Tahun Ajaran:</span>
                    <span class="font-bold text-slate-700 ml-1">{{ $activeAcademicYear->year }}</span>
                </div>
            </div>
        </div>

        <x-card padding="none" class="overflow-hidden shadow-xs border border-slate-200" x-data="{
            search: '',
            selected: [],
            students: {{ \Illuminate\Support\Js::from($students->map(fn($s) => ['id' => (string)$s->id, 'name' => (string)($s->user->name ?? ''), 'nis' => (string)($s->nis ?? '-')])) }},
            get filteredStudents() {
                if (!this.search || this.search.trim() === '') {
                    return this.students;
                }
                const lowerSearch = this.search.toLowerCase().trim();
                return this.students.filter(s => 
                    String(s.name || '').toLowerCase().includes(lowerSearch) || 
                    String(s.nis || '').toLowerCase().includes(lowerSearch)
                );
            },
            get allSelected() {
                return this.filteredStudents.length > 0 && this.filteredStudents.every(s => this.selected.includes(s.id));
            },
            toggleAll() {
                if (this.allSelected) {
                    const filteredIds = this.filteredStudents.map(fs => fs.id);
                    this.selected = this.selected.filter(id => !filteredIds.includes(id));
                } else {
                    const toAdd = this.filteredStudents.map(fs => fs.id).filter(id => !this.selected.includes(id));
                    this.selected = [...this.selected, ...toAdd];
                }
            },
            getInitials(name) {
                if (!name) return '?';
                return name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
            }
        }">
            <form action="{{ route('admin.student-placements.store') }}" method="POST">
                @csrf
                <input type="hidden" name="academic_year_id" value="{{ $activeAcademicYear->id }}">

                <div class="p-6 md:p-8 space-y-8">
                    <!-- Validation Errors -->
                    @if($errors->any())
                        <div class="p-4 bg-red-50/80 border border-red-200/80 rounded-xl text-sm text-red-700 flex items-start gap-3">
                            <svg class="h-5 w-5 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <h4 class="font-semibold text-red-900 mb-1">Terjadi kesalahan pada input:</h4>
                                <ul class="list-disc list-inside space-y-0.5 text-xs">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Parameter Setup Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 bg-slate-50/70 border border-slate-200/80 rounded-2xl">
                        <!-- Target Kelas -->
                        <div class="space-y-2">
                            <label for="class_id" class="block text-sm font-semibold text-slate-800">
                                Target Kelas <span class="text-red-500">*</span>
                            </label>
                            @if($classrooms->isEmpty())
                                <div class="p-3.5 bg-amber-50 border border-amber-200/80 rounded-xl flex items-start gap-3">
                                    <svg class="h-5 w-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <div class="text-xs text-amber-800">
                                        <p class="font-medium">Belum ada kelas pada tahun ajaran aktif.</p>
                                        <a href="{{ route('admin.classes.create') }}" class="underline font-semibold hover:text-amber-900 mt-0.5 inline-block">Buat Kelas Baru &rarr;</a>
                                    </div>
                                </div>
                            @else
                                <div class="relative">
                                    <select id="class_id" name="class_id" class="block w-full pl-3.5 pr-10 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs" required>
                                        <option value="" disabled selected>-- Pilih Kelas Tujuan --</option>
                                        @foreach($classrooms as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }} (Tingkat {{ $class->level }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>

                        <!-- Info Tahun Ajaran -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-800">Tahun Ajaran Aktif</label>
                            <div class="flex items-center justify-between pl-5 pr-3.5 py-2.5 bg-slate-100/70 border border-slate-300 rounded-xl text-slate-800 text-sm font-semibold shadow-2xs">
                                <div class="flex items-center gap-4">
                                    <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                    <span>{{ $activeAcademicYear->year }}</span>
                                </div>
                                <span class="text-xs bg-emerald-100 text-emerald-700 font-semibold px-2.5 py-0.5 rounded-full border border-emerald-200/60">Aktif</span>
                            </div>
                        </div>
                    </div>

                    <!-- Selection & Table Container -->
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Daftar Siswa Belum Terdaftar</h3>
                                <p class="text-xs text-slate-500">Pilih siswa yang akan dimasukkan ke kelas yang telah ditentukan.</p>
                            </div>
                            
                            <!-- Search Input -->
                            <div class="relative w-full sm:w-72">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                </div>
                                <input type="text" x-model="search" placeholder="Cari Nama / NIS..." class="block w-full pl-9 pr-3 py-2 bg-white border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder:text-slate-400 transition">
                            </div>
                        </div>

                        <!-- Interactive Table Card -->
                        <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-xs">
                            <div class="max-h-[420px] overflow-y-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead class="bg-slate-50/90 backdrop-blur-xs sticky top-0 z-10 border-b border-slate-200">
                                        <tr>
                                            <th scope="col" class="py-3 px-4 w-12 text-center">
                                                <input type="checkbox" :checked="allSelected" @change="toggleAll()" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                            </th>
                                            <th scope="col" class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Siswa</th>
                                            <th scope="col" class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">NIS</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <template x-for="student in filteredStudents" :key="student.id">
                                            <tr class="hover:bg-slate-50/80 transition-colors" :class="{'bg-blue-50/40': selected.includes(student.id)}">
                                                <td class="py-3 px-4 text-center">
                                                    <input type="checkbox" :value="student.id" name="student_ids[]" x-model="selected" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                                </td>
                                                <td class="py-3 px-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="h-8 w-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 shrink-0" x-text="getInitials(student.name)"></div>
                                                        <span class="text-sm font-semibold text-slate-800" x-text="student.name"></span>
                                                    </div>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span class="inline-block px-2 py-0.5 text-xs font-mono font-medium text-slate-600 bg-slate-100 rounded-md" x-text="student.nis"></span>
                                                </td>
                                            </tr>
                                        </template>
                                        
                                        <!-- Empty State -->
                                        <tr x-show="filteredStudents.length === 0">
                                            <td colspan="3" class="py-12 px-4 text-center">
                                                <div class="flex flex-col items-center justify-center space-y-2">
                                                    <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                                        </svg>
                                                    </div>
                                                    <p class="text-sm font-medium text-slate-600">Tidak ada siswa yang sesuai.</p>
                                                    <p class="text-xs text-slate-400">Seluruh siswa sudah memiliki kelas atau kata kunci pencarian tidak ditemukan.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Table Footer Status Bar -->
                            <div class="bg-slate-50 px-4 py-3 border-t border-slate-200 flex items-center justify-between text-xs text-slate-600 font-medium">
                                <div>
                                    Total tersedia: <span class="font-bold text-slate-900" x-text="students.length"></span> siswa
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span>Terpilih:</span>
                                    <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 font-bold" x-text="selected.length"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/60 flex flex-col-reverse sm:flex-row items-center justify-between gap-3">
                    <span class="text-xs text-slate-500">
                        <span x-text="selected.length"></span> siswa akan ditempatkan di kelas tujuan.
                    </span>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <x-button variant="secondary" href="{{ route('admin.student-placements.index') }}" class="w-full sm:w-auto">Batal</x-button>
                        <x-button variant="primary" type="submit" class="w-full sm:w-auto justify-center" x-bind:disabled="selected.length === 0 || {{ $classrooms->isEmpty() ? 'true' : 'false' }}">
                            <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tempatkan Siswa (<span x-text="selected.length"></span>)
                        </x-button>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
