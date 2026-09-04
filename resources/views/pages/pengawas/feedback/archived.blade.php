<x-layouts.app>
    <x-slot:title>Feedback Diarsipkan - Pengawas</x-slot:title>

    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Feedback yang Diarsipkan</h1>
            <p class="text-sm text-slate-500 mt-1">Lihat kembali feedback dan rencana aksi yang telah diarsipkan</p>
        </div>

        <x-card padding="none">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-4 py-4 text-left font-semibold text-slate-700">Nama Siswa</th>
                            <th class="px-4 py-4 text-left font-semibold text-slate-700">Kelas</th>
                            <th class="px-4 py-4 text-center font-semibold text-slate-700">Rata-rata</th>
                            <th class="px-4 py-4 text-left font-semibold text-slate-700">Feedback</th>
                            <th class="px-4 py-4 text-center font-semibold text-slate-700">Tanggal</th>
                            <th class="px-4 py-4 text-center font-semibold text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($archived as $grade)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-4">
                                    <div class="font-medium text-slate-900">{{ $grade->student?->user?->name ?? '-' }}</div>
                                    <div class="text-xs text-slate-500">{{ $grade->student?->nis ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-4 text-slate-600">
                                    {{ $grade->classroom?->name ?? '-' }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg text-sm font-semibold bg-slate-100 text-slate-800">
                                        {{ number_format($grade->average_score ?? 0, 1) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    @if($grade->supervisor_feedback)
                                        <p class="text-sm text-slate-600 truncate max-w-xs">{{ $grade->supervisor_feedback }}</p>
                                        <p class="text-xs text-slate-400 mt-1">Rencana: {{ Str::limit($grade->supervisor_action_plan ?? '-', 50) }}</p>
                                    @else
                                        <p class="text-sm text-slate-400 italic">Belum ada feedback</p>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <x-badge variant="slate">{{ $grade->updated_at->format('d M Y, H:i') }}</x-badge>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('pengawas.feedback.unarchive', $grade->student) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-emerald-600 hover:text-emerald-800 transition" onclick="return confirm('Batalkan arsip feedback ini?')">Buka Arsip</button>
                                        </form>
                                        <a href="{{ route('pengawas.students.show', $grade->student->id) }}" class="px-3 py-1.5 text-sm bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">Lihat</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-lg bg-slate-50 text-slate-300 mb-4">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 7.5l2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800">Belum Ada Feedback Terarsip</h3>
                                    <p class="text-sm text-slate-500 mt-1">Feedback yang diarsipkan akan muncul di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($archived->hasPages())
                <div class="p-6 border-t border-slate-100">
                    {{ $archived->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>