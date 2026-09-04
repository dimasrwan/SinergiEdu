<x-layouts.app>
    <x-slot:title>Pertemuan Pembelajaran</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Pertemuan Pembelajaran" description="Catat rencana dan pelaksanaan pembelajaran per pertemuan agar materi serta nilai dapat ditelusuri.">
            <x-slot:actions><x-button variant="primary" href="{{ route('guru.learning-meetings.create') }}">Tambah Pertemuan</x-button></x-slot:actions>
        </x-page-header>

        @if(session('success'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        <x-card padding="none">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] text-left text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-6 py-4">Pertemuan</th><th class="px-6 py-4">Kelas / Mapel</th><th class="px-6 py-4">Topik</th><th class="px-6 py-4">Alat / Bahan</th><th class="px-6 py-4 text-center">Materi</th><th class="px-6 py-4 text-center">Nilai</th></tr></thead>
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
        </x-card>
        @if($meetings->hasPages())<div>{{ $meetings->links() }}</div>@endif
    </div>
</x-layouts.app>
