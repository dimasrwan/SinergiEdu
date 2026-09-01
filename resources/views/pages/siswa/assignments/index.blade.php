<x-layouts.app>
    <x-slot:title>Tugas</x-slot:title>

    <div class="w-full space-y-6">
        
        <!-- Header -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 mb-1">Tugas & Latihan</h1>
                <p class="text-slate-500 text-sm max-w-xl">Daftar tugas yang diberikan oleh guru untuk kelas Anda.</p>
            </div>
            @if($classroom)
                <div class="shrink-0 bg-slate-50 border border-slate-100 px-4 py-2 rounded-xl text-center">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Kelas Aktif</span>
                    <span class="block text-sm font-bold text-primary">{{ $classroom->name }}</span>
                </div>
            @endif
        </div>

        @if(!$classroom)
            <div class="bg-slate-50 border border-slate-200 rounded-2xl py-12 px-6 flex flex-col items-center text-center max-w-2xl mx-auto">
                <div class="h-16 w-16 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Kelas Aktif</h3>
                <p class="text-sm text-slate-500">Anda belum terdaftar di kelas manapun untuk tahun ajaran ini. Silakan hubungi admin sekolah.</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5">
                @forelse($assignments as $assignment)
                    @php
                        $submission = $assignment->submissions->first();
                        $isSubmitted = $submission !== null;
                        $isOverdue = now()->isAfter($assignment->deadline);
                        $hasScore = $isSubmitted && $submission->score !== null;
                        
                        // Status Logic
                        if ($hasScore) {
                            $statusText = 'Dinilai';
                            $statusClass = 'bg-blue-50 text-blue-700 border border-blue-200/50';
                        } elseif ($isSubmitted) {
                            $statusText = 'Sudah Dikumpulkan';
                            $statusClass = 'bg-emerald-50 text-emerald-700 border border-emerald-200/50';
                        } elseif ($isOverdue) {
                            $statusText = 'Terlambat';
                            $statusClass = 'bg-red-50 text-red-700 border border-red-200/50';
                        } else {
                            $statusText = 'Belum Dikerjakan';
                            $statusClass = 'bg-slate-100 text-slate-700 border border-slate-200';
                        }
                    @endphp
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex flex-col h-full shadow-sm hover:shadow-md hover:border-primary/30 transition group">
                        
                        <div class="flex items-start justify-between mb-3 gap-2">
                            <span class="inline-flex text-[10px] font-bold text-primary bg-blue-50 px-2.5 py-1 rounded-lg uppercase tracking-wider shrink-0">
                                {{ $assignment->subject->name ?? '-' }}
                            </span>
                            <span class="inline-flex text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider text-right shrink-0 {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </div>
                        
                        <h3 class="text-base font-bold text-slate-900 mb-1 line-clamp-2 leading-snug group-hover:text-primary transition-colors">{{ $assignment->title }}</h3>
                        <p class="text-xs text-slate-500 mb-2 line-clamp-2">{{ $assignment->description }}</p>
                        
                        <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-4 pb-4 border-b border-slate-100">
                            <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            <span class="truncate">Deadline: <span class="{{ $isOverdue && !$isSubmitted ? 'text-red-600 font-semibold' : 'text-slate-700' }}">{{ $assignment->deadline->format('d M Y, H:i') }}</span></span>
                        </div>
                        
                        <div class="mt-auto space-y-3">
                            @if($hasScore)
                                <div class="flex items-center justify-between px-3 py-2 bg-slate-50 rounded-xl mb-3 border border-slate-100">
                                    <span class="text-xs font-medium text-slate-500">Nilai Akhir:</span>
                                    <span class="text-sm font-bold text-primary">{{ $submission->score }}</span>
                                </div>
                            @endif
                            
                            <a href="{{ route('siswa.assignments.show', $assignment->id) }}" class="flex items-center justify-center w-full py-2.5 {{ $isSubmitted ? 'bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200' : 'bg-primary hover:bg-primary/90 text-white border border-transparent' }} text-sm font-semibold rounded-xl transition shadow-sm">
                                {{ $isSubmitted ? 'Lihat Detail' : 'Kerjakan Tugas' }}
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl py-12 px-6 flex flex-col items-center text-center max-w-2xl mx-auto">
                            <div class="h-16 w-16 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center mb-4">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 019 9v.375M10.125 2.25A3.375 3.375 0 0113.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 013.375 3.375M9 15l2.25 2.25L15 12" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Tugas</h3>
                            <p class="text-sm text-slate-500">Guru Anda belum memberikan tugas untuk kelas ini. Periksa kembali nanti.</p>
                        </div>
                    </div>
                @endforelse
            </div>
            
            @if($assignments->hasPages())
                <div class="mt-8">
                    {{ $assignments->links() }}
                </div>
            @endif
        @endif
    </div>
</x-layouts.app>
