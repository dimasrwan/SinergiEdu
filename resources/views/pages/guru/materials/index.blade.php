<x-layouts.app>
    <x-slot:title>Kelola Materi Pelajaran</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Materi Pembelajaran" description="Upload dan bagikan materi berbentuk PDF maupun Video pembelajaran ke siswa Anda.">
            <x-slot:actions>
                <x-button variant="primary" href="{{ route('guru.materials.create') }}">
                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Upload Materi
                </x-button>
            </x-slot:actions>
        </x-page-header>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm text-emerald-800 flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($materials as $material)
                <x-card padding="lg" class="flex flex-col relative group">
                    <div class="flex justify-between items-start mb-4">
                        <x-badge variant="primary">{{ $material->subject->name ?? '-' }}</x-badge>
                        
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false" class="p-1 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </button>
                            <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-36 bg-white rounded-xl shadow-lg shadow-slate-200/50 ring-1 ring-slate-200 py-1 z-10">
                                <a href="{{ route('guru.materials.edit', $material) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">Edit Materi</a>
                                <button type="button" x-on:click.prevent="$dispatch('open-modal', 'delete-material-{{ $material->id }}')" class="block w-full text-left px-4 py-2 text-sm text-danger hover:bg-red-50 transition">Hapus</button>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-bold text-slate-900 mb-2 leading-tight">{{ $material->title }}</h3>
                    <p class="text-sm text-slate-500 line-clamp-2 mb-4 flex-grow">{{ $material->description ?? 'Tidak ada deskripsi' }}</p>
                    
                    <div class="flex items-center gap-4 text-xs font-medium text-slate-500 mb-4 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-1.5" title="Kelas">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ $material->classroom->name ?? '-' }}
                        </div>
                        <div class="flex items-center gap-1.5" title="Diupload pada">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $material->created_at->format('d M Y') }}
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        @if($material->file_path)
                            <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-danger hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition" title="Lihat PDF">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                PDF
                            </a>
                        @endif
                        @if($material->video_path)
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-accent bg-blue-50 px-3 py-1.5 rounded-lg" title="Ada Video Pembelajaran">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Video
                            </span>
                        @endif
                        
                        @if(!$material->file_path && !$material->video_path)
                            <span class="text-xs text-slate-400 italic">Hanya teks</span>
                        @endif
                    </div>

                    <x-modal name="delete-material-{{ $material->id }}" maxWidth="sm">
                        <div class="p-6">
                            <h2 class="text-lg font-bold text-slate-900">Konfirmasi Penghapusan</h2>
                            <p class="mt-2 text-sm text-slate-600">Apakah Anda yakin ingin menghapus materi <strong>{{ $material->title }}</strong>? Data file yang tertaut juga mungkin akan terhapus.</p>
                            <div class="mt-6 flex justify-end gap-3">
                                <x-button variant="secondary" x-on:click="$dispatch('close-modal', 'delete-material-{{ $material->id }}')">Batal</x-button>
                                <form action="{{ route('guru.materials.destroy', $material) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <x-button variant="danger" type="submit">Hapus Materi</x-button>
                                </form>
                            </div>
                        </div>
                    </x-modal>
                </x-card>
            @empty
                <div class="col-span-full">
                    <x-card padding="lg" class="text-center py-16">
                        <div class="mx-auto w-16 h-16 bg-blue-50 text-accent rounded-full flex items-center justify-center mb-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Belum Ada Materi</h3>
                        <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto mb-6">Anda belum mengunggah materi pembelajaran apapun. Silakan tambah materi pertama Anda untuk mulai berbagi dengan siswa.</p>
                        <x-button variant="primary" href="{{ route('guru.materials.create') }}">Upload Materi Sekarang</x-button>
                    </x-card>
                </div>
            @endforelse
        </div>

        @if($materials->hasPages())
            <div class="mt-6">
                {{ $materials->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
