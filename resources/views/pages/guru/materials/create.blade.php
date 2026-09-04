<x-layouts.app>
    <x-slot:title>Upload Materi Baru</x-slot:title>

    <div class="w-full">
        <div class="mb-6">
            <a href="{{ route('guru.materials.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <x-page-header title="Upload Materi Pembelajaran Baru" description="Isi formulir dan unggah berkas materi pelajaran Anda." />
        </div>

        <x-card padding="lg">
            <form action="{{ route('guru.materials.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="class_id" :value="__('Kelas Sasaran')" />
                        <x-select id="class_id" name="class_id" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classes as $classItem)
                                <option value="{{ $classItem->id }}">{{ $classItem->name }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error :messages="$errors->get('class_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="subject_id" :value="__('Mata Pelajaran')" />
                        <x-select id="subject_id" name="subject_id" required>
                            <option value="">-- Pilih Mapel --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                            @endforeach
                        </x-select>
                        <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="learning_meeting_id" :value="__('Pertemuan Pembelajaran (Opsional)')" />
                    <x-select id="learning_meeting_id" name="learning_meeting_id">
                        <option value="">-- Belum dikaitkan ke pertemuan --</option>
                        @foreach($meetings as $meeting)
                            <option value="{{ $meeting->id }}" @selected(old('learning_meeting_id') == $meeting->id)>
                                P{{ $meeting->meeting_number }} · {{ $meeting->meeting_date->format('d M Y') }} · {{ $meeting->classroom->name }} · {{ $meeting->subject->name }}
                            </option>
                        @endforeach
                    </x-select>
                    <p class="mt-1 text-xs text-slate-500">Pilih pertemuan agar Waka dapat menelusuri materi sesuai topik dan alat/bahan yang direncanakan.</p>
                    <x-input-error :messages="$errors->get('learning_meeting_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="title" :value="__('Judul Materi')" />
                    <x-text-input id="title" name="title" type="text" :value="old('title')" required placeholder="Contoh: Pengenalan Aljabar Linear" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" :value="__('Deskripsi / Instruksi')" />
                    <textarea id="description" name="description" rows="4" placeholder="Tuliskan petunjuk pembelajaran bagi siswa..."
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:bg-white transition duration-150">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-slate-100">
                    <!-- PDF Upload -->
                    <div x-data="fileUpload('file')">
                        <x-input-label for="file" value="Unggah Berkas PDF (Maks 10MB)" />
                        <div 
                            @dragover.prevent="dragover = true"
                            @dragleave.prevent="dragover = false"
                            @drop.prevent="drop($event)"
                            :class="{'border-accent bg-blue-50/50': dragover, 'border-slate-200 bg-slate-50': !dragover}"
                            class="mt-2 flex justify-center rounded-xl border-2 border-dashed px-6 pt-5 pb-6 transition-colors"
                        >
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-300" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-slate-600 justify-center">
                                    <label for="file" class="relative cursor-pointer rounded-md font-medium text-accent hover:text-blue-500 focus-within:outline-none">
                                        <span>Browse file</span>
                                        <input id="file" name="file" type="file" accept="application/pdf" class="sr-only" @change="handleFileChange">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-slate-500">PDF up to 10MB</p>
                            </div>
                        </div>
                        
                        <div x-show="fileName" style="display: none;" class="mt-3 flex items-center p-3 bg-slate-50 border border-slate-200 rounded-lg">
                            <svg class="h-6 w-6 text-danger mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900 truncate" x-text="fileName"></p>
                                <p class="text-xs text-slate-500" x-text="fileSize"></p>
                            </div>
                            <button type="button" @click="removeFile" class="ml-2 text-slate-400 hover:text-danger" title="Hapus File">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('file')" class="mt-2" />
                    </div>

                    <!-- Video Upload -->
                    <div x-data="fileUpload('video')">
                        <x-input-label for="video" value="Unggah Berkas Video MP4 (Maks 50MB)" />
                        <div 
                            @dragover.prevent="dragover = true"
                            @dragleave.prevent="dragover = false"
                            @drop.prevent="drop($event)"
                            :class="{'border-accent bg-blue-50/50': dragover, 'border-slate-200 bg-slate-50': !dragover}"
                            class="mt-2 flex justify-center rounded-xl border-2 border-dashed px-6 pt-5 pb-6 transition-colors"
                        >
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-300" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M15 10l20 14-20 14V10z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-slate-600 justify-center">
                                    <label for="video" class="relative cursor-pointer rounded-md font-medium text-accent hover:text-blue-500 focus-within:outline-none">
                                        <span>Browse video</span>
                                        <input id="video" name="video" type="file" accept="video/mp4,video/x-m4v,video/*" class="sr-only" @change="handleFileChange">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-slate-500">MP4 up to 50MB</p>
                            </div>
                        </div>
                        
                        <div x-show="fileName" style="display: none;" class="mt-3 flex items-center p-3 bg-slate-50 border border-slate-200 rounded-lg">
                            <svg class="h-6 w-6 text-accent mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900 truncate" x-text="fileName"></p>
                                <p class="text-xs text-slate-500" x-text="fileSize"></p>
                            </div>
                            <button type="button" @click="removeFile" class="ml-2 text-slate-400 hover:text-danger" title="Hapus Video">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('video')" class="mt-2" />
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <x-button variant="secondary" href="{{ route('guru.materials.index') }}">Batal</x-button>
                    <x-button variant="primary" type="submit">Simpan & Unggah Materi</x-button>
                </div>
            </form>
        </x-card>
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
</x-layouts.app>
