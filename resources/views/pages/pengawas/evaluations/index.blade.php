<x-layouts.app>
    <x-slot:title>Kelola Evaluasi Sekolah</x-slot:title>

    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <x-page-header title="Evaluasi Sekolah" description="Kelola dan terbitkan catatan evaluasi, supervisi akademis, dan masukan sekolah." />
            <x-button href="{{ route('pengawas.evaluations.create') }}" variant="primary">
                Tulis Evaluasi Baru
            </x-button>
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
            {{-- Mobile Cards --}}
            <div class="block lg:hidden divide-y divide-slate-100">
                @forelse($evaluations as $eval)
                    <div class="p-4 space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h3 class="font-bold text-slate-950 text-base truncate">{{ $eval->title }}</h3>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $eval->content }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-100">
                            <span>Dibuat: <strong class="text-slate-700">{{ $eval->created_at->format('d M Y, H:i') }}</strong></span>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                            <a href="{{ route('pengawas.evaluations.edit', $eval) }}" 
                               class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg transition min-h-[44px]">
                                Edit
                            </a>
                            <form action="{{ route('pengawas.evaluations.destroy', $eval) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus evaluasi ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold bg-red-50 text-red-700 hover:bg-red-100 rounded-lg transition min-h-[44px]">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-sm">Belum Ada Evaluasi</div>
                @endforelse
            </div>

            {{-- Desktop Table --}}
            <div class="hidden lg:block">
                <x-table>
                    <x-slot:head>
                        <tr>
                            <th class="px-6 py-4">Evaluasi</th>
                            <th class="px-6 py-4">Tanggal Dibuat</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </x-slot:head>
                    <x-slot:body>
                        @forelse($evaluations as $eval)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-950">{{ $eval->title }}</div>
                                    <div class="text-xs text-slate-500 mt-1 max-w-lg truncate">{{ $eval->content }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <x-badge variant="slate">{{ $eval->created_at->format('d M Y, H:i') }}</x-badge>
                                </td>
                                <td class="px-6 py-4 flex items-center justify-end gap-3">
                                    <a href="{{ route('pengawas.evaluations.edit', $eval) }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition">Edit</a>
                                    <form action="{{ route('pengawas.evaluations.destroy', $eval) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus evaluasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-800 transition">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-lg bg-slate-50 text-slate-300 mb-4 ring-8 ring-slate-50/50">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800">Belum Ada Evaluasi</h3>
                                    <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Belum ada evaluasi yang dibuat untuk saat ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </x-slot:body>
                </x-table>
            </div>

            <div class="p-4 sm:p-6 border-t border-slate-100">
                {{ $evaluations->links() }}
            </div>
        </x-card>
    </div>
</x-layouts.app>