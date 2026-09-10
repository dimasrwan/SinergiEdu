<x-layouts.app>
    <x-slot:title>Evaluasi Pengawas</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Catatan Evaluasi Pengawas" description="Daftar masukan resmi, catatan evaluasi, dan saran perbaikan sekolah dari Pengawas Sekolah." />

        <div class="space-y-4">
            @forelse($evaluations as $eval)
                <x-card padding="lg" class="border-l-4 border-l-blue-700">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3 gap-2">
                        <span class="text-xs text-slate-500 font-medium flex items-center gap-1.5">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                            </svg>
                            Tanggal Evaluasi: {{ $eval->created_at->format('d M Y, H:i') }}
                        </span>
                        <x-badge variant="primary">Oleh: {{ $eval->user->name }}</x-badge>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">{{ $eval->title }}</h3>
                    <div class="text-sm text-slate-700 mt-3 whitespace-pre-wrap leading-relaxed">{{ $eval->content }}</div>
                </x-card>
            @empty
                <x-card padding="lg" class="text-center py-16 border border-slate-100 bg-white">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-lg bg-slate-50 text-slate-300 mb-4 ring-8 ring-slate-50/50">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Belum Ada Evaluasi</h3>
                    <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Belum ada catatan evaluasi dari Pengawas untuk saat ini.</p>
                </x-card>
            @endforelse

            <div class="mt-6">
                {{ $evaluations->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
