<x-layouts.app>
    <x-slot:title>Jadwal Inspeksi</x-slot:title>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <x-page-header title="Jadwal Inspeksi"
                description="Kelola dan pantau kunjungan supervisi/inspeksi kelas untuk para guru." />
            <x-button href="{{ route('pengawas.inspections.create') }}" variant="primary">
                Jadwalkan Inspeksi
            </x-button>
        </div>

        @if(session('success'))
            <div
                class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm text-emerald-800 flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Filter Status --}}
        <div class="bg-white border border-slate-200/60 rounded-2xl p-4 shadow-sm">
            <form method="GET" action="{{ route('pengawas.inspections.index') }}"
                class="flex flex-wrap items-center gap-3">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Filter Status:</span>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('pengawas.inspections.index') }}"
                        class="px-3.5 py-1.5 text-xs font-semibold rounded-lg border transition {{ !request('status') ? 'bg-primary text-white border-primary' : 'bg-slate-50 hover:bg-slate-100 text-slate-600 border-slate-200' }}">
                        Semua
                    </a>
                    @foreach(['scheduled' => 'Terjadwal', 'in_progress' => 'Sedang Berjalan', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $val => $label)
                        <a href="{{ route('pengawas.inspections.index', ['status' => $val]) }}"
                            class="px-3.5 py-1.5 text-xs font-semibold rounded-lg border transition {{ request('status') === $val ? 'bg-primary text-white border-primary' : 'bg-slate-50 hover:bg-slate-100 text-slate-600 border-slate-200' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </form>
        </div>

        {{-- Grid Inspeksi --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($inspections as $ins)
                <x-card padding="md"
                    class="flex flex-col justify-between border border-slate-200/60 hover:shadow-md transition">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $ins->status_badge_class }}">
                                {{ $ins->status_label }}
                            </span>
                            <span class="text-xs font-bold text-slate-400 font-mono">
                                {{ $ins->inspection_date->format('d M Y, H:i') }}
                            </span>
                        </div>

                        <h3 class="text-base font-bold text-slate-900 leading-snug">{{ $ins->title }}</h3>
                        <p class="text-xs text-slate-500 mt-1">Guru: <strong
                                class="text-slate-700">{{ $ins->teacher?->user?->name }}</strong></p>
                        @if($ins->classroom)
                            <p class="text-xs text-slate-500">Kelas: <strong
                                    class="text-slate-700">{{ $ins->classroom->name }}</strong></p>
                        @endif

                        @if($ins->description)
                            <p
                                class="text-sm text-slate-600 mt-3 line-clamp-3 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                {{ $ins->description }}</p>
                        @endif

                        @if($ins->notes)
                            <div class="mt-4 pt-3 border-t border-slate-100">
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Catatan Hasil
                                    Supervisi:</span>
                                <p class="text-xs text-slate-700 mt-1 italic line-clamp-3">"{{ $ins->notes }}"</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-5 pt-3 border-t border-slate-100 shrink-0">
                        <a href="{{ route('pengawas.inspections.edit', $ins) }}"
                            class="text-xs font-semibold text-primary hover:text-blue-800 transition">Edit & Catat Hasil</a>
                        <form action="{{ route('pengawas.inspections.destroy', $ins) }}" method="POST"
                            onsubmit="return confirm('Hapus jadwal inspeksi ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="text-xs font-semibold text-red-500 hover:text-red-700 transition">Hapus</button>
                        </form>
                    </div>
                </x-card>
            @empty
                <div class="md:col-span-2 lg:col-span-3 bg-white border border-slate-200/60 rounded-3xl p-16 text-center">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 mb-4 ring-8 ring-slate-50/50">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-700">Tidak ada jadwal inspeksi</h3>
                    <p class="text-sm text-slate-400 mt-1">Mulai buat jadwal supervisi guru Anda.</p>
                    <a href="{{ route('pengawas.inspections.create') }}"
                        class="mt-4 inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-primary hover:bg-blue-800 rounded-xl transition">Jadwalkan
                        Sekarang</a>
                </div>
            @endforelse
        </div>

        @if($inspections->hasPages())
            <div class="pt-4">
                {{ $inspections->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>