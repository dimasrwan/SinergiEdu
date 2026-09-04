<x-layouts.app>
    <x-slot:title>Manajemen Orang Tua</x-slot:title>

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between border-b border-slate-200 pb-5 gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Daftar Orang Tua</h1>
                <p class="mt-2 text-sm text-slate-500 max-w-2xl">
                    Kelola data orang tua / wali murid dan pantau data anak yang terhubung.
                    @if($totalParents > 0)
                        <span class="inline-flex items-center ml-2 px-2.5 py-0.5 rounded-lg bg-slate-100 text-slate-600 text-xs font-semibold border border-slate-200">
                            {{ $totalParents }} orang tua terdaftar
                        </span>
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto shrink-0">
                <x-button variant="primary" href="{{ route('admin.parents.create') }}">
                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Orang Tua
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

        <!-- Search Toolbar -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
            <form action="{{ route('admin.parents.index') }}" method="GET" class="w-full">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau nomor HP..." 
                        class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-lg leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent text-sm transition-shadow">
                </div>
            </form>
        </div>

        <!-- Table Container -->
        <x-card padding="none" class="overflow-visible">
            <div class="overflow-x-auto lg:overflow-visible">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Orang Tua / Wali</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No. HP</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Siswa (Anak)</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($parents as $parent)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 text-sm">{{ $parent->user->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($parent->user && $parent->user->email)
                                        <div class="text-sm text-slate-700">{{ $parent->user->email }}</div>
                                    @else
                                        <span class="text-sm text-slate-400 italic">Belum diisi</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-slate-700 font-mono">
                                    {{ $parent->phone ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($parent->students->count() > 0)
                                        <div class="flex flex-col gap-2">
                                            @php
                                                $displayCount = 2;
                                                $studentsToDisplay = $parent->students->take($displayCount);
                                                $remaining = $parent->students->count() - $displayCount;
                                            @endphp

                                            @foreach($studentsToDisplay as $student)
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-semibold text-slate-800">{{ $student->user->name ?? '-' }}</span>
                                                    <span class="text-[11px] text-slate-500 font-mono">NIS: {{ $student->nis ?? '-' }}</span>
                                                </div>
                                            @endforeach

                                            @if($remaining > 0)
                                                <div class="text-xs font-semibold text-accent mt-1">
                                                    +{{ $remaining }} anak lainnya
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-sm text-slate-400 italic">Belum terhubung</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                                                        <div class="flex items-center justify-end gap-1.5 ">
                                        <a href="{{ route('admin.parents.show', $parent) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail" aria-label="Lihat detail orang tua">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </a>
                                        <a href="{{ route('admin.parents.edit', $parent) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit" aria-label="Edit orang tua">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                        </a>
                                        <button type="button" x-on:click.prevent="$dispatch('open-modal', 'delete-parent-{{ $parent->id }}')" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus" aria-label="Hapus orang tua">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Delete Modal -->
                                    <x-modal name="delete-parent-{{ $parent->id }}" maxWidth="sm">
                                        <div class="p-6 text-left whitespace-normal">
                                            <div class="w-12 h-12 rounded-full bg-red-100 text-danger flex items-center justify-center mb-4">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                            </div>
                                            <h2 class="text-lg font-bold text-slate-900">Hapus orang tua/wali?</h2>
                                            <p class="mt-2 text-sm text-slate-600">Data orang tua/wali <strong>{{ $parent->user->name ?? '' }}</strong> akan dihapus dari sistem.</p>
                                            @if($parent->students->count() > 0)
                                                <div class="mt-3 p-3 bg-amber-50 rounded-lg border border-amber-200">
                                                    <p class="text-xs text-amber-800 font-medium flex gap-2">
                                                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                                        Hubungan dengan {{ $parent->students->count() }} siswa akan terputus. Data siswa itu sendiri TIDAK akan dihapus.
                                                    </p>
                                                </div>
                                            @endif
                                            <div class="mt-6 flex justify-end gap-3">
                                                <x-button variant="secondary" x-on:click="$dispatch('close-modal', 'delete-parent-{{ $parent->id }}')">Batal</x-button>
                                                <form action="{{ route('admin.parents.destroy', $parent) }}" method="POST" class="inline">
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
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-4">
                                            @if(request('search'))
                                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                                            @else
                                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                                            @endif
                                        </div>
                                        
                                        @if(request('search'))
                                            <h3 class="text-sm font-bold text-slate-900 mb-1">Orang tua/wali tidak ditemukan</h3>
                                            <p class="text-xs text-slate-500">Coba ubah kata kunci pencarian.</p>
                                        @else
                                            <h3 class="text-sm font-bold text-slate-900 mb-1">Belum ada orang tua/wali</h3>
                                            <p class="text-xs text-slate-500 mb-4">Belum terdapat data orang tua atau wali yang terdaftar.</p>
                                            <x-button variant="primary" href="{{ route('admin.parents.create') }}" class="!py-2 !text-xs">
                                                + Tambah Orang Tua
                                            </x-button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($parents->hasPages())
                <div class="p-5 border-t border-slate-100 bg-slate-50/50">
                    {{ $parents->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
