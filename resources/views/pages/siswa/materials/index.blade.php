<x-layouts.app>
    <x-slot:title>Materi Pembelajaran</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Materi Pembelajaran" description="Lihat dan pelajari berkas materi (PDF) serta video pembelajaran untuk kelas Anda." />

        @if(!$classroom)
            <div class="bg-amber-50 border border-amber-100 rounded-3xl p-8 text-center text-amber-800">
                <p class="font-medium text-base">Kelas Aktif Belum Ditentukan</p>
                <p class="text-sm mt-2 text-amber-700/80 font-light">Akun Anda belum didaftarkan ke kelas aktif manapun pada Tahun Ajaran berjalan. Hubungi Administrator untuk informasi lebih lanjut.</p>
            </div>
        @else
            <div class="mb-6 pb-4 border-b border-slate-200">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kelas Anda</span>
                <h2 class="text-lg font-bold text-slate-800 mt-0.5">{{ $classroom->name }}</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($materials as $material)
                    <x-card padding="lg" class="flex flex-col h-full group hover:shadow-md transition-shadow duration-200">
                        <div class="flex justify-between items-start mb-4">
                            <x-badge variant="primary">{{ $material->subject->name ?? '-' }}</x-badge>
                        </div>
                        
                        <h3 class="text-lg font-bold text-slate-900 mb-2 leading-tight group-hover:text-accent transition-colors">{{ $material->title }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-2 mb-4 flex-grow">{{ $material->description ?? 'Tidak ada deskripsi' }}</p>
                        
                        <div class="flex items-center gap-4 text-xs font-medium text-slate-500 mb-4 pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-1.5" title="Guru Pengampu">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ $material->teacher->user->name ?? '-' }}
                            </div>
                            <div class="flex items-center gap-1.5" title="Tanggal Upload">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $material->created_at->format('d M Y') }}
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mt-auto">
                            <div class="flex items-center gap-2">
                                @if($material->file_path)
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-danger bg-red-50 px-2 py-1 rounded" title="Terdapat file PDF">
                                        PDF
                                    </span>
                                @endif
                                @if($material->video_path)
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-accent bg-blue-50 px-2 py-1 rounded" title="Terdapat Video">
                                        Video
                                    </span>
                                @endif
                                @if(!$material->file_path && !$material->video_path)
                                    <span class="text-slate-400 text-xs italic">Teks saja</span>
                                @endif
                            </div>
                            
                            <x-button variant="primary" href="{{ route('siswa.materials.show', $material) }}" class="!py-1.5 !px-3 !text-xs">
                                Pelajari
                                <svg class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </x-button>
                        </div>
                    </x-card>
                @empty
                    <div class="col-span-full">
                        <x-card padding="lg" class="text-center py-16">
                            <div class="mx-auto w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mb-4">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900">Belum Ada Materi</h3>
                            <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto">Guru belum mengunggah materi pembelajaran untuk kelas Anda.</p>
                        </x-card>
                    </div>
                @endforelse
            </div>

            @if($materials->hasPages())
                <div class="mt-6">
                    {{ $materials->links() }}
                </div>
            @endif
        @endif
    </div>
</x-layouts.app>
