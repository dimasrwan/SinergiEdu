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
            <x-card padding="md" class="border-l-4 border-l-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-semibold">Total Siswa</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">{{ $feedbacks->total() }}</p>
                    </div>
                    <svg class="h-12 w-12 text-blue-500/20" fill="currentColor" viewBox="0 0 20 20"><path d="M10.5 1.5H5.75A2.25 2.25 0 003.5 3.75v12.5A2.25 2.25 0 005.75 18.5h8.5a2.25 2.25 0 002.25-2.25V6.5m-11-4v3m6-3v3m-6 3h6M3.5 16.5h13"/></svg>
                </div>
            </x-card>

            <x-card padding="md" class="border-l-4 border-l-emerald-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-semibold">Sudah Feedback</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">
                            {{ $feedbacks->filter(fn($f) => $f->studentGrades->where('supervisor_feedback')->isNotEmpty())->count() }}
                        </p>
                    </div>
                    <svg class="h-12 w-12 text-emerald-500/20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
            </x-card>

            <x-card padding="md" class="border-l-4 border-l-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-semibold">Perlu Follow-up</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">
                            {{ $feedbacks->filter(fn($f) => !$f->studentGrades->where('supervisor_feedback')->isNotEmpty())->count() }}
                        </p>
                    </div>
                    <svg class="h-12 w-12 text-orange-500/20" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                </div>
            </x-card>
        </div>

        {{-- Daftar Feedback --}}
        <x-card padding="none">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-3 text-left font-semibold text-slate-700">Nama Siswa</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-700">Kelas</th>
                            <th class="px-6 py-3 text-center font-semibold text-slate-700">Rata-rata</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-700">Feedback Terakhir</th>
                            <th class="px-6 py-3 text-center font-semibold text-slate-700">Status</th>
                            <th class="px-6 py-3 text-center font-semibold text-slate-700">Aksi</th>
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
        <td class="px-6 py-3">
            <div class="font-medium text-slate-900">{{ $feedback->user?->name }}</div>
            <div class="text-xs text-slate-500">{{ $feedback->nis }}</div>
        </td>
        <td class="px-6 py-3 text-slate-600">
            {{ $classroom?->name ?? '-' }}
        </td>
                                <td class="px-6 py-3 text-center">
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-sm font-semibold
                                        {{ $feedback->studentGrades->avg('average_score') >= 80 ? 'bg-emerald-100 text-emerald-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ number_format($feedback->studentGrades->avg('average_score') ?? 0, 1) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    @if($hasFeedback)
                                        <p class="text-sm text-slate-600 truncate max-w-xs">{{ $lastFeedback?->supervisor_feedback }}</p>
                                        <p class="text-xs text-slate-400 mt-1">{{ $lastFeedback?->updated_at->diffForHumans() }}</p>
                                    @else
                                        <p class="text-sm text-slate-400 italic">Belum ada feedback</p>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-center">
                                    @if($hasFeedback)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                            Sudah Feedback
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            Perlu Feedback
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('pengawas.students.show', $feedback->id) }}"
                                           class="px-3 py-1.5 text-sm bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
                                            Lihat
                                        </a>
                                        @if($hasFeedback)
                                            <a href="{{ route('pengawas.feedback.edit', $feedback->id) }}"
                                               class="px-3 py-1.5 text-sm bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200 transition">
                                                Edit
                                            </a>
                                        @else
                                            <a href="{{ route('pengawas.feedback.create', ['student_id' => $feedback->id]) }}"
                                               class="px-3 py-1.5 text-sm bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200 transition">
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
                                        <svg class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
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
