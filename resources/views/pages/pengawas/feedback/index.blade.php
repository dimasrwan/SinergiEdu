<x-layouts.app>
    <x-slot:title>Daftar Feedback - Pengawas</x-slot:title>

    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Daftar Feedback Siswa</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola feedback dan rencana aksi untuk peningkatan hasil belajar siswa</p>
        </div>

        {{-- Statistik Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-card padding="sm">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Siswa</span>
                        <h3 class="text-2xl font-bold tracking-tight text-slate-900 mt-1">{{ $feedbacks->total() }}</h3>
                    </div>
                    <div class="text-blue-600 bg-blue-50/50 p-3 rounded-xl border border-blue-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
                    </div>
                </div>
            </x-card>

            <x-card padding="sm">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Sudah Feedback</span>
                        <h3 class="text-2xl font-bold tracking-tight text-slate-900 mt-1">
                            {{ $feedbacks->filter(fn($f) => $f->studentGrades->where('supervisor_feedback')->isNotEmpty())->count() }}
                        </h3>
                    </div>
                    <div class="text-emerald-600 bg-emerald-50/50 p-3 rounded-xl border border-emerald-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                    </div>
                </div>
            </x-card>

            <x-card padding="sm">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Perlu Follow-up</span>
                        <h3 class="text-2xl font-bold tracking-tight text-slate-900 mt-1">
                            {{ $feedbacks->filter(fn($f) => !$f->studentGrades->where('supervisor_feedback')->isNotEmpty())->count() }}
                        </h3>
                    </div>
                    <div class="text-amber-600 bg-amber-50/50 p-3 rounded-xl border border-amber-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                    </div>
                </div>
            </x-card>
        </div>

        {{-- Daftar Feedback --}}
        <x-card padding="none">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-4 py-4 text-left font-semibold text-slate-700">Nama Siswa</th>
                            <th class="px-4 py-4 text-left font-semibold text-slate-700">Kelas</th>
                            <th class="px-4 py-4 text-center font-semibold text-slate-700">Rata-rata</th>
                            <th class="px-4 py-4 text-left font-semibold text-slate-700">Feedback Terakhir</th>
                            <th class="px-4 py-4 text-center font-semibold text-slate-700">Status</th>
                            <th class="px-4 py-4 text-center font-semibold text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
@php
    $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
    $feedbacks->load('classes');
@endphp
@forelse($feedbacks as $feedback)
    @php
        $hasFeedback = $feedback->studentGrades->where('supervisor_feedback')->isNotEmpty();
        $lastFeedback = $feedback->studentGrades->where('supervisor_feedback')->sortByDesc('updated_at')->first();
        $classroom = $activeYear ? $feedback->classes->first(fn ($c) => $c->pivot?->academic_year_id == $activeYear->id) : null;
    @endphp
    <tr class="hover:bg-slate-50 transition">
        <td class="px-4 py-4">
            <div class="font-medium text-slate-900">{{ $feedback->user?->name }}</div>
            <div class="text-xs text-slate-500">{{ $feedback->nis }}</div>
        </td>
        <td class="px-4 py-4 text-slate-600">
            {{ $classroom?->name ?? '-' }}
        </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg text-sm font-semibold
                                        {{ $feedback->studentGrades->avg('average_score') >= 80 ? 'bg-emerald-100 text-emerald-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ number_format($feedback->studentGrades->avg('average_score') ?? 0, 1) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    @if($hasFeedback)
                                        <p class="text-sm text-slate-600 truncate max-w-xs">{{ $lastFeedback?->supervisor_feedback }}</p>
                                        <p class="text-xs text-slate-400 mt-1">{{ $lastFeedback?->updated_at->diffForHumans() }}</p>
                                    @else
                                        <p class="text-sm text-slate-400 italic">Belum ada feedback</p>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($hasFeedback)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-emerald-100 text-emerald-800">
                                            Sudah Feedback
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-orange-100 text-orange-800">
                                            Perlu Feedback
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('pengawas.students.show', $feedback->id) }}"
                                           class="px-3 py-1.5 text-sm bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
                                            Lihat
                                        </a>
                                        @if($hasFeedback)
                                            <a href="{{ route('pengawas.feedback.edit', $feedback->id) }}"
                                               class="px-3 py-1.5 text-sm bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
                                                Edit
                                            </a>
                                        @else
                                            <a href="{{ route('pengawas.feedback.create', ['student_id' => $feedback->id]) }}"
                                               class="px-3 py-1.5 text-sm bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
                                                Feedback
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        <p class="text-sm">Belum ada data siswa</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($feedbacks->hasPages())
                <div class="p-6 border-t border-slate-200">
                    {{ $feedbacks->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
