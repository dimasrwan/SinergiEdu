<x-layouts.app>
    <x-slot:title>Manajemen Kelas</x-slot:title>

    <div class="w-full space-y-5 sm:space-y-6" x-data="{
        deleteModalOpen: false,
        classNameToDelete: '',
        deleteFormId: '',
        confirmDelete(name, formId) {
            this.classNameToDelete = name;
            this.deleteFormId = formId;
            this.deleteModalOpen = true;
        },
        submitDelete() {
            if (this.deleteFormId) {
                document.getElementById(this.deleteFormId).submit();
            }
        }
    }">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Manajemen Kelas</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200/80">
                        {{ $classes->total() }} kelas terdaftar
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed">
                    Kelola master data kelas, wali kelas, dan tahun ajaran aktif.
                </p>
            </div>
            <div class="w-full sm:w-auto shrink-0 pt-1 sm:pt-0">
                <x-button variant="primary" href="{{ route('admin.classes.create') }}" class="w-full sm:w-auto justify-center min-h-[44px]">
                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Kelas
                </x-button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-100 rounded-xl flex items-start gap-3 shadow-2xs">
                <svg class="h-5 w-5 text-green-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div>
                    <h3 class="text-xs sm:text-sm font-bold text-emerald-700">Berhasil</h3>
                    <p class="text-xs sm:text-sm text-green-700 mt-0.5 leading-snug">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-red-50 border border-red-100 rounded-xl flex items-start gap-3 shadow-2xs">
                <svg class="h-5 w-5 text-danger mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <div>
                    <h3 class="text-xs sm:text-sm font-bold text-red-800">Gagal</h3>
                    <p class="text-xs sm:text-sm text-red-700 mt-0.5 leading-snug">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <x-card padding="none" class="overflow-hidden">
            <!-- Filter Bar -->
            <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/50" x-data="{
                jenjang: '{{ request('education_level') }}',
                tingkat: '{{ request('grade_level') }}',
                onJenjangChange() {
                    this.tingkat = '';
                }
            }">
                <form action="{{ route('admin.classes.index') }}" method="GET" class="flex flex-col gap-3 w-full">
                    <!-- Search Input -->
                    <div class="w-full">
                        <div class="flex items-center w-full min-h-[44px] px-4 py-2.5 border border-slate-300 rounded-xl bg-white focus-within:ring-2 focus-within:ring-accent focus-within:border-accent transition-shadow">
                            <svg class="h-4 w-4 text-slate-400 shrink-0 mr-3 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kelas..." 
                                class="flex-1 w-full min-w-0 bg-transparent border-0 p-0 focus:outline-none focus:ring-0 text-slate-800 placeholder:text-slate-400 text-xs sm:text-sm font-medium">
                        </div>
                    </div>

                    <!-- Dropdowns and Actions Grid -->
                    <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2.5 w-full">
                        <div class="flex-1 min-w-[140px]">
                            <x-select name="education_level" 
                                      placeholder="Semua Jenjang" 
                                      :selected="request('education_level')" 
                                      :options="[
                                          ['value' => 'SD', 'label' => 'SD / MI'],
                                          ['value' => 'SMP', 'label' => 'SMP / MTs'],
                                          ['value' => 'SMA', 'label' => 'SMA / MA / SMK']
                                      ]" 
                                      x-model="jenjang" 
                                      @change="onJenjangChange"
                                      class="min-h-[44px]" />
                        </div>

                        <div class="flex-1 min-w-[130px]">
                            <x-select name="grade_level" 
                                      placeholder="Semua Tingkat" 
                                      :selected="request('grade_level')" 
                                      :options="[
                                          ['value' => '1', 'label' => 'Tingkat 1'],
                                          ['value' => '2', 'label' => 'Tingkat 2'],
                                          ['value' => '3', 'label' => 'Tingkat 3'],
                                          ['value' => '4', 'label' => 'Tingkat 4'],
                                          ['value' => '5', 'label' => 'Tingkat 5'],
                                          ['value' => '6', 'label' => 'Tingkat 6'],
                                          ['value' => '7', 'label' => 'Tingkat 7'],
                                          ['value' => '8', 'label' => 'Tingkat 8'],
                                          ['value' => '9', 'label' => 'Tingkat 9'],
                                          ['value' => '10', 'label' => 'Tingkat 10'],
                                          ['value' => '11', 'label' => 'Tingkat 11'],
                                          ['value' => '12', 'label' => 'Tingkat 12']
                                      ]" 
                                      class="min-h-[44px]" />
                        </div>
                        
                        <div class="flex-1 min-w-[160px]">
                            <x-select name="academic_year_id" 
                                      placeholder="Semua Tahun Ajaran" 
                                      :selected="request('academic_year_id')" 
                                      :options="$academicYears->map(fn($y) => ['value' => $y->id, 'label' => 'TA ' . $y->year])->toArray()" 
                                      class="min-h-[44px]" />
                        </div>
                        
                        <div class="flex gap-2 shrink-0 sm:w-auto">
                            <button type="submit" class="flex-1 sm:flex-initial px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-bold rounded-xl border border-slate-200/80 transition-colors text-center min-h-[44px]">
                                Filter
                            </button>
                            @if(request('search') || request('grade_level') || request('academic_year_id') || request('education_level'))
                                <a href="{{ route('admin.classes.index') }}" class="flex-1 sm:flex-initial px-4 py-2.5 bg-white hover:bg-red-50 text-slate-500 hover:text-danger text-xs sm:text-sm font-bold rounded-xl border border-slate-200/80 transition-colors text-center min-h-[44px] inline-flex items-center justify-center">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Desktop View Table (hidden lg:block) -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead>
                        <tr class="bg-white border-b border-slate-100">
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider w-10">NO</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">KELAS / ROMBEL</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">JENJANG</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">TINGKAT</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">WALI KELAS</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">TAHUN AJARAN</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center">JUMLAH SISWA</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($classes as $index => $class)
                            <tr class="hover:bg-slate-50/50 transition-colors group {{ optional($class->academicYear)->is_active ? 'bg-blue-50/20' : '' }}">
                                <td class="py-4 px-6 text-sm font-medium text-slate-400">
                                    {{ $classes->firstItem() + $index }}
                                </td>
                                <td class="py-4 px-6">
                                    <p class="text-sm font-bold text-primary">{{ $class->name }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center text-xs font-semibold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                                        {{ $class->education_level }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-primary font-bold text-xs shadow-2xs">
                                        {{ $class->grade_level }}
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-sm text-slate-700 font-medium break-words whitespace-normal">
                                    {{ $class->homeroomTeacher->user->name ?? 'Belum ditentukan' }}
                                </td>
                                <td class="py-4 px-6 text-sm text-slate-700 font-medium">
                                    {{ $class->academicYear->year ?? 'Belum ditentukan' }}
                                </td>
                                <td class="py-4 px-6 text-center text-sm font-bold {{ $class->students_count > 0 ? 'text-slate-900' : 'text-slate-400' }}">
                                    {{ $class->students_count }} siswa
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.classes.show', $class) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail" aria-label="Lihat detail kelas">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </a>
                                        <a href="{{ route('admin.classes.edit', $class) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit" aria-label="Edit kelas">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                        </a>
                                        <form id="delete-class-form-desktop-{{ $class->id }}" action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                @click="confirmDelete('{{ addslashes($class->name) }}', 'delete-class-form-desktop-{{ $class->id }}')" 
                                                class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                                title="Hapus" 
                                                aria-label="Hapus kelas {{ $class->name }}">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center">
                                    <div class="mx-auto w-16 h-16 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-4">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-900">
                                        @if(request('search') || request('grade_level') || request('academic_year_id') || request('education_level'))
                                            Belum ada kelas yang sesuai.
                                        @else
                                            Belum ada data kelas
                                        @endif
                                    </h3>
                                    <p class="text-sm text-slate-500 mt-1 mb-4">
                                        @if(request('search') || request('grade_level') || request('academic_year_id') || request('education_level'))
                                            Coba ubah kata kunci pencarian atau filter Anda.
                                        @else
                                            Belum terdapat data kelas yang didaftarkan dalam sistem.
                                        @endif
                                    </p>
                                    @if(!(request('search') || request('grade_level') || request('academic_year_id') || request('education_level')))
                                        <x-button variant="primary" href="{{ route('admin.classes.create') }}" class="!py-2 !text-xs">
                                            Tambah Kelas
                                        </x-button>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile List Card View (block lg:hidden) -->
            <div class="block lg:hidden divide-y divide-slate-100 p-3.5 sm:p-4 space-y-3">
                @forelse($classes as $class)
                    <div class="bg-white border rounded-xl p-4 transition-all duration-150 space-y-3.5 shadow-2xs {{ optional($class->academicYear)->is_active ? 'border-blue-200/80 bg-blue-50/20' : 'border-slate-200/80' }}">
                        <!-- Class Name & View Eye Action -->
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <h2 class="text-lg font-bold text-slate-900 leading-snug break-words whitespace-normal">
                                    {{ $class->name }}
                                </h2>
                                <p class="text-xs font-semibold text-slate-500 mt-0.5">
                                    {{ $class->education_level }} &middot; Tingkat {{ $class->grade_level }}
                                </p>
                            </div>
                            
                            <a href="{{ route('admin.classes.show', $class) }}" 
                               class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors shrink-0" 
                               title="Lihat Detail" 
                               aria-label="Lihat detail kelas {{ $class->name }}">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>
                        </div>

                        <!-- Class Metadata -->
                        <div class="grid grid-cols-2 gap-3 pt-1 text-xs">
                            <div class="space-y-0.5 min-w-0">
                                <span class="text-[11px] font-medium text-slate-400 block uppercase tracking-wider">Wali Kelas</span>
                                <p class="font-semibold text-slate-800 leading-snug break-words whitespace-normal">
                                    {{ $class->homeroomTeacher->user->name ?? 'Belum ditentukan' }}
                                </p>
                            </div>
                            <div class="space-y-0.5 min-w-0">
                                <span class="text-[11px] font-medium text-slate-400 block uppercase tracking-wider">Tahun Ajaran</span>
                                <p class="font-semibold text-slate-800 leading-snug">
                                    {{ $class->academicYear->year ?? 'Belum ditentukan' }}
                                </p>
                            </div>
                        </div>

                        <!-- Student Count & Actions Bar -->
                        <div class="flex items-center justify-between pt-2.5 border-t border-slate-100 text-xs gap-2">
                            <span class="inline-flex items-center gap-1.5 font-bold text-slate-700">
                                <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6 0 3.375 3.375 0 016 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                                {{ $class->students_count }} siswa
                            </span>

                            <div class="flex items-center gap-1.5 shrink-0">
                                <a href="{{ route('admin.classes.show', $class) }}" 
                                   class="px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors inline-flex items-center justify-center min-h-[36px]">
                                    Lihat
                                </a>
                                <a href="{{ route('admin.classes.edit', $class) }}" 
                                   class="px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors inline-flex items-center justify-center min-h-[36px]">
                                    Edit
                                </a>
                                <form id="delete-class-form-mobile-{{ $class->id }}" action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                        @click="confirmDelete('{{ addslashes($class->name) }}', 'delete-class-form-mobile-{{ $class->id }}')" 
                                        class="px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors inline-flex items-center justify-center min-h-[36px]">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white border border-slate-100 rounded-xl p-6 text-center">
                        <div class="mx-auto w-12 h-12 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-3">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
                        </div>
                        <h3 class="text-xs font-bold text-slate-900">
                            @if(request('search') || request('grade_level') || request('academic_year_id') || request('education_level'))
                                Belum ada kelas yang sesuai.
                            @else
                                Belum ada data kelas
                            @endif
                        </h3>
                        <p class="text-xs text-slate-500 mt-1 mb-3">
                            @if(request('search') || request('grade_level') || request('academic_year_id') || request('education_level'))
                                Coba ubah kata kunci pencarian atau filter Anda.
                            @else
                                Belum terdapat data kelas yang didaftarkan dalam sistem.
                            @endif
                        </p>
                        @if(!(request('search') || request('grade_level') || request('academic_year_id') || request('education_level')))
                            <x-button variant="primary" href="{{ route('admin.classes.create') }}" class="!py-2 !text-xs w-full justify-center">
                                Tambah Kelas
                            </x-button>
                        @endif
                    </div>
                @endforelse
            </div>
            
            @if($classes->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $classes->links() }}
                </div>
            @endif
        </x-card>

        <!-- Custom Delete Confirmation Modal (SinergiEdu Design System) -->
        <div 
            x-show="deleteModalOpen" 
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto" 
            role="dialog" 
            aria-modal="true"
            aria-labelledby="modal-title-delete-class"
            aria-describedby="modal-desc-delete-class"
            x-on:keydown.escape.window="deleteModalOpen = false"
        >
            <!-- Overlay -->
            <div 
                x-show="deleteModalOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"
                @click="deleteModalOpen = false"
                aria-hidden="true"
            ></div>

            <!-- Dialog Box -->
            <div 
                x-show="deleteModalOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative z-10 transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full md:w-[420px] md:max-w-[420px] md:flex-none max-h-[calc(100dvh-32px)] overflow-y-auto p-5 md:p-5 border border-slate-200 mx-auto my-auto shrink-0 box-border"
                @click.away="deleteModalOpen = false"
            >
                <!-- Trash Icon Container -->
                <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-red-50 text-red-600 mb-3 shrink-0">
                    <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </div>

                <!-- Content -->
                <div class="text-center space-y-1.5 w-full max-w-[360px] mx-auto min-w-0">
                    <h3 class="text-base sm:text-lg font-semibold text-slate-900 tracking-tight break-words px-1" id="modal-title-delete-class">
                        Hapus Kelas Ini?
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed break-words" id="modal-desc-delete-class">
                        Apakah Anda yakin ingin menghapus kelas &ldquo;<span x-text="classNameToDelete" class="font-semibold text-slate-900 break-all"></span>&rdquo;?
                    </p>
                </div>

                <!-- Warning Box -->
                <div class="mt-4 p-3 bg-amber-50/80 border border-amber-200/80 rounded-xl flex items-start gap-2.5 text-left w-full min-w-0 box-border">
                    <svg class="h-4 w-4 text-amber-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <div class="text-xs leading-relaxed text-amber-800 min-w-0 flex-1 break-words">
                        <span class="font-semibold block text-amber-900 mb-0.5">Kelas masih digunakan</span>
                        Kelas yang masih memiliki siswa tidak dapat dihapus oleh sistem.
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 md:mt-5 flex items-center justify-end gap-2 flex-wrap sm:flex-nowrap w-full">
                    <button 
                        type="button" 
                        @click="deleteModalOpen = false"
                        class="flex-1 sm:flex-initial min-w-[90px] px-4 h-10 bg-white hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold rounded-xl border border-slate-200 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300"
                    >
                        Batal
                    </button>
                    <button 
                        type="button" 
                        @click="submitDelete()"
                        class="flex-1 sm:flex-initial min-w-[90px] px-4 h-10 bg-red-600 hover:bg-red-700 text-white text-xs sm:text-sm font-semibold rounded-xl transition-colors shadow-2xs focus:outline-none focus:ring-2 focus:ring-red-500/30"
                    >
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
