<x-layouts.app>
    <x-slot:title>{{ $assignment->title }}</x-slot:title>

    <div class="max-w-5xl mx-auto space-y-6">
        <div class="mb-4">
            <a href="{{ route('siswa.assignments.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-primary gap-1.5 transition mb-3">
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
            <!-- Kolom Informasi Tugas -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 shadow-sm">
                    <div class="mb-5">
                        <div class="flex items-center gap-2 flex-wrap mb-3">
                            <span class="inline-flex text-[10px] font-bold text-primary bg-blue-50 px-2.5 py-1 rounded-lg uppercase tracking-wider">
                                {{ $assignment->subject->name ?? 'Umum' }}
                            </span>
                            @if($assignment->learningMeeting)
                                <span class="inline-flex text-[10px] font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg uppercase tracking-wider">
                                    Pertemuan {{ $assignment->learningMeeting->meeting_number }}
                                </span>
                            @endif
                            @if($assignment->material)
                                <span class="inline-flex text-[10px] font-bold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-lg uppercase tracking-wider">
                                    Materi: {{ $assignment->material->title }}
                                </span>
                            @endif
                        </div>
                        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 leading-tight">{{ $assignment->title }}</h1>
                    </div>

                    <div class="flex flex-wrap gap-x-8 gap-y-4 text-sm text-slate-600 mb-8 pb-6 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <div>
                                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Guru Pengampu</span>
                                <span class="font-bold text-slate-800">{{ $assignment->teacher->user->name ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <div>
                                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tenggat Waktu</span>
                                <span class="font-bold {{ now()->isAfter($assignment->deadline) ? 'text-red-600' : 'text-slate-800' }}">{{ $assignment->deadline->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-sm font-bold text-slate-900 mb-3 uppercase tracking-wider">Instruksi Tugas</h3>
                    <div class="text-sm md:text-base text-slate-700 whitespace-pre-wrap leading-relaxed">{{ $assignment->description }}</div>
                    
                    @if($assignment->attachment_path)
                        <div class="mt-8 pt-6 border-t border-slate-100">
                            <h4 class="text-[11px] font-bold text-slate-500 mb-3 uppercase tracking-wider">Lampiran Pendukung</h4>
                            <div class="border border-slate-200 rounded-xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-50 hover:bg-slate-100 transition-colors">
                                <div class="flex items-center gap-4 text-center sm:text-left">
                                    <div class="p-3 bg-white text-slate-600 rounded-xl shadow-sm border border-slate-100">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900">Materi Tugas (Berkas)</h4>
                                        <p class="text-xs text-slate-500 mt-0.5">Unduh untuk membaca rincian soal selengkapnya.</p>
                                    </div>
                                </div>
                                <a href="{{ route('siswa.assignments.download', $assignment) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-lg-lg text-sm font-semibold transition w-full sm:w-auto shadow-sm">
                                    Unduh File
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Kolom Pengumpulan Jawaban -->
            <div class="lg:col-span-1">
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-md shadow-slate-200/50 sticky top-6">
                    <h3 class="text-[15px] font-bold text-slate-900 mb-4 border-b border-slate-100 pb-3">Pengumpulan Jawaban</h3>

                    @if($submission)
                        <!-- Jika Sudah Kumpul -->
                        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl mb-5">
                            <div class="flex items-center gap-2 text-emerald-800 font-bold mb-1">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                                Tugas Diserahkan
                            </div>
                            <p class="text-xs text-emerald-700">Dikumpulkan pada {{ $submission->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <span class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">File Jawaban Anda:</span>
                                <a href="{{ route('siswa.assignments.submissions.download', $assignment) }}" target="_blank" class="flex items-center gap-3 p-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg transition text-slate-700 group">
                                    <div class="p-2 bg-white rounded-lg shadow-sm border border-slate-100 text-slate-500 group-hover:text-primary transition-colors">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </div>
                                    <span class="text-[13px] font-bold group-hover:text-primary transition-colors">Lihat Berkas</span>
                                </a>
                            </div>
                            @if($submission->notes)
                                <div>
                                    <span class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Catatan Tambahan:</span>
                                    <div class="text-[13px] text-slate-700 bg-slate-50 border border-slate-200 p-3 rounded-xl">
                                        {{ $submission->notes }}
                                    </div>
                                </div>
                            @endif

                            <div class="mt-6 pt-5 border-t border-slate-100">
                                <h4 class="text-[13px] font-bold text-slate-900 mb-3">Penilaian Guru</h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="p-3 rounded-xl border border-slate-200 bg-slate-50">
                                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nilai</span>
                                        @if($submission->score !== null)
                                            <span class="text-2xl font-bold text-slate-900 leading-none">{{ $submission->score }}</span>
                                        @else
                                            <span class="text-xs font-medium text-slate-500 italic">Belum dinilai</span>
                                        @endif
                                    </div>
                                    <div class="p-3 rounded-xl border border-slate-200 bg-slate-50 flex flex-col justify-center">
                                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status</span>
                                        <div class="flex items-center gap-1.5">
                                            @if($submission->score !== null)
                                                <div class="h-1.5 w-1.5 rounded-full bg-emerald-500"></div>
                                                <span class="text-xs font-bold text-emerald-700">Selesai</span>
                                            @else
                                                <div class="h-1.5 w-1.5 rounded-full bg-amber-500"></div>
                                                <span class="text-xs font-bold text-amber-700">Menunggu</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <span class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Umpan Balik (Feedback):</span>
                                <div class="p-3 rounded-xl border border-slate-200 bg-white">
                                    @if($submission->feedback)
                                        <p class="text-[13px] text-slate-700">{{ $submission->feedback }}</p>
                                    @else
                                        <p class="text-[13px] text-slate-400 italic">Belum ada feedback.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Jika Belum Kumpul -->
                        @if(now()->isAfter($assignment->deadline))
                            <div class="p-5 bg-red-50 border border-red-100 rounded-xl text-center">
                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-2">
                                    <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <h4 class="font-bold text-red-900 text-sm">Batas Waktu Berakhir</h4>
                                <p class="text-[11px] text-red-700 mt-1">Anda tidak dapat mengumpulkan jawaban karena batas waktu telah lewat.</p>
                            </div>
                        @else
                            <form action="{{ route('siswa.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                
                                <div x-data="fileUpload('file')">
                                    <label for="file" class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Unggah File Jawaban</label>
                                    <div 
                                        @dragover.prevent="dragover = true"
                                        @dragleave.prevent="dragover = false"
                                        @drop.prevent="drop($event)"
                                        :class="{'border-primary bg-blue-50/50': dragover, 'border-slate-200 bg-slate-50 hover:border-slate-300': !dragover}"
                                        class="flex justify-center rounded-lg border-2 border-dashed px-4 py-5 transition-colors cursor-pointer"
                                        @click="document.getElementById('file').click()"
                                    >
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-8 w-8 text-slate-400 mb-2" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-[13px] text-slate-600 justify-center">
                                                <span class="font-bold text-primary">Pilih File</span>
                                                <input id="file" name="file" type="file" required class="sr-only" @change="handleFileChange">
                                                <p class="pl-1">atau tarik ke sini</p>
                                            </div>
                                            <p class="text-[10px] text-slate-400">Maksimal 20MB</p>
                                        </div>
                                    </div>
                                    
                                    <div x-show="fileName" style="display: none;" class="mt-2 flex items-center p-2 bg-blue-50 border border-blue-100 rounded-lg-lg">
                                        <svg class="h-5 w-5 text-blue-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm3.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75z" />
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[11px] font-bold text-slate-900 truncate" x-text="fileName"></p>
                                            <p class="text-[10px] font-semibold text-blue-600" x-text="fileSize"></p>
                                        </div>
                                        <button type="button" @click.stop="removeFile" class="ml-2 text-slate-400 hover:text-red-500 transition-colors p-1" title="Hapus">
                                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('file')
                                        <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="notes" class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Catatan Tambahan (Opsional)</label>
                                    <textarea id="notes" name="notes" rows="2" placeholder="Pesan singkat untuk guru..."
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 text-[13px] focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary focus:bg-white transition duration-150">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg text-sm font-bold transition shadow-sm">
                                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                    </svg>
                                    Kumpulkan Tugas
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
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
                removeFile(event) {
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
</x-layouts.app>
