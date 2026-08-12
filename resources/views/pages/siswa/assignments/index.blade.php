<x-layouts.app>
    <x-slot:title>Tugas & Ujian</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Tugas & Ujian" description="Daftar tugas yang harus Anda kerjakan di kelas {{ $classroom->name ?? 'Belum terdaftar di kelas manapun' }}." />

        @if(!$classroom)
            <div class="p-6 bg-amber-50 border border-amber-200 rounded-2xl flex items-start gap-4">
                <svg class="h-6 w-6 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <h3 class="text-sm font-bold text-amber-900">Belum Ada Kelas Aktif</h3>
                    <p class="text-sm text-amber-700 mt-1">Anda belum terdaftar di kelas manapun untuk tahun ajaran ini. Silakan hubungi admin sekolah.</p>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($assignments as $assignment)
                    @php
                        $isSubmitted = $assignment->submissions->isNotEmpty();
                        $isOverdue = now()->isAfter($assignment->deadline);
                    @endphp
                    <x-card padding="lg" class="{{ $isSubmitted ? 'border-emerald-200 shadow-emerald-100/50' : ($isOverdue ? 'border-red-200 shadow-red-100/50' : 'border-slate-200/60 shadow-sm hover:shadow-md transition-shadow') }} flex flex-col h-full relative overflow-hidden group">
                        @if($isSubmitted)
                            <div class="absolute top-0 right-0 w-16 h-16 pointer-events-none">
                                <div class="absolute top-2 -right-6 bg-emerald-500 text-white text-[10px] font-bold py-0.5 px-8 rotate-45">SELESAI</div>
                            </div>
                        @elseif($isOverdue)
                            <div class="absolute top-0 right-0 w-16 h-16 pointer-events-none">
                                <div class="absolute top-2 -right-6 bg-red-500 text-white text-[10px] font-bold py-0.5 px-8 rotate-45">TERLAMBAT</div>
                            </div>
                        @endif

                        <div class="flex items-start justify-between mb-4">
                            <x-badge variant="primary">{{ $assignment->subject->name ?? 'Umum' }}</x-badge>
                        </div>
                        
                        <h3 class="text-lg font-bold text-slate-900 leading-tight mb-2 group-hover:text-blue-600 transition-colors">{{ $assignment->title }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-2 flex-grow">{{ $assignment->description }}</p>

                        <div class="mt-4 pt-4 border-t border-slate-100 space-y-3">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-500 font-medium">Tenggat Waktu:</span>
                                <span class="font-semibold {{ $isOverdue && !$isSubmitted ? 'text-red-600' : 'text-slate-800' }}">
                                    {{ $assignment->deadline->format('d M Y, H:i') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-500 font-medium">Guru:</span>
                                <span class="font-medium text-slate-700 truncate max-w-[150px]">
                                    {{ $assignment->teacher->user->name ?? '-' }}
                                </span>
                            </div>
                            <div class="pt-2">
                                <x-button href="{{ route('siswa.assignments.show', $assignment) }}" variant="{{ $isSubmitted ? 'success' : 'primary' }}" class="w-full justify-center">
                                    {{ $isSubmitted ? 'Lihat Jawaban Saya' : 'Kerjakan Tugas' }}
                                </x-button>
                            </div>
                        </div>
                    </x-card>
                @empty
                    <div class="col-span-full py-16 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 019 9v.375M10.125 2.25A3.375 3.375 0 0113.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 013.375 3.375M9 15l2.25 2.25L15 12" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Belum Ada Tugas</h3>
                        <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Guru Anda belum memberikan tugas untuk kelas ini. Silakan periksa kembali nanti.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-6">
                {{ $assignments->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
