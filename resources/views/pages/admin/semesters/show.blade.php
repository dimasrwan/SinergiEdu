<x-layouts.app>
    <x-slot:title>Detail Semester - {{ $semester->name }}</x-slot:title>

    <div class="w-full max-w-2xl">
        <!-- Header & Breadcrumb -->
        <div class="mb-6 flex flex-col items-start gap-4">
            <a href="{{ route('admin.semesters.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <div class="w-full flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Detail Semester</h1>
                    <p class="mt-1 text-sm text-slate-500">Informasi rekap status semester dan volume penggunaannya.</p>
                </div>
                <div class="flex gap-2 w-full sm:w-auto shrink-0">
                    <x-button variant="secondary" href="{{ route('admin.semesters.edit', $semester) }}" class="flex-1 sm:flex-none justify-center">Edit Semester</x-button>
                </div>
            </div>
        </div>

        <x-card padding="lg" class="{{ $semester->is_active ? 'border-primary/20 ring-1 ring-primary/20' : '' }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Info Utama -->
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Tahun Ajaran</p>
                        <h2 class="text-xl font-bold text-slate-900">{{ $semester->academicYear->year }}</h2>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Semester</p>
                        <h2 class="text-3xl font-bold {{ $semester->is_active ? 'text-primary' : 'text-slate-900' }}">{{ $semester->name }}</h2>
                    </div>
                </div>
                
                <!-- Status & Metrik -->
                <div class="space-y-5 md:border-l md:border-slate-100 md:pl-8">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Status Sistem</p>
                        @if($semester->is_active)
                            <div class="inline-flex items-center text-sm font-bold text-green-700 bg-green-100 px-3 py-1 rounded-lg border border-green-200">
                                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                AKTIF
                            </div>
                        @else
                            <div class="inline-flex items-center text-sm font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-lg border border-slate-200">
                                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                TIDAK AKTIF
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Rekaman Data Nilai</p>
                        <p class="text-lg font-bold text-slate-900">
                            {{ number_format($studentGradesCount) }} <span class="text-sm font-normal text-slate-500">entri nilai tercatat</span>
                        </p>
                        @if($studentGradesCount == 0)
                            <p class="text-xs text-slate-400 mt-1">Belum ada aktivitas penilaian pada semester ini.</p>
                        @endif
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</x-layouts.app>
