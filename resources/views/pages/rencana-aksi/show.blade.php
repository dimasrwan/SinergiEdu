<x-layouts.app>
    <x-slot:title>{{ $actionPlan->title }}</x-slot:title>

    <div class="w-full space-y-6">
        <div>
            <a href="{{ route($prefix.'.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-primary gap-1.5 transition-colors mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Kembali ke Daftar Rencana Aksi
            </a>
            <x-page-header :title="$actionPlan->title" :description="'Dibuat oleh ' . ($actionPlan->creator?->name ?? '-') . ' • Target: ' . ($actionPlan->target?->name ?? $actionPlan->target_role_label ?? 'Umum')" />
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $actionPlan->priority_color }}">{{ ucfirst($actionPlan->priority) }}</span>
            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700">{{ $actionPlan->category_label }}</span>
            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $actionPlan->status_color }}">{{ $actionPlan->status_label }}</span>
        </div>

        <x-card padding="lg">
            <div class="space-y-6">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Deskripsi</p>
                    <p class="text-sm text-slate-700 whitespace-pre-line">{{ $actionPlan->description ?: '-' }}</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Target</p>
                        <p class="text-sm font-semibold text-slate-700">{{ $actionPlan->target?->name ?? 'Umum (' . ($actionPlan->target_role_label ?? '-') . ')' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Role Target</p>
                        <p class="text-sm font-semibold text-slate-700">{{ $actionPlan->target_role_label ?? 'Umum' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Tanggal Mulai</p>
                        <p class="text-sm text-slate-700">{{ $actionPlan->start_date?->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Tenggat</p>
                        <p class="text-sm text-slate-700">{{ $actionPlan->due_date?->format('d M Y') ?? '-' }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Catatan</p>
                    <p class="text-sm text-slate-700 whitespace-pre-line">{{ $actionPlan->notes ?: '-' }}</p>
                </div>
            </div>
        </x-card>
    </div>
</x-layouts.app>
