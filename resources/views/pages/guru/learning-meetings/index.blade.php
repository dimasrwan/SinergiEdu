<x-layouts.app>
    <x-slot:title>Pertemuan Pembelajaran</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Pertemuan Pembelajaran" description="Catat rencana dan pelaksanaan pembelajaran per pertemuan agar materi serta nilai dapat ditelusuri.">
            <x-slot:actions><x-button variant="primary" href="{{ route('guru.learning-meetings.create') }}">Tambah Pertemuan</x-button></x-slot:actions>
        </x-page-header>

        @if(session('success'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        <div class="space-y-3 lg:hidden">
            @forelse($meetings as $meeting)
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-blue-50 text-primary border border-blue-100">
                                Pertemuan {{ $meeting->meeting_number }}
                            </span>
                            <span class="text-xs text-slate-400 block mt-1 font-medium">{{ $meeting->meeting_date->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-600 font-semibold" title="Jumlah Materi">
                                📖 {{ $meeting->materials_count }}
                            </span>
                            <span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-600 font-semibold" title="Jumlah Penilaian">
                                📊 {{ $meeting->assessments_count }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-bold text-slate-900 text-sm break-words">{{ $meeting->topic }}</h3>
                        <p class="text-xs text-slate-500 mt-1">{{ $meeting->classroom->name }} · <span class="font-medium text-slate-700">{{ $meeting->subject->name }}</span></p>
                    </div>

                    @if($meeting->tools_materials)
                        <div class="text-xs text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100 break-words">
                            <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-wider mb-0.5">Alat / Bahan:</span>
                            {{ $meeting->tools_materials }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center">
                    <p class="text-sm text-slate-500">Belum ada pertemuan pembelajaran. Buat pertemuan pertama sebelum menginput penilaian.</p>
                </div>
            @endforelse

            @if($meetings->hasPages())
                <div class="pt-2">
                    {{ $meetings->links() }}
                </div>
            @endif
        </div>

        <x-card padding="none" class="hidden lg:block">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Pertemuan</th>
                            <th class="px-6 py-4">Kelas / Mapel</th>
                            <th class="px-6 py-4">Topik</th>
                            <th class="px-6 py-4">Alat / Bahan</th>
                            <th class="px-6 py-4 text-center">Materi</th>
                            <th class="px-6 py-4 text-center">Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($meetings as $meeting)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-6 py-4"><p class="font-bold text-slate-900">Pertemuan {{ $meeting->meeting_number }}</p><p class="text-xs text-slate-400">{{ $meeting->meeting_date->format('d M Y') }}</p></td>
                                <td class="px-6 py-4 text-slate-600"><p>{{ $meeting->classroom->name }}</p><p class="text-xs text-slate-400">{{ $meeting->subject->name }}</p></td>
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $meeting->topic }}</td>
                                <td class="max-w-xs px-6 py-4 text-slate-600">{{ $meeting->tools_materials ?: '-' }}</td>
                                <td class="px-6 py-4 text-center font-bold text-blue-700">{{ $meeting->materials_count }}</td>
                                <td class="px-6 py-4 text-center font-bold text-blue-700">{{ $meeting->assessments_count }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-14 text-center text-slate-500">Belum ada pertemuan pembelajaran. Buat pertemuan pertama sebelum menginput penilaian.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($meetings->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $meetings->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
