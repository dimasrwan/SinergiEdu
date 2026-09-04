<x-layouts.app>
    <x-slot:title>Detail Feedback</x-slot:title>

    <div class="space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('orangtua.feedbacks.index', ['student_id' => $feedback->student_id]) }}" class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
            </a>
            <x-page-header title="Detail Feedback" description="Umpan balik yang diberikan oleh guru kepada anak Anda." />
        </div>

        <x-card padding="lg" class="border border-slate-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 pb-8 border-b border-slate-100">
                <div>
                    <div class="text-sm font-medium text-slate-500 mb-1">Nama Anak</div>
                    <div class="font-bold text-slate-900">{{ $feedback->student->user->name ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-sm font-medium text-slate-500 mb-1">Tanggal Diberikan</div>
                    <div class="font-bold text-slate-900">{{ $feedback->created_at->format('d F Y, H:i') }}</div>
                </div>
                <div>
                    <div class="text-sm font-medium text-slate-500 mb-1">Mata Pelajaran</div>
                    <div class="font-bold text-slate-900">{{ $feedback->subject->name ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-sm font-medium text-slate-500 mb-1">Guru Pengampu</div>
                    <div class="font-bold text-slate-900">{{ $feedback->teacher->user->name ?? '-' }}</div>
                </div>
            </div>

            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900">{{ $feedback->title }}</h2>
                <x-badge :variant="$feedback->type === 'positive' ? 'success' : ($feedback->type === 'negative' ? 'danger' : 'slate')">
                    {{ $feedback->type_label }}
                </x-badge>
            </div>

            <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 text-slate-800 leading-relaxed whitespace-pre-wrap">
                {{ $feedback->message }}
            </div>
        </x-card>
    </div>
</x-layouts.app>
