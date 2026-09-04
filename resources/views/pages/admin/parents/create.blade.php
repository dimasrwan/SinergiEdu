<x-layouts.app>
    <x-slot:title>Tambah Orang Tua / Wali</x-slot:title>

    <div class="w-full">
        <div class="mb-6 flex flex-col items-start gap-4">
            <a href="{{ route('admin.parents.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Tambah Orang Tua / Wali</h1>
                <p class="mt-1 text-sm text-slate-500">Buat akun akses dan profil, serta tautkan dengan data siswa yang bersangkutan.</p>
            </div>
        </div>

        <x-card padding="none" class="overflow-hidden">
            <form action="{{ route('admin.parents.store') }}" method="POST" x-data="parentForm()" @submit="submitForm($event)">
                @csrf

                <div class="p-6 md:p-8 space-y-10">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <!-- Informasi Akun -->
                        <div class="space-y-6">
                            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Informasi Akun</h2>
                            
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Wali <span class="text-danger">*</span></label>
                                <x-text-input id="name" name="name" type="text" :value="old('name')" placeholder="Masukkan nama lengkap wali murid" required class="w-full" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email Wali (Akses Login) <span class="text-danger">*</span></label>
                                <x-text-input id="email" name="email" type="email" :value="old('email')" placeholder="Misal: orangtua@domain.com" required class="w-full" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password <span class="text-danger">*</span></label>
                                <x-password-input id="password" name="password" required placeholder="Minimal 8 karakter" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Password <span class="text-danger">*</span></label>
                                <x-password-input id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password" />
                            </div>
                        </div>

                        <!-- Profil Wali -->
                        <div class="space-y-6">
                            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Profil Orang Tua</h2>

                            <div>
                                <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor HP</label>
                                <x-text-input id="phone" name="phone" type="text" :value="old('phone')" placeholder="Masukkan nomor HP" class="w-full" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Rumah</label>
                                <x-textarea id="address" name="address" rows="5" placeholder="Masukkan alamat lengkap">{{ old('address') }}</x-textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2 text-xs" />
                            </div>
                        </div>
                    </div>

                    <!-- Hubungkan Anak -->
                    <div class="space-y-4 pt-6 border-t border-slate-100">
                        <div class="border-b border-slate-100 pb-2">
                            <h2 class="text-base font-bold text-slate-900">Hubungkan dengan Siswa (Anak)</h2>
                            <p class="text-sm text-slate-500 mt-0.5">Pilih satu atau beberapa anak yang ingin dihubungkan.</p>
                        </div>

                        <!-- Backend Validation Errors -->
                        @if($errors->has('students') || $errors->has('students.*'))
                            <div class="p-3 bg-red-50 border border-red-100 rounded-lg text-sm text-danger">
                                <ul class="list-disc pl-5">
                                    @if($errors->has('students'))<li>{{ $errors->first('students') }}</li>@endif
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                            <!-- Kolom Kiri: Search & List -->
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 shadow-sm">
                                <div class="relative w-full mb-4">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                                    </div>
                                    <input type="text" x-model="searchQuery" placeholder="Cari nama atau NIS siswa..." 
                                        class="block w-full pl-9 pr-3 py-2 border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent text-sm">
                                </div>
                                
                                <div class="max-h-60 overflow-y-auto space-y-2 pr-1 custom-scrollbar">
                                    <template x-for="student in filteredAvailableStudents" :key="student.id">
                                        <div class="flex items-center justify-between p-3 bg-white border border-slate-200 rounded-xl hover:border-accent hover:shadow-sm transition-all group">
                                            <div>
                                                <p class="text-sm font-bold text-slate-900" x-text="student.name"></p>
                                                <p class="text-xs text-slate-500 font-mono mt-0.5" x-text="`NIS: ${student.nis}`"></p>
                                            </div>
                                            <button type="button" @click="addStudent(student)" class="text-xs font-semibold text-primary bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors shrink-0">
                                                Tambahkan
                                            </button>
                                        </div>
                                    </template>
                                    <template x-if="filteredAvailableStudents.length === 0">
                                        <div class="text-center py-6">
                                            <p class="text-sm font-semibold text-slate-900" x-text="searchQuery ? 'Tidak ada kecocokan.' : 'Tidak ada data siswa.'"></p>
                                            <p class="text-xs text-slate-500 mt-1" x-text="searchQuery ? 'Coba nama atau NIS lain.' : 'Seluruh siswa telah memiliki wali.'"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Kolom Kanan: Selected Students -->
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                                    Siswa yang dipilih
                                    <span class="inline-flex items-center justify-center bg-blue-100 text-primary text-[10px] font-bold h-5 min-w-[20px] rounded-full px-1.5" x-text="selectedStudents.length"></span>
                                </h3>
                                
                                <div class="space-y-3">
                                    <template x-for="student in selectedStudents" :key="student.id">
                                        <div class="flex items-start justify-between p-4 bg-white border border-slate-200 shadow-sm rounded-xl relative overflow-hidden group">
                                            <!-- Aksens Kiri -->
                                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-accent"></div>
                                            <div class="pl-2">
                                                <p class="text-sm font-bold text-slate-900" x-text="student.name"></p>
                                                <p class="text-xs text-slate-500 font-mono mt-0.5 mb-1" x-text="`NIS: ${student.nis}`"></p>
                                                <span class="inline-flex text-[10px] font-bold uppercase tracking-wider text-primary bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100" x-text="student.active_class ?? 'Belum ada kelas'"></span>
                                            </div>
                                            <button type="button" @click="removeStudent(student.id)" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Batal Pilih">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                            
                                            <!-- Hidden Inputs for Submission -->
                                            <input type="hidden" name="students[]" :value="student.id">
                                        </div>
                                    </template>
                                    
                                    <template x-if="selectedStudents.length === 0">
                                        <div class="px-5 py-8 text-center bg-slate-50 border border-dashed border-slate-300 rounded-xl">
                                            <p class="text-sm font-bold text-slate-700">Belum ada yang dihubungkan</p>
                                            <p class="text-xs text-slate-500 mt-1">Gunakan panel di sebelah kiri untuk mencari dan menambahkan anak.</p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 sm:gap-4">
                    <x-button variant="secondary" href="{{ route('admin.parents.index') }}" class="w-full sm:w-auto">Batal</x-button>
                    
                    <button type="submit" :disabled="isSubmitting" 
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-primary hover:bg-primary-hover active:bg-blue-900 rounded-xl transition-all shadow-sm shadow-primary/20 disabled:opacity-70 disabled:cursor-not-allowed">
                        <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display: none;">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Data Orang Tua'"></span>
                    </button>
                </div>
            </form>
        </x-card>
    </div>

    @push('scripts')
    <script>
        function parentForm() {
            // Menerima data dari PHP
            @php
                $mappedStudents = $students->map(function($s) {
                    return [
                        'id' => $s->id,
                        'name' => $s->user->name ?? '-',
                        'nis' => $s->nis ?? '-',
                        'active_class' => $s->activeClassroom()?->name ?? 'Belum ada kelas'
                    ];
                });
            @endphp
            
            const allStudentsRaw = {!! json_encode($mappedStudents) !!};
            
            // Rekonstruksi selection jika ada validasi error (old input)
            const oldSelectedIds = @json(old('students', []));
            const initialSelected = allStudentsRaw.filter(s => oldSelectedIds.includes(String(s.id)) || oldSelectedIds.includes(Number(s.id)));
            const initialAvailable = allStudentsRaw.filter(s => !oldSelectedIds.includes(String(s.id)) && !oldSelectedIds.includes(Number(s.id)));

            return {
                availableStudents: initialAvailable,
                selectedStudents: initialSelected,
                searchQuery: '',
                isSubmitting: false,

                get filteredAvailableStudents() {
                    if (this.searchQuery.trim() === '') {
                        return this.availableStudents;
                    }
                    const q = this.searchQuery.toLowerCase();
                    return this.availableStudents.filter(student => 
                        student.name.toLowerCase().includes(q) || 
                        String(student.nis).toLowerCase().includes(q)
                    );
                },

                addStudent(student) {
                    this.selectedStudents.push(student);
                    this.availableStudents = this.availableStudents.filter(s => s.id !== student.id);
                    // Jangan mereset query agar lebih enak mencari massal
                },

                removeStudent(studentId) {
                    const student = this.selectedStudents.find(s => s.id === studentId);
                    if (student) {
                        this.availableStudents.push(student);
                        // Sort kembali berdasarkan nama
                        this.availableStudents.sort((a, b) => a.name.localeCompare(b.name));
                        this.selectedStudents = this.selectedStudents.filter(s => s.id !== studentId);
                    }
                },

                submitForm(event) {
                    this.isSubmitting = true;
                }
            }
        }
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @endpush
</x-layouts.app>
