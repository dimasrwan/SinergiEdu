<x-layouts.app>
    <x-slot:title>Manajemen Mata Pelajaran</x-slot:title>

    <div class="w-full space-y-5 sm:space-y-6" x-data="{
        deleteModalOpen: false,
        subjectNameToDelete: '',
        deleteFormId: '',
        confirmDelete(name, formId) {
            this.subjectNameToDelete = name;
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
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Manajemen Mata Pelajaran</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200/80">
                        {{ $subjects->total() }} mata pelajaran
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed">
                    Kelola master data mata pelajaran yang digunakan dalam proses pembelajaran.
                </p>
            </div>
            <div class="w-full sm:w-auto shrink-0 pt-1 sm:pt-0">
                <x-button variant="primary" href="{{ route('admin.subjects.create') }}" class="w-full sm:w-auto justify-center min-h-[44px]">
                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Mata Pelajaran
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
                    <h3 class="text-xs sm:text-sm font-bold text-red-800">Gagal Menghapus</h3>
                    <p class="text-xs sm:text-sm text-red-700 mt-0.5 leading-snug">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <x-card padding="none">
            <!-- Filter Bar -->
            <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/50">
                <form action="{{ route('admin.subjects.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full">
                    <div class="flex-1 w-full min-w-0">
                        <div class="flex items-center w-full min-h-[44px] px-4 py-2.5 border border-slate-300 rounded-xl bg-white focus-within:ring-2 focus-within:ring-accent focus-within:border-accent transition-shadow">
                            <svg class="h-4 w-4 text-slate-400 shrink-0 mr-3 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode mata pelajaran..." 
                                class="flex-1 w-full min-w-0 bg-transparent border-0 p-0 focus:outline-none focus:ring-0 text-slate-800 placeholder:text-slate-400 text-xs sm:text-sm font-medium">
                        </div>
                    </div>
                    
                    <div class="flex gap-2 w-full sm:w-auto shrink-0">
                        <button type="submit" class="flex-1 sm:flex-initial px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-bold rounded-xl border border-slate-200/80 transition-colors text-center min-h-[44px]">
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.subjects.index') }}" class="flex-1 sm:flex-initial px-5 py-2.5 bg-white hover:bg-red-50 text-slate-500 hover:text-danger text-xs sm:text-sm font-bold rounded-xl border border-slate-200/80 transition-colors text-center min-h-[44px] inline-flex items-center justify-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Desktop View Table (hidden lg:block) -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead>
                        <tr class="bg-white border-b border-slate-100">
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider w-10">NO</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider w-32">KODE</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider">NAMA MATA PELAJARAN</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($subjects as $index => $subject)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="py-4 px-6 text-sm font-medium text-slate-400">
                                    {{ $subjects->firstItem() + $index }}
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex text-[11px] font-bold text-slate-600 bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-lg tracking-wide">
                                        {{ $subject->code }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="text-sm font-bold text-slate-900 break-words whitespace-normal">{{ $subject->name }}</p>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.subjects.show', $subject) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail" aria-label="Lihat detail mata pelajaran">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </a>
                                        <a href="{{ route('admin.subjects.edit', $subject) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit" aria-label="Edit mata pelajaran">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                        </a>
                                        <form id="delete-subject-form-desktop-{{ $subject->id }}" action="{{ route('admin.subjects.destroy', $subject) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                @click="confirmDelete('{{ addslashes($subject->name) }}', 'delete-subject-form-desktop-{{ $subject->id }}')" 
                                                class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                                title="Hapus" 
                                                aria-label="Hapus mata pelajaran {{ $subject->name }}">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center">
                                    <div class="mx-auto w-16 h-16 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-4">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-900">
                                        @if(request('search'))
                                            Mata pelajaran tidak ditemukan.
                                        @else
                                            Belum ada mata pelajaran
                                        @endif
                                    </h3>
                                    <p class="text-sm text-slate-500 mt-1 mb-4">
                                        @if(request('search'))
                                            Coba ubah kata kunci pencarian Anda.
                                        @else
                                            Belum terdapat data mata pelajaran yang terdaftar dalam sistem.
                                        @endif
                                    </p>
                                    @if(!request('search'))
                                        <x-button variant="primary" href="{{ route('admin.subjects.create') }}" class="!py-2 !text-xs">
                                            Tambah Mata Pelajaran
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
                @forelse($subjects as $subject)
                    <div class="bg-white border border-slate-200/80 rounded-xl p-4 shadow-2xs space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <h2 class="text-base font-bold text-slate-900 leading-snug break-words whitespace-normal">
                                    {{ $subject->name }}
                                </h2>
                                <div class="mt-1.5">
                                    <span class="inline-flex text-[11px] font-bold text-slate-600 bg-slate-100 border border-slate-200/80 px-2.5 py-0.5 rounded-md tracking-wide">
                                        Kode: {{ $subject->code }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-1 shrink-0 pt-0.5">
                                <a href="{{ route('admin.subjects.show', $subject) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail" aria-label="Lihat detail mata pelajaran">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </a>
                                <a href="{{ route('admin.subjects.edit', $subject) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit" aria-label="Edit mata pelajaran">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                </a>
                                <form id="delete-subject-form-mobile-{{ $subject->id }}" action="{{ route('admin.subjects.destroy', $subject) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                        @click="confirmDelete('{{ addslashes($subject->name) }}', 'delete-subject-form-mobile-{{ $subject->id }}')" 
                                        class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                        title="Hapus" 
                                        aria-label="Hapus mata pelajaran {{ $subject->name }}">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white border border-slate-100 rounded-xl p-6 text-center">
                        <div class="mx-auto w-12 h-12 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-3">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                        </div>
                        <h3 class="text-xs font-bold text-slate-900">
                            @if(request('search'))
                                Mata pelajaran tidak ditemukan.
                            @else
                                Belum ada mata pelajaran
                            @endif
                        </h3>
                        <p class="text-xs text-slate-500 mt-1 mb-3">
                            @if(request('search'))
                                Coba ubah kata kunci pencarian Anda.
                            @else
                                Belum terdapat data mata pelajaran yang terdaftar dalam sistem.
                            @endif
                        </p>
                        @if(!request('search'))
                            <x-button variant="primary" href="{{ route('admin.subjects.create') }}" class="!py-2 !text-xs w-full justify-center">
                                Tambah Mata Pelajaran
                            </x-button>
                        @endif
                    </div>
                @endforelse
            </div>
            
            @if($subjects->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $subjects->links() }}
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
            aria-labelledby="modal-title-delete-subject"
            aria-describedby="modal-desc-delete-subject"
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
                    <h3 class="text-base sm:text-lg font-semibold text-slate-900 tracking-tight break-words px-1" id="modal-title-delete-subject">
                        Hapus Mata Pelajaran?
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed break-words" id="modal-desc-delete-subject">
                        Apakah Anda yakin ingin menghapus mata pelajaran &ldquo;<span x-text="subjectNameToDelete" class="font-semibold text-slate-900 break-all"></span>&rdquo;?
                    </p>
                </div>

                <!-- Warning Box -->
                <div class="mt-4 p-3 bg-amber-50/80 border border-amber-200/80 rounded-xl flex items-start gap-2.5 text-left w-full min-w-0 box-border">
                    <svg class="h-4 w-4 text-amber-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <div class="text-xs leading-relaxed text-amber-800 min-w-0 flex-1 break-words">
                        <span class="font-semibold block text-amber-900 mb-0.5">Penghapusan dapat diblokir</span>
                        Jika mata pelajaran ini telah digunakan oleh modul lain (tugas, materi, penilaian, penugasan guru), sistem akan memblokir penghapusan.
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

