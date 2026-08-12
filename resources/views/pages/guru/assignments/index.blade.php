<x-layouts.app>
    <x-slot:title>Tugas & Penilaian</x-slot:title>

    <div class="space-y-6">
        <div class="flex items-center justify-between mb-2">
            <x-page-header title="Tugas & Penilaian" description="Berikan tugas, tetapkan batas waktu (deadline), dan evaluasi jawaban siswa kelas Anda." />
            <div class="mt-4 sm:mt-0">
                <x-button variant="primary" href="{{ route('guru.assignments.create') }}">
                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Buat Tugas Baru
                </x-button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm text-emerald-800 flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <x-card padding="none">
        <x-table>
            <x-slot:head>
                <tr>
                    <th class="px-6 py-4">Tugas</th>
                    <th class="px-6 py-4">Kelas & Mapel</th>
                    <th class="px-6 py-4">Tenggat Waktu</th>
                    <th class="px-6 py-4 text-center">Dikumpulkan</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </x-slot:head>
            <x-slot:body>
                @forelse($assignments as $assignment)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-950 mb-1">{{ $assignment->title }}</div>
                            <div class="text-xs text-slate-500 max-w-xs truncate mx-auto" title="{{ $assignment->description }}">{{ $assignment->description }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-800">{{ $assignment->classroom->name ?? '-' }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">{{ $assignment->subject->name ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <x-badge variant="{{ now()->isAfter($assignment->deadline) ? 'danger' : 'success' }}">
                                {{ $assignment->deadline->format('d M Y, H:i') }}
                            </x-badge>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-50 text-blue-700 font-bold text-xs border border-blue-200" title="{{ $assignment->submissions_count }} Siswa Mengumpulkan">
                                {{ $assignment->submissions_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('guru.assignments.show', $assignment) }}" class="inline-flex items-center justify-center p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Lihat Jawaban">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </a>
                                <a href="{{ route('guru.assignments.edit', $assignment) }}" class="inline-flex items-center justify-center p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition" title="Edit Tugas">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                    </svg>
                                </a>
                                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'confirm-deletion-{{ $assignment->id }}')" class="inline-flex items-center justify-center p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus Tugas">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                                
                                <x-modal name="confirm-deletion-{{ $assignment->id }}" maxWidth="sm">
                                    <div class="p-6 text-center">
                                        <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <h2 class="text-lg font-bold text-slate-900 mb-2">Hapus Tugas Ini?</h2>
                                        <p class="text-sm text-slate-500 mb-6">Anda yakin ingin menghapus tugas <strong>"{{ $assignment->title }}"</strong>? Seluruh berkas jawaban siswa juga akan terhapus dan tindakan ini tidak dapat dibatalkan.</p>
                                        <div class="flex justify-center gap-3">
                                            <x-button type="button" variant="secondary" x-on:click="$dispatch('close')">Batal</x-button>
                                            <form action="{{ route('guru.assignments.destroy', $assignment) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <x-button type="submit" variant="danger">Ya, Hapus</x-button>
                                            </form>
                                        </div>
                                    </div>
                                </x-modal>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-10 w-10 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 11.69a2.62 2.62 0 115.17-1.01 2.62 2.62 0 01-5.17 1.01zm-1.78 4.22h6.86c.64 0 1.17-.45 1.28-1.07l.38-2.28c.11-.64-.32-1.22-.97-1.22H9.86c-.65 0-1.08.58-.97 1.22l.38 2.28c.11.62.64 1.07 1.28 1.07z" />
                                </svg>
                                <p>Belum ada tugas yang diberikan.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-table>

            @if($assignments->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $assignments->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
