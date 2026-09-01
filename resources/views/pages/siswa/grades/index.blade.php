<x-layouts.app>
    <x-slot:title>Nilai Akademik</x-slot:title>

    <div class="w-full space-y-6">
        
        <!-- Header -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 mb-1">Nilai Akademik</h1>
                <p class="text-slate-500 text-sm max-w-xl">Ringkasan nilai Anda pada periode akademik saat ini.</p>
            </div>
            
            <form action="{{ route('siswa.grades.index') }}" method="GET" class="shrink-0 flex items-center gap-3">
                <div class="bg-slate-50 border border-slate-200 rounded-xl flex items-center p-1 relative shadow-sm overflow-hidden">
                    <select name="academic_year_id" onchange="this.form.submit()" class="bg-transparent border-none text-sm font-semibold text-slate-700 focus:ring-0 pl-3 pr-8 py-1.5 cursor-pointer outline-none appearance-none z-10 w-32">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $selectedAcademicYearId == $year->id ? 'selected' : '' }}>
                                TA {{ $year->year }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
                
                <div class="bg-slate-50 border border-slate-200 rounded-xl flex items-center p-1 relative shadow-sm overflow-hidden">
                    <select name="semester_id" onchange="this.form.submit()" class="bg-transparent border-none text-sm font-semibold text-slate-700 focus:ring-0 pl-3 pr-8 py-1.5 cursor-pointer outline-none appearance-none z-10 w-32">
                        @foreach($semesters as $sem)
                            <option value="{{ $sem->id }}" {{ $selectedSemesterId == $sem->id ? 'selected' : '' }}>
                                {{ $sem->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
            </form>
        </div>

        @if($grades->isEmpty())
            <div class="bg-slate-50 border border-slate-200 rounded-2xl py-12 px-6 flex flex-col items-center text-center max-w-2xl mx-auto">
                <div class="h-16 w-16 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Nilai</h3>
                <p class="text-sm text-slate-500">Guru belum mengunggah rekapitulasi nilai untuk Anda pada periode akademik yang dipilih.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($grades as $grade)
                    @php
                        $avg = $grade->average_score;
                    @endphp
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex flex-col h-full shadow-sm hover:shadow-md hover:border-primary/30 transition group">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <span class="inline-flex text-[10px] font-bold text-primary bg-blue-50 px-2.5 py-1 rounded-lg uppercase tracking-wider mb-2">
                                    Mata Pelajaran
                                </span>
                                <h3 class="text-lg font-bold text-slate-900 leading-tight group-hover:text-primary transition-colors">{{ $grade->subject->name ?? '-' }}</h3>
                            </div>
                            <div class="flex items-center justify-center h-14 w-14 shrink-0 rounded-2xl {{ $avg >= 80 ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : ($avg >= 60 ? 'bg-amber-50 text-amber-600 border border-amber-100' : ($avg > 0 ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-slate-50 text-slate-500 border border-slate-200')) }}">
                                <span class="text-xl font-bold">{{ $avg > 0 ? $avg : '-' }}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2 text-sm text-slate-500 mb-5 pb-5 border-b border-slate-100">
                            <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <span class="truncate">Guru: <span class="font-semibold text-slate-700">{{ $grade->teacher->user->name ?? '-' }}</span></span>
                        </div>
                        
                        <div class="mt-auto">
                            <a href="{{ route('siswa.grades.show', $grade) }}" class="flex items-center justify-center w-full py-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 hover:text-primary rounded-xl text-sm font-semibold transition">
                                Lihat Rincian Nilai
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
