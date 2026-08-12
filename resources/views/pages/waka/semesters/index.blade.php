<x-layouts.app>
    <x-slot:title>Semester</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Daftar Semester" description="Kelola data semester akademik yang aktif di sekolah.">
            <x-slot:actions>
                <x-button variant="primary" href="{{ route('waka.semesters.create') }}">Tambah Semester</x-button>
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
            <x-table :headers="['Semester', 'Tahun Ajaran', 'Status', 'Aksi']">
                @forelse($semesters as $semester)
                    <tr>
                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $semester->name }}</td>
                        <td class="px-6 py-4">{{ $semester->academicYear->year ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($semester->is_active)
                                <x-badge variant="success">Aktif</x-badge>
                            @else
                                <x-badge variant="secondary">Nonaktif</x-badge>
                            @endif
                        </td>
                        <td class="px-6 py-4 flex items-center gap-3">
                            @if(!$semester->is_active)
                                <form action="{{ route('waka.semesters.toggle', $semester) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs font-semibold text-accent hover:text-accent-hover transition">Set Aktif</button>
                                </form>
                            @endif
                            <a href="{{ route('waka.semesters.edit', $semester) }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition">Edit</a>
                            
                            <div x-data class="inline-block">
                                <button type="button" x-on:click.prevent="$dispatch('open-modal', 'delete-semester-{{ $semester->id }}')" class="text-xs font-semibold text-danger hover:text-red-800 transition">Hapus</button>
                                
                                <x-modal name="delete-semester-{{ $semester->id }}" maxWidth="sm">
                                    <div class="p-6">
                                        <h2 class="text-lg font-bold text-slate-900">Konfirmasi Penghapusan</h2>
                                        <p class="mt-2 text-sm text-slate-600">Apakah Anda yakin ingin menghapus semester ini? Tindakan ini tidak dapat dibatalkan.</p>
                                        <div class="mt-6 flex justify-end gap-3">
                                            <x-button variant="secondary" x-on:click="$dispatch('close-modal', 'delete-semester-{{ $semester->id }}')">Batal</x-button>
                                            <form action="{{ route('waka.semesters.destroy', $semester) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
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
                        <td colspan="4" class="px-6 py-10 text-center text-slate-400">Belum ada data semester.</td>
                    </tr>
                @endforelse
            </x-table>

            @if($semesters->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $semesters->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
