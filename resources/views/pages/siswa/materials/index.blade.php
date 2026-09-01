<x-layouts.app>
    <x-slot:title>Materi Pembelajaran</x-slot:title>

    <div class="w-full space-y-6">
        
        <!-- Header -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 mb-1">Materi Pembelajaran</h1>
                <p class="text-slate-500 text-sm max-w-xl">Temukan materi yang dibagikan guru untuk kelas Anda.</p>
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
                <p class="text-sm text-slate-500">Anda belum memiliki penempatan kelas pada periode akademik saat ini. Hubungi admin sekolah untuk informasi lebih lanjut.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($materials as $material)
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex flex-col h-full shadow-sm hover:shadow-md hover:border-primary/30 transition group">
                        
                        <div class="flex items-start justify-between mb-3">
                            <span class="inline-flex text-[10px] font-bold text-primary bg-blue-50 px-2.5 py-1 rounded-lg uppercase tracking-wider">
                                {{ $material->subject->name ?? '-' }}
                            </span>
                            <div class="flex items-center gap-1.5">
                                @if($material->file_path)
                                    <span class="inline-flex items-center justify-center h-6 w-6 rounded-md bg-red-50 text-red-600" title="PDF File">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                                    </span>
                                @endif
                                @if($material->video_path)
                                    <span class="inline-flex items-center justify-center h-6 w-6 rounded-md bg-blue-50 text-primary" title="Video">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" /></svg>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <h3 class="text-base font-bold text-slate-900 mb-4 line-clamp-2 leading-snug group-hover:text-primary transition-colors">{{ $material->title }}</h3>
                        
                        <div class="mt-auto space-y-3">
                            <div class="flex items-center gap-3 text-xs text-slate-500">
                                <div class="flex items-center gap-1.5">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                                    <span class="truncate">{{ $material->teacher->user->name ?? '-' }}</span>
                                </div>
                                <span>&bull;</span>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                                    <span>{{ $material->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            
                            <a href="{{ route('siswa.materials.show', $material->id) }}" class="flex items-center justify-center w-full py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl border border-slate-200 transition">
                                Buka Materi
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl py-12 px-6 flex flex-col items-center text-center max-w-2xl mx-auto">
                            <div class="h-16 w-16 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center mb-4">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Materi</h3>
                            <p class="text-sm text-slate-500">Belum ada materi pembelajaran yang tersedia untuk kelas Anda saat ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($materials->hasPages())
                <div class="mt-8">
                    {{ $materials->links() }}
                </div>
            @endif
        @endif
    </div>
</x-layouts.app>
