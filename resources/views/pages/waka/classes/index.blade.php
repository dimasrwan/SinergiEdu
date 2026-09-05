<x-layouts.app>
    <x-slot:title>Manajemen Kelas</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Daftar Kelas" description="Kelola data kelas pembelajaran di sekolah.">
            <x-slot:actions>
                <x-button variant="primary" href="{{ route('waka.classes.create') }}">Tambah Kelas</x-button>
            </x-slot:actions>
        </x-page-header>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm text-emerald-800 flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <x-card padding="none">
            <x-table :headers="['Nama Kelas', 'Tingkat Kelas', 'Aksi']">
                @forelse($classes as $classItem)
                    <tr>
                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $classItem->name }}</td>
                        <td class="px-6 py-4">{{ $classItem->grade_level }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('waka.classes.edit', $classItem) }}" 
                                   class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                   title="Edit" 
                                   aria-label="Edit">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                    </svg>
                                </a>
                                
                                <div x-data class="inline-block">
                                    <button type="button" 
                                            x-on:click.prevent="$dispatch('open-modal', 'delete-class-{{ $classItem->id }}')" 
                                            class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                            title="Hapus" 
                                            aria-label="Hapus">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                    
                                    <x-modal name="delete-class-{{ $classItem->id }}" maxWidth="sm">
                                        <div class="p-6">
                                            <h2 class="text-lg font-bold text-slate-900">Konfirmasi Penghapusan</h2>
                                            <p class="mt-2 text-sm text-slate-600">Apakah Anda yakin ingin menghapus kelas ini? Tindakan ini tidak dapat dibatalkan.</p>
                                            <div class="mt-6 flex justify-end gap-3">
                                                <x-button variant="secondary" x-on:click="$dispatch('close-modal', 'delete-class-{{ $classItem->id }}')">Batal</x-button>
                                                <form action="{{ route('waka.classes.destroy', $classItem) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-button variant="danger" type="submit">Hapus</x-button>
                                                </form>
                                            </div>
                                        </div>
                                    </x-modal>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-10 text-center text-slate-400">Belum ada data kelas.</td>
                    </tr>
                @endforelse
            </x-table>

            @if($classes->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $classes->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
