<x-layouts.app>
    <x-slot:title>Rencana Aksi</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Rencana Aksi" description="Kelola rencana aksi tindak lanjut untuk guru, waka, dan pengawas.">
            <x-slot:actions>
                <x-button variant="primary" href="{{ route('kepala-sekolah.rencana-aksi.create') }}">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Buat Rencana Aksi
                </x-button>
            </x-slot:actions>
        </x-page-header>

        <!-- Statistik Kanban -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-slate-100 border border-slate-200 rounded-2xl p-4">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Draft</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $draf->count() }}</p>
            </div>
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                <p class="text-xs font-bold uppercase tracking-wider text-blue-500">Dikerjakan</p>
                <p class="text-2xl font-bold text-blue-700 mt-1">{{ $inProgress->count() }}</p>
            </div>
            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4">
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-500">Selesai</p>
                <p class="text-2xl font-bold text-emerald-700 mt-1">{{ $completed->count() }}</p>
            </div>
            <div class="bg-red-50 border border-red-100 rounded-2xl p-4">
                <p class="text-xs font-bold uppercase tracking-wider text-red-500">Dibatalkan</p>
                <p class="text-2xl font-bold text-red-700 mt-1">{{ $cancelled->count() }}</p>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($actionPlans as $actionPlan)
                <a href="{{ route('kepala-sekolah.rencana-aksi.show', $actionPlan) }}" class="block p-5 bg-white border border-slate-200 rounded-xl hover:border-primary hover:shadow-sm transition group">
                    <div class="flex items-center justify-between gap-4 mb-2">
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $actionPlan->priority_color }}">{{ ucfirst($actionPlan->priority) }}</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700">{{ $actionPlan->category_label }}</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $actionPlan->status_color }}">{{ $actionPlan->status_label }}</span>
                        </div>
                        <span class="text-xs text-slate-400">{{ $actionPlan->created_at->diffForHumans() }}</span>
                    </div>
                    <h3 class="font-bold text-slate-900 group-hover:text-primary transition">{{ $actionPlan->title }}</h3>
                    <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $actionPlan->description }}</p>
                    <p class="text-xs text-slate-400 mt-3">
                        Target: <span class="font-semibold text-slate-600">{{ $actionPlan->target?->name ?? 'Umum (' . $actionPlan->target_role_label . ')' }}</span>
                        @if($actionPlan->due_date)
                            • Tenggat: <span class="font-semibold text-slate-600">{{ $actionPlan->due_date->format('d M Y') }}</span>
                        @endif
                    </p>
                </a>
            @empty
                <x-card>
                    <p class="text-sm text-slate-500">Belum ada rencana aksi. Klik "Buat Rencana Aksi" untuk membuat yang baru.</p>
                </x-card>
            @endforelse
        </div>
    </div>
</x-layouts.app>