<x-layouts.app>
    <x-slot:title>Rencana Aksi</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Rencana Aksi" :description="$pageDescription">
            <x-slot:actions>
                @if($canCreate)
                    <x-button variant="primary" href="{{ route($prefix.'.create') }}">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Buat Rencana Aksi
                    </x-button>
                @endif
            </x-slot:actions>
        </x-page-header>

        <div class="space-y-4">
            @forelse($actionPlans as $actionPlan)
                <a href="{{ route($prefix.'.show', $actionPlan) }}" class="block p-5 bg-white border border-slate-200 rounded-xl hover:border-primary hover:shadow-sm transition group">
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
                        Dari: <span class="font-semibold text-slate-600">{{ $actionPlan->creator?->name ?? '-' }}</span>
                        • Target: <span class="font-semibold text-slate-600">{{ $actionPlan->target?->name ?? 'Umum (' . ($actionPlan->target_role_label ?? '-') . ')' }}</span>
                        @if($actionPlan->due_date)
                            • Tenggat: <span class="font-semibold text-slate-600">{{ $actionPlan->due_date->format('d M Y') }}</span>
                        @endif
                    </p>
                </a>
            @empty
                <x-card>
                    <p class="text-sm text-slate-500">Belum ada rencana aksi.</p>
                </x-card>
            @endforelse
        </div>
    </div>
</x-layouts.app>
