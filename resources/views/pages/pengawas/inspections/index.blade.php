<x-layouts.app>
    <x-slot:title>Jadwal Inspeksi - Pengawas</x-slot:title>

    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <x-page-header title="Jadwal Inspeksi" description="Kelola jadwal inspeksi di masing-masing sekolah." />
            <x-button href="{{ route('pengawas.inspections.create') }}" variant="primary">
                Jadwalkan Inspeksi
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
            <div class="p-4 border-b border-slate-200 flex items-center gap-4">
                <form method="GET" class="flex gap-3">
                    <select name="status" class="px-4 py-2 border border-slate-300 rounded-lg-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Dijadwalkan</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    <button type="submit" class="px-4 py-2 text-sm bg-slate-100 text-slate-700 rounded-lg-lg hover:bg-slate-200 transition">Filter</button>
                </form>
            </div>
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
                    @forelse($inspections as $inspection)
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
                                <a href="{{ route('pengawas.inspections.edit', $inspection) }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition">Edit</a>
                                <form action="{{ route('pengawas.inspections.destroy', $inspection) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal inspeksi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-800 transition">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-lg bg-slate-50 text-slate-300 mb-4">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800">Belum Ada Jadwal Inspeksi</h3>
                                <p class="text-sm text-slate-500 mt-1">Mulai dengan menjadwalkan inspeksi baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </x-slot:body>
            </x-table>

            <div class="p-6 border-t border-slate-100">
                {{ $inspections->links() }}
            </div>
        </x-card>
    </div>
</x-layouts.app>