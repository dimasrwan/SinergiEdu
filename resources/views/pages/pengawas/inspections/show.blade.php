<x-layouts.app>
    <x-slot:title>Detail Inspeksi - Pengawas</x-slot:title>

    <div class="space-y-6">
        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('pengawas.inspections.index') }}" class="hover:text-slate-800 font-semibold transition flex items-center gap-1">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Jadwal Inspeksi
            </a>
            <span>/</span>
            <span class="text-slate-800 font-semibold">{{ $inspection->title }}</span>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $inspection->title }}</h1>
                <p class="text-sm text-slate-500 mt-1">Detail jadwal inspeksi.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('pengawas.inspections.edit', $inspection) }}" class="px-4 py-2 text-sm bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 transition font-semibold">Edit</a>
                <a href="{{ route('pengawas.inspections.index') }}" class="px-4 py-2 text-sm bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition font-semibold">Kembali</a>
            </div>
        </div>

        <x-card padding="md">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Informasi Inspeksi</h3>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm text-slate-500">Status</dt>
                            <dd class="mt-1">
                                @php
                                    $statusVariant = match($inspection->status) {
                                        'completed' => 'success',
                                        'scheduled' => 'primary',
                                        default => 'slate',
                                    };
                                @endphp
                                <x-badge variant="{{ $statusVariant }}">{{ ucfirst($inspection->status) }}</x-badge>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm text-slate-500">Tanggal Inspeksi</dt>
                            <dd class="mt-1 text-sm font-medium text-slate-900">{{ $inspection->inspection_date?->format('d M Y') ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-slate-500">Lokasi</dt>
                            <dd class="mt-1 text-sm font-medium text-slate-900">{{ $inspection->location ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-slate-500">Sekolah</dt>
                            <dd class="mt-1 text-sm font-medium text-slate-900">{{ $inspection->school?->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-slate-500">Dibuat Oleh</dt>
                            <dd class="mt-1 text-sm font-medium text-slate-900">{{ $inspection->createdBy?->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-slate-500">Tanggal Dibuat</dt>
                            <dd class="mt-1 text-sm font-medium text-slate-900">{{ $inspection->created_at?->format('d M Y, H:i') ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Deskripsi</h3>
                    <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $inspection->content ?? 'Tidak ada deskripsi.' }}</p>
                </div>
            </div>
        </x-card>
    </div>
</x-layouts.app>