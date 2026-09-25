<x-layouts.app>
    <x-slot:title>Inspeksi Diarsipkan - Pengawas</x-slot:title>

    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-950">Jadwal Inspeksi yang Diarsipkan</h1>
                <p class="text-sm text-slate-500 mt-1">Lihat kembali jadwal inspeksi yang telah diarsipkan.</p>
            </div>
            <a href="{{ route('pengawas.inspections.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Kembali ke Daftar
            </a>
        </div>

        <x-card padding="none">
            <x-table>
                <x-slot:head>
                    <tr>
                        <th class="px-6 py-4">Judul Inspeksi</th>
                        <th class="px-6 py-4">Sekolah</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </x-slot:head>
                <x-slot:body>
                    @forelse($archived as $inspection)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-950">{{ $inspection->title }}</div>
                                <div class="text-xs text-slate-400 mt-1">{{ $inspection->location ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-600">{{ $inspection->school?->name ?? '-' }}</div>
                                <div class="text-xs text-slate-400">Oleh: {{ $inspection->createdBy?->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <x-badge variant="slate">{{ $inspection->inspection_date?->format('d M Y') ?? '-' }}</x-badge>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusVariant = match($inspection->status) {
                                        'completed' => 'success',
                                        'scheduled' => 'primary',
                                        default => 'slate',
                                    };
                                @endphp
                                <x-badge variant="{{ $statusVariant }}">{{ ucfirst($inspection->status) }}</x-badge>
                            </td>
                            <td class="px-6 py-4 flex items-center justify-end gap-3">
                                <a href="{{ route('pengawas.inspections.show', $inspection) }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition">Lihat</a>
                                <form action="{{ route('pengawas.inspections.unarchive', $inspection) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800 transition" onclick="return confirm('Batalkan arsip jadwal inspeksi ini?')">Buka Arsip</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 mb-4">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800">Belum Ada Jadwal Inspeksi Terarsip</h3>
                                <p class="text-sm text-slate-500 mt-1">Jadwal inspeksi yang diarsipkan akan muncul di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </x-slot:body>
            </x-table>

            <div class="p-6 border-t border-slate-100">
                {{ $archived->links() }}
            </div>
        </x-card>
    </div>
</x-layouts.app>
