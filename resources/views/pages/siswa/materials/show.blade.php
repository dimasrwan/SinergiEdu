<x-layouts.app>
    <x-slot:title>{{ $material->title }}</x-slot:title>

    <div class="max-w-4xl space-y-6 mx-auto">
        <div class="mb-2">
            <a href="{{ route('siswa.materials.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar Materi
            </a>
        </div>

        <x-card padding="lg" class="space-y-6">
            <div class="border-b border-slate-100 pb-6">
                <div class="flex items-center gap-3 mb-3">
                    <x-badge variant="primary">{{ $material->subject->name ?? '-' }}</x-badge>
                    <span class="text-xs text-slate-400 flex items-center gap-1">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Diunggah {{ $material->created_at->diffForHumans() }}
                    </span>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-950 mb-3 leading-tight">{{ $material->title }}</h1>
                <div class="flex items-center gap-2">
                    <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="text-sm">
                        <p class="text-slate-500 font-medium">Pengajar</p>
                        <p class="font-semibold text-slate-800">{{ $material->teacher->user->name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            @if($material->description)
                <div>
                    <h3 class="font-bold text-slate-900 mb-3 text-lg">Petunjuk Pembelajaran</h3>
                    <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-5 text-sm md:text-base text-slate-700 leading-relaxed shadow-sm">
                        {!! nl2br(e($material->description)) !!}
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6 pt-4">
                <!-- PDF Viewer & Download -->
                @if($material->file_path)
                    <div>
                        <h3 class="font-bold text-slate-900 mb-3 text-lg">Berkas Pendukung</h3>
                        <div class="border rounded-2xl p-4 md:p-5 flex flex-col sm:flex-row items-center justify-between gap-4 bg-red-50/30 border-red-100 hover:border-red-200 transition-colors shadow-sm">
                            <div class="flex items-center gap-4 text-center sm:text-left">
                                <div class="p-3 bg-white text-danger rounded-xl shadow-sm border border-red-100">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-base font-semibold text-slate-900">Materi Lengkap (PDF)</h4>
                                    <p class="text-sm text-slate-500 mt-0.5">Dokumen panduan materi.</p>
                                </div>
                            </div>
                            <x-button variant="danger" href="{{ asset('storage/' . $material->file_path) }}" target="_blank" download class="w-full sm:w-auto shadow-sm">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Unduh PDF
                            </x-button>
                        </div>
                    </div>
                @endif

                <!-- Video Player -->
                @if($material->video_path)
                    <div>
                        <h3 class="font-bold text-slate-900 mb-3 text-lg">Video Pembelajaran</h3>
                        <div class="aspect-video w-full rounded-2xl overflow-hidden bg-slate-900 shadow-md ring-1 ring-slate-900/10">
                            <video class="w-full h-full object-contain" controls preload="metadata">
                                <source src="{{ asset('storage/' . $material->video_path) }}" type="video/mp4">
                                Browser Anda tidak mendukung pemutar video HTML5. Silakan unduh berkas videonya.
                            </video>
                        </div>
                    </div>
                @endif
            </div>
        </x-card>
    </div>
</x-layouts.app>
