<x-layouts.app>
    <x-slot:title>Rencana Aksi</x-slot:title>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <x-page-header title="Rencana Aksi"
                description="Kelola dan terbitkan rencana aksi peningkatan mutu sekolah dari hasil supervisi." />
            <x-button href="{{ route('pengawas.action-plans.create') }}" variant="primary">
                Buat Rencana Aksi
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

        <x-card padding="none">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/50">
                            <th class="px-4 py-4.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Rencana Aksi</th>
                            <th
                                class="px-4 py-4.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500 hidden md:table-cell">
                                Kelas</th>
                            <th
                                class="px-4 py-4.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500 hidden sm:table-cell">
                                Prioritas</th>
                            <th class="px-4 py-4.5 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Status</th>
                            <th
                                class="px-4 py-4.5 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($plans as $plan)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-900">{{ $plan->title }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5 max-w-xs truncate">{{ $plan->content }}</div>
                                    <div class="text-xs text-slate-400 mt-1">{{ $plan->created_at->format('d M Y') }}</div>
                                </td>
                                <td class="px-5 py-4 hidden md:table-cell">
                                    @if($plan->classroom)
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">{{ $plan->classroom->name }}</span>
                                    @else
                                        <span class="text-slate-400 text-xs">Seluruh Sekolah</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 hidden sm:table-cell">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg-lg text-xs font-semibold {{ $plan->priority_badge_class }}">
                                        {{ $plan->priority_label }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg-lg text-xs font-semibold {{ $plan->status_badge_class }}">
                                        {{ $plan->status_label }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('pengawas.action-plans.edit', $plan) }}"
                                            class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition">Edit</a>
                                        <form action="{{ route('pengawas.action-plans.destroy', $plan) }}" method="POST"
                                            onsubmit="return confirm('Hapus rencana aksi ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-xs font-semibold text-red-500 hover:text-red-700 transition">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-16 text-center">
                                    <div
                                        class="inline-flex items-center justify-center w-16 h-16 rounded-lg bg-slate-50 text-slate-300 mb-4 ring-8 ring-slate-50/50">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-700">Belum ada rencana aksi</h3>
                                    <p class="text-sm text-slate-400 mt-1">Mulai buat rencana aksi peningkatan mutu sekolah.
                                    </p>
                                    <a href="{{ route('pengawas.action-plans.create') }}"
                                        class="mt-4 inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">Buat
                                        Sekarang</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($plans->hasPages())
                <div class="p-5 border-t border-slate-100">
                    {{ $plans->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>