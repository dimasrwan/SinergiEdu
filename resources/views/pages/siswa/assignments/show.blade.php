<x-layouts.app>
    <x-slot:title>Kerjakan Tugas: {{ $assignment->title }}</x-slot:title>

    <div class="space-y-6">
        <div class="mb-4">
            <a href="{{ route('siswa.assignments.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition mb-3">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar Tugas
            </a>
            
            @if(session('success'))
                <div class="mt-4 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm text-emerald-800 flex items-center gap-3">
                    <svg class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mt-4 p-4 bg-red-50 border border-red-100 rounded-2xl text-sm text-red-800 flex items-center gap-3">
                    <svg class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informasi Tugas -->
            <div class="lg:col-span-2 space-y-6">
                <x-card padding="lg">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-6">
                        <h1 class="text-2xl md:text-3xl font-bold text-slate-950">{{ $assignment->title }}</h1>
                        <x-badge variant="primary" class="self-start">{{ $assignment->subject->name ?? 'Umum' }}</x-badge>
                    </div>

                    <div class="flex flex-wrap gap-4 text-sm text-slate-600 mb-6 pb-6 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-slate-50 rounded-lg text-slate-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <div>
                                <span class="block text-xs text-slate-500 font-medium">Guru Pengampu</span>
                                <span class="font-semibold text-slate-800">{{ $assignment->teacher->user->name ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 ml-4">
                            <div class="p-2 bg-slate-50 rounded-lg text-slate-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <span class="block text-xs text-slate-500 font-medium">Tenggat Waktu</span>
                                <span class="font-semibold {{ now()->isAfter($assignment->deadline) ? 'text-red-600' : 'text-slate-800' }}">{{ $assignment->deadline->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-sm font-bold text-slate-900 mb-3 uppercase tracking-wider">Instruksi Tugas</h3>
                    <div class="text-sm md:text-base text-slate-700 whitespace-pre-wrap leading-relaxed">{{ $assignment->description }}</div>
                    
                    @if($assignment->attachment_path)
                        <div class="mt-8 pt-6 border-t border-slate-100">
                            <h4 class="text-xs font-semibold text-slate-500 mb-3 uppercase tracking-wide">Lampiran Pendukung</h4>
                            <div class="border rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4 bg-blue-50/30 border-blue-100 hover:border-blue-200 transition-colors shadow-sm">
                                <div class="flex items-center gap-4 text-center sm:text-left">
                                    <div class="p-3 bg-white text-blue-600 rounded-xl shadow-sm border border-blue-100">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-slate-900">Materi Tugas (Berkas)</h4>
                                        <p class="text-xs text-slate-500 mt-0.5">Unduh untuk membaca rincian soal selengkapnya.</p>
                                    </div>
                                </div>
                                <x-button variant="primary" href="{{ route('siswa.assignments.download', $assignment) }}" target="_blank" class="w-full sm:w-auto shadow-sm">
                                    Unduh File
                                </x-button>
                            </div>
                        </div>
                    @endif
                </x-card>
            </div>

            <!-- Area Pengumpulan -->
            <div class="lg:col-span-1">
                <x-card padding="lg" class="sticky top-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4 border-b border-slate-100 pb-3">Pengumpulan Jawaban</h3>

                    @if($submission)
                        <!-- Jika Sudah Kumpul -->
                        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl mb-5">
                            <div class="flex items-center gap-2 text-emerald-800 font-bold mb-1">
                                <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                                Tugas Diserahkan
                            </div>
                            <p class="text-xs text-emerald-700">Dikumpulkan pada {{ $submission->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <span class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">File Jawaban Anda:</span>
                                <a href="{{ route('siswa.assignments.submissions.download', $assignment) }}" target="_blank" class="flex items-center gap-3 p-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl transition text-slate-700 hover:text-blue-600">
                                    <div class="p-2 bg-white rounded-lg shadow-sm border border-slate-100 text-blue-500">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold">Lihat Berkas</span>
                                </a>
                            </div>
                            @if($submission->notes)
                                <div>
                                    <span class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Catatan Tambahan:</span>
                                    <div class="text-sm text-slate-700 bg-amber-50/50 border border-amber-100 p-4 rounded-xl relative">
                                        <svg class="absolute top-3 right-3 h-5 w-5 text-amber-200" fill="currentColor" viewBox="0 0 24 24"><path d="M9 21c0 .5.4 1 1 1h4c.6 0 1-.5 1-1v-1H9v1zm3-19C8.1 2 5 5.1 5 9c0 2.4 1.2 4.5 3 5.7V17c0 .6.4 1 1 1h6c.6 0 1-.4 1-1v-2.3c1.8-1.3 3-3.4 3-5.7 0-3.9-3.1-7-7-7z"/></svg>
                                        {{ $submission->notes }}
                                    </div>
                                </div>
                            @endif

                            <div class="mt-6 pt-5 border-t border-slate-100">
                                <h4 class="text-sm font-bold text-slate-900 mb-4">Penilaian Guru</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50 flex flex-col justify-center">
                                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Nilai</span>
                                        @if($submission->score !== null)
                                            <span class="text-2xl font-bold text-slate-900">{{ $submission->score }}<span class="text-base font-medium text-slate-400">/100</span></span>
                                        @else
                                            <span class="text-sm font-medium text-slate-500 italic">Belum dinilai</span>
                                        @endif
                                    </div>
                                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50 flex flex-col justify-center">
                                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Status Penilaian</span>
                                        <div class="flex items-center gap-1.5 mt-1">
                                            @if($submission->score !== null)
                                                <div class="h-2 w-2 rounded-full bg-emerald-500"></div>
                                                <span class="text-sm font-medium text-emerald-700">Selesai Dinilai</span>
                                            @else
                                                <div class="h-2 w-2 rounded-full bg-amber-500"></div>
                                                <span class="text-sm font-medium text-amber-700">Menunggu Penilaian</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <span class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Umpan Balik (Feedback):</span>
                                <div class="p-4 rounded-xl border border-slate-100 bg-white">
                                    @if($submission->feedback)
                                        <p class="text-sm text-slate-700">{{ $submission->feedback }}</p>
                                    @else
                                        <p class="text-sm text-slate-400 italic">Belum ada feedback.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Jika Belum Kumpul -->
                        @if(now()->isAfter($assignment->deadline))
                            <div class="p-5 bg-red-50 border border-red-100 rounded-2xl text-center">
                                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-3">
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <h4 class="font-bold text-red-900 text-base">Batas Waktu Berakhir</h4>
                                <p class="text-xs text-red-700 mt-1">Anda tidak dapat mengumpulkan jawaban karena batas waktu telah lewat.</p>
                            </div>
                        @else
                            <form action="{{ route('siswa.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                                @csrf
                                
                                <div x-data="fileUpload('file')">
                                    <x-input-label for="file" value="Unggah File Jawaban" class="font-bold text-slate-800" />
                                    <div 
                                        @dragover.prevent="dragover = true"
                                        @dragleave.prevent="dragover = false"
                                        @drop.prevent="drop($event)"
                                        :class="{'border-accent bg-blue-50/50': dragover, 'border-slate-200 bg-slate-50': !dragover}"
                                        class="mt-2 flex justify-center rounded-xl border-2 border-dashed px-6 pt-5 pb-6 transition-colors"
                                    >
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-10 w-10 text-slate-300" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-slate-600 justify-center">
                                                <label for="file" class="relative cursor-pointer rounded-md font-bold text-accent hover:text-blue-500 focus-within:outline-none">
                                                    <span>Pilih file</span>
                                                    <input id="file" name="file" type="file" required class="sr-only" @change="handleFileChange">
                                                </label>
                                                <p class="pl-1">atau tarik ke sini</p>
                                            </div>
                                            <p class="text-[11px] text-slate-500">Maks. 20MB. Format sesuai instruksi.</p>
                                        </div>
                                    </div>
                                    
                                    <div x-show="fileName" style="display: none;" class="mt-3 flex items-center p-3 bg-blue-50 border border-blue-100 rounded-lg">
                                        <svg class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm3.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75z" />
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-slate-900 truncate" x-text="fileName"></p>
                                            <p class="text-xs text-blue-600" x-text="fileSize"></p>
                                        </div>
                                        <button type="button" @click="removeFile" class="ml-2 text-slate-400 hover:text-red-500 transition-colors" title="Hapus">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                    <x-input-error :messages="$errors->get('file')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="notes" value="Catatan Tambahan (Opsional)" class="font-bold text-slate-800" />
                                    <textarea id="notes" name="notes" rows="3" placeholder="Pesan singkat untuk guru..."
                                        class="mt-2 w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:bg-white transition duration-150">{{ old('notes') }}</textarea>
                                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                                </div>

                                <x-button type="submit" variant="primary" class="w-full justify-center py-3 shadow-md">
                                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                    </svg>
                                    Kumpulkan Sekarang
                                </x-button>
                            </form>
                        @endif
                    @endif
                </x-card>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('fileUpload', (inputId) => ({
                dragover: false,
                fileName: '',
                fileSize: '',
                
                drop(event) {
                    this.dragover = false;
                    if (event.dataTransfer.files.length > 0) {
                        const input = document.getElementById(inputId);
                        input.files = event.dataTransfer.files;
                        this.updateDisplay(input.files[0]);
                    }
                },
                handleFileChange(event) {
                    if (event.target.files.length > 0) {
                        this.updateDisplay(event.target.files[0]);
                    }
                },
                updateDisplay(file) {
                    this.fileName = file.name;
                    this.fileSize = this.formatBytes(file.size);
                },
                removeFile() {
                    const input = document.getElementById(inputId);
                    input.value = '';
                    this.fileName = '';
                    this.fileSize = '';
                },
                formatBytes(bytes, decimals = 2) {
                    if (!+bytes) return '0 Bytes';
                    const k = 1024;
                    const dm = decimals < 0 ? 0 : decimals;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
                }
            }));
        });
    </script>
    </div>
</x-layouts.app>
