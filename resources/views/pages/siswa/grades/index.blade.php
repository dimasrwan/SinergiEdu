<x-layouts.app>
    <x-slot:title>Nilai Akademik</x-slot:title>

    <div class="w-full space-y-6">
        
        <!-- Header -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 flex flex-col gap-6 md:gap-8 shadow-sm">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900 mb-2">Nilai Akademik</h1>
                <p class="text-slate-500 text-sm max-w-xl">Ringkasan nilai Anda pada periode akademik saat ini.</p>
            </div>
            
            <form x-ref="filterForm" action="{{ route('siswa.grades.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                
                <!-- Custom Dropdown Tahun Ajaran -->
                <div x-data="{
                    open: false,
                    selectedId: '{{ $selectedAcademicYearId }}',
                    selectedLabel: '{{ $academicYears->firstWhere('id', $selectedAcademicYearId)?->year ? 'TA ' . $academicYears->firstWhere('id', $selectedAcademicYearId)->year : 'Pilih Tahun' }}',
                    select(id, label) {
                        this.selectedId = id;
                        this.selectedLabel = label;
                        this.open = false;
                        $refs.yearInput.value = id;
                        $refs.filterForm.submit();
                    }
                }" class="relative w-full sm:w-[240px]" @keydown.escape.prevent.stop="open = false" @click.away="open = false">
                    
                    <input type="hidden" name="academic_year_id" x-ref="yearInput" :value="selectedId">
                    
                    <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Tahun Ajaran</span>
                    
                    <button type="button" 
                            @click="open = !open" 
                            @keydown.space.prevent="open = !open"
                            @keydown.enter.prevent="open = !open"
                            @keydown.arrow-down.prevent="open = true"
                            aria-haspopup="listbox" 
                            :aria-expanded="open"
                            class="w-full flex items-center justify-between bg-white border border-slate-200 hover:border-primary/40 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary/60 rounded-lg px-4 py-2.5 text-[14px] font-semibold text-slate-700 shadow-sm transition-all"
                    >
                        <span x-text="selectedLabel" class="truncate"></span>
                        <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul x-show="open" 
                        x-transition:enter="transition ease-out duration-100" 
                        x-transition:enter-start="transform opacity-0 scale-95" 
                        x-transition:enter-end="transform opacity-100 scale-100" 
                        x-transition:leave="transition ease-in duration-75" 
                        x-transition:leave-start="transform opacity-100 scale-100" 
                        x-transition:leave-end="transform opacity-0 scale-95" 
                        class="absolute z-50 mt-2 w-full bg-white border border-slate-200 rounded-xl shadow-lg max-h-60 overflow-auto py-1 focus:outline-none" 
                        role="listbox" 
                        style="display: none;"
                    >
                        @foreach($academicYears as $year)
                            <li @click="select('{{ $year->id }}', 'TA {{ $year->year }}')" 
                                class="cursor-pointer select-none px-4 py-2.5 text-[14px] transition-colors flex items-center justify-between"
                                :class="selectedId == '{{ $year->id }}' ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-700 hover:bg-slate-50 font-medium'"
                                role="option"
                                :aria-selected="selectedId == '{{ $year->id }}'">
                                TA {{ $year->year }}
                                <svg x-show="selectedId == '{{ $year->id }}'" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </li>
                        @endforeach
                    </ul>
                </div>
                
                <!-- Custom Dropdown Semester -->
                <div x-data="{
                    open: false,
                    selectedId: '{{ $selectedSemesterId }}',
                    selectedLabel: '{{ $semesters->firstWhere('id', $selectedSemesterId)?->name ?? 'Pilih Semester' }}',
                    select(id, label) {
                        this.selectedId = id;
                        this.selectedLabel = label;
                        this.open = false;
                        $refs.semesterInput.value = id;
                        $refs.filterForm.submit();
                    }
                }" class="relative w-full sm:w-[240px]" @keydown.escape.prevent.stop="open = false" @click.away="open = false">
                    
                    <input type="hidden" name="semester_id" x-ref="semesterInput" :value="selectedId">
                    
                    <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Semester</span>
                    
                    <button type="button" 
                            @click="open = !open" 
                            @keydown.space.prevent="open = !open"
                            @keydown.enter.prevent="open = !open"
                            @keydown.arrow-down.prevent="open = true"
                            aria-haspopup="listbox" 
                            :aria-expanded="open"
                            class="w-full flex items-center justify-between bg-white border border-slate-200 hover:border-primary/40 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary/60 rounded-lg px-4 py-2.5 text-[14px] font-semibold text-slate-700 shadow-sm transition-all"
                    >
                        <span x-text="selectedLabel" class="truncate"></span>
                        <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul x-show="open" 
                        x-transition:enter="transition ease-out duration-100" 
                        x-transition:enter-start="transform opacity-0 scale-95" 
                        x-transition:enter-end="transform opacity-100 scale-100" 
                        x-transition:leave="transition ease-in duration-75" 
                        x-transition:leave-start="transform opacity-100 scale-100" 
                        x-transition:leave-end="transform opacity-0 scale-95" 
                        class="absolute z-50 mt-2 w-full bg-white border border-slate-200 rounded-xl shadow-lg max-h-60 overflow-auto py-1 focus:outline-none" 
                        role="listbox" 
                        style="display: none;"
                    >
                        @foreach($semesters as $sem)
                            <li @click="select('{{ $sem->id }}', '{{ $sem->name }}')" 
                                class="cursor-pointer select-none px-4 py-2.5 text-[14px] transition-colors flex items-center justify-between"
                                :class="selectedId == '{{ $sem->id }}' ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-700 hover:bg-slate-50 font-medium'"
                                role="option"
                                :aria-selected="selectedId == '{{ $sem->id }}'">
                                {{ $sem->name }}
                                <svg x-show="selectedId == '{{ $sem->id }}'" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </form>
        </div>

        @if($grades->isEmpty())
            <div class="bg-slate-50 border border-slate-200 rounded-2xl py-12 px-6 flex flex-col items-center text-center max-w-2xl mx-auto">
                <div class="h-16 w-16 bg-blue-50 text-blue-400 rounded-full flex items-center justify-center mb-4">
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
                    <div class="bg-white border border-slate-200/75 rounded-2xl p-5 flex flex-col h-full shadow-sm hover:shadow-md hover:border-primary/40 transition group">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <span class="inline-flex text-[11px] font-bold text-primary uppercase tracking-wider mb-2">
                                    Mata Pelajaran
                                </span>
                                <h3 class="text-[17px] font-bold text-slate-900 leading-tight group-hover:text-primary transition-colors">{{ $grade->subject->name ?? '-' }}</h3>
                            </div>
                            <div class="flex items-center justify-center h-14 w-14 shrink-0 rounded-2xl {{ $avg >= 80 ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : ($avg >= 60 ? 'bg-amber-50 text-amber-600 border border-amber-100' : ($avg > 0 ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-slate-50 text-slate-500 border border-slate-200')) }}">
                                <span class="text-[19px] font-bold">{{ $avg > 0 ? $avg : '-' }}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2 text-[13px] text-slate-500 font-medium mb-5 pb-5 border-b border-slate-100">
                            <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <span class="truncate">Guru: <span class="font-semibold text-slate-700">{{ $grade->teacher->user->name ?? '-' }}</span></span>
                        </div>
                        
                        <div class="mt-auto">
                            <a href="{{ route('siswa.grades.show', $grade) }}" class="flex items-center justify-center w-full py-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 hover:text-primary rounded-xl text-[13px] font-bold transition">
                                Lihat Rincian &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
