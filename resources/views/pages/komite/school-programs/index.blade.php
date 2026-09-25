<x-layouts.app>
    <x-slot:title>Program Sekolah</x-slot:title>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Program Sekolah</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftar program dan rencana kerja sekolah (Read Only).</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold border border-slate-200 self-start sm:self-auto">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
                Mode Lihat Saja
            </span>
        </div>

        @if(count($programs) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                @foreach($programs as $program)
                    <x-card padding="md" class="hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="px-2.5 py-0.5 rounded bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-wider border border-blue-200">
                                {{ $program['category'] ?? 'Program Utama' }}
                            </span>
                            <span class="text-xs font-medium text-slate-400">
                                Status: {{ $program['status'] ?? 'Aktif' }}
                            </span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-1.5">{{ $program['name'] }}</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $program['description'] }}</p>
                    </x-card>
                @endforeach
            </div>
        @else
            <x-card padding="md">
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                    <h3 class="text-base font-bold text-slate-900">Belum Ada Program Sekolah</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Informasi program prioritas dan rencana kerja sekolah akan ditampilkan di sini setelah dikonfigurasi.</p>
                </div>
            </x-card>
        @endif
    </div>
</x-layouts.app>
