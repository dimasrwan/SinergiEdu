<x-layouts.app>
    <x-slot:title>Detail Feedback</x-slot:title>

    <div class="max-w-3xl space-y-6 mx-auto">
        <div>
            <a href="{{ route('kepala-sekolah.feedback.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-primary gap-1.5 transition-colors mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Kembali ke Daftar Feedback
            </a>
            <div class="flex items-center gap-3 flex-wrap mb-4">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $feedback->priority_color }}">{{ $feedback->priority_label }}</span>
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700">{{ $feedback->category_label }}</span>
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $feedback->status_color }}">{{ $feedback->status_label }}</span>
            </div>
            <x-page-header :title="$feedback->title" :description="'Dikirim oleh ' . ($feedback->sender->name ?? 'Anda') . ' • ' . $feedback->created_at->format('d M Y H:i')" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <x-card>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-3">Isi Feedback</h2>
                    <div class="prose prose-sm max-w-none text-slate-700 whitespace-pre-line">{{ $feedback->message }}</div>
                </x-card>

                @if($feedback->action_plan)
                    <x-card>
                        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-3">Rencana Tindak Lanjut</h2>
                        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $feedback->action_plan }}</p>
                        @if($feedback->action_deadline)
                            <p class="text-xs text-slate-500 mt-3">Tenggat: <span class="font-semibold text-slate-700">{{ $feedback->action_deadline->format('d M Y') }}</span></p>
                        @endif
                    </x-card>
                @endif
            </div>

            <div class="space-y-6">
                <x-card>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">Informasi</h2>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Pengirim</dt>
                            <dd class="font-semibold text-slate-900">{{ $feedback->sender?->name ?? 'Anda' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Penerima</dt>
                            <dd class="font-semibold text-slate-900">{{ $feedback->recipient?->name ?? 'Umum (' . ($feedback->recipient_role ?? '-') . ')' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Dibuat</dt>
                            <dd class="font-semibold text-slate-900">{{ $feedback->created_at->format('d M Y') }}</dd>
                        </div>
                    </dl>
                </x-card>

                <x-card>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-3">Perbarui Status</h2>
                    <form action="{{ route('kepala-sekolah.feedback.update-status', $feedback) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PATCH')
                        <x-select name="status">
                            <option value="sent" {{ $feedback->status === 'sent' ? 'selected' : '' }}>Terkirim</option>
                            <option value="acknowledged" {{ $feedback->status === 'acknowledged' ? 'selected' : '' }}>Dibaca</option>
                            <option value="actioned" {{ $feedback->status === 'actioned' ? 'selected' : '' }}>Ditindaklanjuti</option>
                        </x-select>
                        <x-button variant="primary" type="submit" class="w-full justify-center">Simpan Status</x-button>
                    </form>
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.app>