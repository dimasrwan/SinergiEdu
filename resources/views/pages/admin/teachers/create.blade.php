<x-layouts.app>
    <x-slot:title>Tambah Guru</x-slot:title>

    <div class="w-full">
        <div class="mb-6 flex flex-col items-start gap-4">
            <a href="{{ route('admin.teachers.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Tambah Guru Baru</h1>
                <p class="mt-1 text-sm text-slate-500">Buat akun guru baru serta atur kombinasi kelas dan mata pelajaran yang diampu.</p>
            </div>
        </div>

        <x-card padding="none" class="overflow-hidden">
            <form action="{{ route('admin.teachers.store') }}" method="POST" x-data="teacherForm()" @submit="submitForm($event)">
                @csrf

                <div class="p-6 md:p-8 space-y-10">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <!-- Informasi Akun -->
                        <div class="space-y-6">
                            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Informasi Akun</h2>
                            
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-danger">*</span></label>
                                <x-text-input id="name" name="name" type="text" :value="old('name')" placeholder="Masukkan nama lengkap guru" required class="w-full" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email Akses <span class="text-danger">*</span></label>
                                <x-text-input id="email" name="email" type="email" :value="old('email')" placeholder="Masukkan alamat email guru" required class="w-full" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password Sementara <span class="text-danger">*</span></label>
                                <x-password-input id="password" name="password" required placeholder="Buat password minimal 8 karakter" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Password <span class="text-danger">*</span></label>
                                <x-password-input id="password_confirmation" name="password_confirmation" required placeholder="Masukkan kembali password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs" />
                            </div>
                        </div>

                        <!-- Profil Guru -->
                        <div class="space-y-6">
                            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Profil Kepegawaian</h2>

                            <div>
                                <label for="nip" class="block text-sm font-semibold text-slate-700 mb-1.5">NIP (Nomor Induk Pegawai) <span class="text-danger">*</span></label>
                                <x-text-input id="nip" name="nip" type="text" :value="old('nip')" placeholder="Masukkan NIP atau nomor identitas unik" required class="w-full" />
                                <x-input-error :messages="$errors->get('nip')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor HP / WhatsApp</label>
                                <x-text-input id="phone" name="phone" type="text" :value="old('phone')" placeholder="Contoh: 081234567890" class="w-full" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2 text-xs" />
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Tempat Tinggal</label>
                                <x-textarea id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap tempat tinggal guru">{{ old('address') }}</x-textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2 text-xs" />
                            </div>
                        </div>
                    </div>

                    <!-- Penugasan Dinamis -->
                    <div class="space-y-4">
                        <div class="border-b border-slate-100 pb-2 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h2 class="text-base font-bold text-slate-900">Penugasan Kelas & Mata Pelajaran</h2>
                                <p class="text-sm text-slate-500 mt-0.5">Tentukan kombinasi spesifik kelas dan mata pelajaran untuk guru ini.</p>
                            </div>
                            <button type="button" @click="addAssignment" class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition shrink-0">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                Tambah Penugasan
                            </button>
                        </div>

                        <!-- Backend Validation Global Errors for assignments -->
                        @if($errors->has('assignments') || $errors->has('assignments.*'))
                            <div class="p-3 bg-red-50 border border-red-100 rounded-lg text-sm text-danger">
                                <ul class="list-disc pl-5">
                                    @if($errors->has('assignments'))<li>{{ $errors->first('assignments') }}</li>@endif
                                    @foreach($errors->get('assignments.*') as $errArray)
                                        @foreach($errArray as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="bg-slate-50 rounded-xl border border-slate-200 overflow-hidden">
                            <!-- Desktop/Tablet Header -->
                            <div class="hidden md:grid grid-cols-12 gap-4 px-5 py-3 border-b border-slate-200 bg-slate-100/50">
                                <div class="col-span-1 text-xs font-bold text-slate-500 uppercase tracking-wider">No</div>
                                <div class="col-span-5 text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas / Rombel</div>
                                <div class="col-span-5 text-xs font-bold text-slate-500 uppercase tracking-wider">Mata Pelajaran</div>
                                <div class="col-span-1 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</div>
                            </div>
                            
                            <!-- Dynamic Rows -->
                            <div class="divide-y divide-slate-100">
                                <template x-for="(assignment, index) in assignments" :key="assignment.id">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 px-5 py-4 items-start relative group transition-colors hover:bg-white" :class="{'bg-red-50/30': isDuplicate(index)}">
                                        
                                        <!-- Mobile Label & No -->
                                        <div class="md:col-span-1 flex items-center pt-2 md:pt-0">
                                            <span class="text-sm font-semibold text-slate-900" x-text="index + 1"></span>
                                            <span class="ml-2 text-xs text-slate-400 font-medium md:hidden">Penugasan</span>
                                        </div>

                                        <!-- Dropdown Kelas -->
                                        <div class="md:col-span-5 relative">
                                            <label class="block text-xs font-semibold text-slate-500 mb-1 md:hidden">Pilih Kelas</label>
                                            <select x-model="assignment.class_id" :name="`assignments[${index}][class_id]`" required
                                                class="block w-full py-2.5 text-sm border-slate-300 focus:border-accent focus:ring focus:ring-accent/20 rounded-lg bg-white shadow-sm cursor-pointer">
                                                <option value="" disabled>-- Pilih Kelas --</option>
                                                <template x-for="cls in classes" :key="cls.id">
                                                    <option :value="cls.id" x-text="`${cls.name} (Tingkat ${cls.grade_level})`"></option>
                                                </template>
                                            </select>
                                        </div>

                                        <!-- Dropdown Mapel -->
                                        <div class="md:col-span-5 relative">
                                            <label class="block text-xs font-semibold text-slate-500 mb-1 md:hidden">Pilih Mata Pelajaran</label>
                                            <select x-model="assignment.subject_id" :name="`assignments[${index}][subject_id]`" required
                                                class="block w-full py-2.5 text-sm border-slate-300 focus:border-accent focus:ring focus:ring-accent/20 rounded-lg bg-white shadow-sm cursor-pointer">
                                                <option value="" disabled>-- Pilih Mata Pelajaran --</option>
                                                <template x-for="sub in subjects" :key="sub.id">
                                                    <option :value="sub.id" x-text="`${sub.name}`"></option>
                                                </template>
                                            </select>
                                            
                                            <!-- Duplicate Error -->
                                            <template x-if="isDuplicate(index)">
                                                <div class="text-xs text-danger font-medium mt-1.5 flex items-start gap-1">
                                                    <svg class="h-3.5 w-3.5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                                    Kombinasi kelas dan mapel ini sudah ditambahkan di baris lain.
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Hapus Button -->
                                        <div class="md:col-span-1 flex items-center justify-end md:justify-center md:pt-2">
                                            <button type="button" @click="removeAssignment(assignment.id)" class="p-2 text-slate-400 hover:text-danger hover:bg-red-50 rounded-lg transition-colors" aria-label="Hapus penugasan">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                                
                                <template x-if="assignments.length === 0">
                                    <div class="px-5 py-8 text-center bg-white">
                                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 text-slate-400 mb-3">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-700">Belum ada penugasan yang ditambahkan</p>
                                        <p class="text-xs text-slate-500 mt-1">Silakan klik "Tambah Penugasan" untuk menetapkan kelas dan mapel.</p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 sm:gap-4">
                    <x-button variant="secondary" href="{{ route('admin.teachers.index') }}" class="w-full sm:w-auto">Batal</x-button>
                    
                    <button type="submit" :disabled="isSubmitting || hasDuplicate()" 
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-primary hover:bg-primary-hover active:bg-blue-900 rounded-xl transition-all shadow-sm shadow-primary/20 disabled:opacity-70 disabled:cursor-not-allowed">
                        <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display: none;">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Data Guru'"></span>
                    </button>
                </div>
            </form>
        </x-card>
    </div>

    @push('scripts')
    <script>
        function teacherForm() {
            // Restore previous inputs if available from validation errors
            const oldAssignments = @json(old('assignments', []));
            const initialAssignments = oldAssignments.length > 0 
                ? oldAssignments.map((a, i) => ({ id: Date.now() + i, class_id: a.class_id || '', subject_id: a.subject_id || '' }))
                : [{ id: Date.now(), class_id: '', subject_id: '' }];

            return {
                assignments: initialAssignments,
                classes: @json($classes->map->only(['id', 'name', 'grade_level'])),
                subjects: @json($subjects->map->only(['id', 'name', 'code'])),
                isSubmitting: false,

                addAssignment() {
                    this.assignments.push({
                        id: Date.now(),
                        class_id: '',
                        subject_id: ''
                    });
                },

                removeAssignment(id) {
                    this.assignments = this.assignments.filter(a => a.id !== id);
                },

                isDuplicate(index) {
                    const current = this.assignments[index];
                    if (!current.class_id || !current.subject_id) return false;
                    
                    return this.assignments.some((a, i) => {
                        return i !== index && a.class_id == current.class_id && a.subject_id == current.subject_id;
                    });
                },

                hasDuplicate() {
                    return this.assignments.some((_, i) => this.isDuplicate(i));
                },

                submitForm(event) {
                    if (this.hasDuplicate()) {
                        event.preventDefault();
                        return;
                    }
                    
                    // Allow normal form submission, just disable button visually
                    this.isSubmitting = true;
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
