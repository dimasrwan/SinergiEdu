<x-layouts.app>
    <x-slot:title>Tugas & Penilaian</x-slot:title>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-2 gap-4">
            <x-page-header title="Tugas & Penilaian" description="Berikan tugas, tetapkan batas waktu (deadline), dan evaluasi jawaban siswa kelas Anda." />
            <div class="mt-4 sm:mt-0 whitespace-nowrap">
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
        @if(session('error'))
            <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-sm text-red-800 flex items-center gap-3">
                <svg class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white p-4 rounded-2xl border border-slate-200 flex flex-col md:flex-row gap-4 items-center justify-between">
            <form action="{{ route('guru.assignments.index') }}" method="GET" class="w-full md:w-1/3 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul tugas..." class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent sm:text-sm transition">
            </form>
            @if(request('search'))
                <a href="{{ route('guru.assignments.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">Clear Search</a>
            @endif
        </div>

        <x-card padding="none">
        <x-table :headers="['Tugas', 'Kelas & Mapel', 'Tenggat Waktu', 'Dikumpulkan', 'Aksi']">
                @forelse($assignments as $assignment)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-950 mb-1">{{ $assignment->title }}</div>
                            <div class="text-xs text-slate-500 max-w-xs truncate mx-auto" title="{{ $assignment->description }}">{{ Str::limit($assignment->description, 50) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-800">{{ $assignment->classroom->name ?? '-' }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">{{ $assignment->subject->name ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if(now()->isAfter($assignment->deadline))
                                <x-badge variant="danger" class="whitespace-nowrap">
                                    {{ $assignment->deadline->format('d M Y, H:i') }} (Terlewat)
                                </x-badge>
                            @else
                                <x-badge variant="success" class="whitespace-nowrap">
                                    {{ $assignment->deadline->format('d M Y, H:i') }} (Aktif)
                                </x-badge>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-col items-center gap-1">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-50 text-blue-700 font-bold text-xs border border-blue-200" title="{{ $assignment->submissions_count }} Siswa Mengumpulkan">
                                    {{ $assignment->submissions_count }}
                                </span>
                                <span class="text-[10px] text-slate-500 font-medium">Submissions</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('guru.assignments.show', $assignment) }}" class="inline-flex items-center justify-center p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Lihat Detail & Jawaban">
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
                                @if($assignment->submissions_count == 0)
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
                                            <p class="text-sm text-slate-500 mb-6">Anda yakin ingin menghapus tugas <strong>"{{ $assignment->title }}"</strong>? Tindakan ini tidak dapat dibatalkan.</p>
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
                                @else
                                    <button type="button" class="inline-flex items-center justify-center p-2 text-slate-300 cursor-not-allowed rounded-lg transition" title="Tugas tidak dapat dihapus karena sudah ada submission" disabled>
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center py-6">
                                <div class="w-16 h-16 bg-blue-50 text-accent rounded-full flex items-center justify-center mb-4">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 11.69a2.62 2.62 0 115.17-1.01 2.62 2.62 0 01-5.17 1.01zm-1.78 4.22h6.86c.64 0 1.17-.45 1.28-1.07l.38-2.28c.11-.64-.32-1.22-.97-1.22H9.86c-.65 0-1.08.58-.97 1.22l.38 2.28c.11.62.64 1.07 1.28 1.07z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900">Belum Ada Tugas</h3>
                                <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto mb-6">Belum ada tugas pembelajaran yang Anda buat.</p>
                                <x-button variant="primary" href="{{ route('guru.assignments.create') }}">
                                    Buat Tugas Baru
                                </x-button>
                            </div>
                        </td>
                    </tr>
                @endforelse
        </x-table>

            @if($assignments->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $assignments->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
