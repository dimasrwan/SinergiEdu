<x-layouts.app>
    <x-slot:title>Detail Tahun Ajaran - {{ $academicYear->year }}</x-slot:title>

    <div class="w-full max-w-4xl">
        <!-- Header & Breadcrumb -->
        <div class="mb-6 flex flex-col items-start gap-4">
            <a href="{{ route('admin.academic-years.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <div class="w-full flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Detail Tahun Ajaran</h1>
                    <p class="mt-1 text-sm text-slate-500">Informasi rekap status tahun ajaran dan daftar kelas terkait.</p>
                </div>
                <div class="flex gap-2 w-full sm:w-auto shrink-0">
                    <x-button variant="secondary" href="{{ route('admin.academic-years.edit', $academicYear) }}" class="flex-1 sm:flex-none justify-center">Edit Tahun Ajaran</x-button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Informasi Rekap (Sidebar) -->
            <div class="md:col-span-1 space-y-6">
                <x-card padding="lg" class="{{ $academicYear->is_active ? 'border-primary/20 ring-1 ring-primary/20' : '' }}">
                    <div class="mb-4">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Periode</p>
                        <h2 class="text-3xl font-bold {{ $academicYear->is_active ? 'text-primary' : 'text-slate-900' }}">{{ $academicYear->year }}</h2>
                    </div>
                    
                    <div class="space-y-4 pt-4 border-t border-slate-100">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Status Sistem</p>
                            @if($academicYear->is_active)
                                <div class="inline-flex items-center text-sm font-bold text-green-700 bg-green-100 px-3 py-1 rounded-md border border-green-200">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                    AKTIF
                                </div>
                            @else
                                <div class="inline-flex items-center text-sm font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-md border border-slate-200">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                    TIDAK AKTIF
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Kelas</p>
                            <p class="text-lg font-bold text-slate-900">{{ $academicYear->classes->count() }} <span class="text-sm font-normal text-slate-500">rombongan belajar</span></p>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Daftar Kelas (Main Content) -->
            <div class="md:col-span-2 space-y-6">
                <x-card padding="none" class="overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <svg class="h-5 w-5 text-accent" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                            Daftar Kelas ({{ $academicYear->year }})
                        </h3>
                    </div>

                    @if($academicYear->classes->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-white border-b border-slate-100">
                                        <th class="py-3 px-5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">NAMA KELAS</th>
                                        <th class="py-3 px-5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">TINGKAT</th>
                                        <th class="py-3 px-5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">WALI KELAS</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($academicYear->classes->sortBy('name') as $class)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="py-3 px-5">
                                                <p class="text-sm font-bold text-slate-900">{{ $class->name }}</p>
                                            </td>
                                            <td class="py-3 px-5">
                                                <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-primary font-bold text-xs shadow-sm">
                                                    {{ $class->grade_level }}
                                                </div>
                                            </td>
                                            <td class="py-3 px-5">
                                                <span class="text-sm font-medium text-slate-700">
                                                    {{ $class->homeroomTeacher->user->name ?? '-' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <div class="mx-auto w-12 h-12 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center text-slate-300 mb-3 shadow-sm">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
                            </div>
                            <p class="text-sm font-bold text-slate-900">Belum ada kelas</p>
                            <p class="text-xs text-slate-500 mt-1">Belum terdapat pendaftaran kelas pada tahun ajaran ini.</p>
                        </div>
                    @endif
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.app>
