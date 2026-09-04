<x-layouts.app>
    <x-slot:title>Detail Rencana Aksi</x-slot:title>

    <div class="max-w-3xl space-y-6 mx-auto">
        <div>
            <a href="{{ route('kepala-sekolah.rencana-aksi.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-primary gap-1.5 transition-colors mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Kembali ke Daftar Rencana Aksi
            </a>
            <div class="flex items-center gap-3 flex-wrap mb-4">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $actionPlan->priority_color }}">{{ $actionPlan->priority_label }}</span>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">{{ $actionPlan->category_label }}</span>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $actionPlan->status_color }}">{{ $actionPlan->status_label }}</span>
            </div>
            <x-page-header :title="$actionPlan->title" :description="'Dibuat oleh ' . ($actionPlan->creator?->name ?? 'Anda') . ' • Target: ' . ($actionPlan->target?->name ?? $actionPlan->target_role_label)" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <x-card>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-3">Deskripsi</h2>
                    <div class="prose prose-sm max-w-none text-slate-700 whitespace-pre-line">{{ $actionPlan->description ?: 'Tidak ada deskripsi.' }}</div>
                </x-card>

                @if($actionPlan->notes)
                    <x-card>
                        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-3">Catatan</h2>
                        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $actionPlan->notes }}</p>
                    </x-card>
                @endif
            </div>

            <div class="space-y-6">
                <x-card>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">Informasi</h2>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Mulai</dt>
                            <dd class="font-semibold text-slate-900">{{ $actionPlan->start_date?->format('d M Y') ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Tenggat</dt>
                            <dd class="font-semibold text-slate-900">{{ $actionPlan->due_date?->format('d M Y') ?? '-' }}</dd>
                        </div>
                        @if($actionPlan->completed_at)
                            <div class="flex justify-between">
                                <dt class="text-slate-500">Selesai</dt>
                                <dd class="font-semibold text-slate-900">{{ $actionPlan->completed_at->format('d M Y H:i') }}</dd>
                            </div>
                        @endif
                    </dl>
                </x-card>

                <x-card>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-3">Perbarui Status</h2>
                    <form action="{{ route('kepala-sekolah.rencana-aksi.update-status', $actionPlan) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PATCH')
                        <x-select name="status">
                            <option value="draft" {{ $actionPlan->status === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="in_progress" {{ $actionPlan->status === 'in_progress' ? 'selected' : '' }}>Dikerjakan</option>
                            <option value="completed" {{ $actionPlan->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ $actionPlan->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </x-select>
                        <x-button variant="primary" type="submit" class="w-full justify-center">Simpan Status</x-button>
                    </form>
                    <form action="{{ route('kepala-sekolah.rencana-aksi.destroy', $actionPlan) }}" method="POST" class="mt-3" onsubmit="return confirm('Hapus rencana aksi ini?');">
                        @csrf
                        @method('DELETE')
                        <x-button variant="danger" type="submit" class="w-full justify-center">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                            Hapus
                        </x-button>
                    </form>
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.app>