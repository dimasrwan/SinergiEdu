<x-layouts.app>
    <x-slot:title>Edit Tugas</x-slot:title>

    <div class="w-full">
        <div class="mb-6">
            <a href="{{ route('guru.assignments.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <x-page-header title="Edit Tugas" description="Perbarui detail tugas atau ganti lampirannya." />
        </div>

        <x-card padding="lg">
            <form action="{{ route('guru.assignments.update', $assignment) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Section 1: Konteks Pembelajaran -->
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        Konteks Pembelajaran
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Tentukan kelas, mata pelajaran, serta pertemuan atau materi acuan (opsional).</p>
                </div>

                @php
                    $rawMeetings = $meetings->map(fn($m) => [
                        'id' => (string)$m->id,
                        'class_id' => (string)$m->class_id,
                        'subject_id' => (string)$m->subject_id,
                        'label' => 'P' . $m->meeting_number . ' · ' . $m->meeting_date->format('d M Y') . ' · ' . $m->classroom->name . ' · ' . $m->subject->name
                    ])->values()->all();

                    $rawMaterials = $materials->map(fn($mat) => [
                        'id' => (string)$mat->id,
                        'class_id' => (string)$mat->class_id,
                        'subject_id' => (string)$mat->subject_id,
                        'learning_meeting_id' => $mat->learning_meeting_id ? (string)$mat->learning_meeting_id : null,
                        'label' => $mat->title . ' (' . $mat->classroom->name . ' · ' . $mat->subject->code . ')'
                    ])->values()->all();
                @endphp

                <div x-data="{
                    selectedClassId: '{{ old('class_id', $assignment->class_id) }}',
                    selectedSubjectId: '{{ old('subject_id', $assignment->subject_id) }}',
                    selectedMeetingId: '{{ old('learning_meeting_id', $assignment->learning_meeting_id) }}',
                    selectedMaterialId: '{{ old('material_id', $assignment->material_id) }}',
                    allMeetings: @js($rawMeetings),
                    allMaterials: @js($rawMaterials),

                    get availableMeetings() {
                        return this.allMeetings.filter(m => {
                            let matchClass = !this.selectedClassId || String(m.class_id) === String(this.selectedClassId);
                            let matchSubject = !this.selectedSubjectId || String(m.subject_id) === String(this.selectedSubjectId);
                            return matchClass && matchSubject;
                        });
                    },

                    get availableMaterials() {
                        return this.allMaterials.filter(m => {
                            let matchClass = !this.selectedClassId || String(m.class_id) === String(this.selectedClassId);
                            let matchSubject = !this.selectedSubjectId || String(m.subject_id) === String(this.selectedSubjectId);
                            let matchMeeting = !this.selectedMeetingId || String(m.learning_meeting_id) === String(this.selectedMeetingId);
                            return matchClass && matchSubject && matchMeeting;
                        });
                    },

                    onMeetingChange(val) {
                        this.selectedMeetingId = val;
                        this.syncMaterialSelectOptions();
                        // Reset invalid material if selected material does not belong to new meeting or filtered context
                        if (this.selectedMaterialId) {
                            let isValid = this.availableMaterials.some(m => String(m.id) === String(this.selectedMaterialId));
                            if (!isValid) {
                                this.selectedMaterialId = '';
                                this.updateSelectComponent('material_id', '');
                            }
                        }
                    },

                    onClassChange(val) {
                        this.selectedClassId = val;
                        this.syncMeetingSelectOptions();
                        this.syncMaterialSelectOptions();
                        this.validateContext();
                    },

                    onSubjectChange(val) {
                        this.selectedSubjectId = val;
                        this.syncMeetingSelectOptions();
                        this.syncMaterialSelectOptions();
                        this.validateContext();
                    },

                    syncMeetingSelectOptions() {
                        let el = document.getElementById('learning_meeting_id');
                        if (!el) return;
                        let wrapper = el.closest('[x-data]');
                        if (wrapper && wrapper._x_dataStack) {
                            let scope = wrapper._x_dataStack[0];
                            if (scope && 'options' in scope) {
                                scope.options = [
                                    { value: '', label: '-- Belum dikaitkan ke pertemuan --' },
                                    ...this.availableMeetings.map(m => ({ value: m.id, label: m.label }))
                                ];
                                scope.syncLabel();
                            }
                        }
                    },

                    syncMaterialSelectOptions() {
                        let el = document.getElementById('material_id');
                        if (!el) return;
                        let wrapper = el.closest('[x-data]');
                        if (wrapper && wrapper._x_dataStack) {
                            let scope = wrapper._x_dataStack[0];
                            if (scope && 'options' in scope) {
                                scope.options = [
                                    { value: '', label: '-- Belum dikaitkan ke materi --' },
                                    ...this.availableMaterials.map(m => ({ value: m.id, label: m.label }))
                                ];
                                scope.syncLabel();
                            }
                        }
                    },

                    validateContext() {
                        if (this.selectedMeetingId) {
                            let isMeetingValid = this.availableMeetings.some(m => String(m.id) === String(this.selectedMeetingId));
                            if (!isMeetingValid) {
                                this.selectedMeetingId = '';
                                this.updateSelectComponent('learning_meeting_id', '');
                            }
                        }
                        if (this.selectedMaterialId) {
                            let isMatValid = this.availableMaterials.some(m => String(m.id) === String(this.selectedMaterialId));
                            if (!isMatValid) {
                                this.selectedMaterialId = '';
                                this.updateSelectComponent('material_id', '');
                            }
                        }
                    },

                    updateSelectComponent(selectId, val) {
                        let el = document.getElementById(selectId);
                        if (el) {
                            el.value = val;
                            el.dispatchEvent(new Event('change', { bubbles: true }));
                            let wrapper = el.closest('[x-data]');
                            if (wrapper && wrapper._x_dataStack) {
                                let scope = wrapper._x_dataStack[0];
                                if (scope && 'selectedVal' in scope) {
                                    scope.selectedVal = val;
                                    scope.syncLabel();
                                }
                            }
                        }
                    },

                    init() {
                        this.$nextTick(() => {
                            this.syncMeetingSelectOptions();
                            this.syncMaterialSelectOptions();
                        });
                    }
                }" class="space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="class_id" :value="__('Kelas Sasaran')" />
                            <x-select id="class_id" name="class_id" required onchange="this.closest('.space-y-6')._x_dataStack[0].onClassChange(this.value)">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classes as $classItem)
                                    <option value="{{ $classItem->id }}" {{ $assignment->class_id == $classItem->id ? 'selected' : '' }}>{{ $classItem->name }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error :messages="$errors->get('class_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="subject_id" :value="__('Mata Pelajaran')" />
                            <x-select id="subject_id" name="subject_id" required onchange="this.closest('.space-y-6')._x_dataStack[0].onSubjectChange(this.value)">
                                <option value="">-- Pilih Mapel --</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ $assignment->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->name }} ({{ $subject->code }})</option>
                                @endforeach
                            </x-select>
                            <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="learning_meeting_id" :value="__('Pertemuan Pembelajaran (Opsional)')" />
                            <x-select id="learning_meeting_id" name="learning_meeting_id" onchange="this.closest('.space-y-6')._x_dataStack[0].onMeetingChange(this.value)">
                                <option value="">-- Belum dikaitkan ke pertemuan --</option>
                                @foreach($meetings as $meeting)
                                    <option value="{{ $meeting->id }}" @selected(old('learning_meeting_id', $assignment->learning_meeting_id) == $meeting->id)>
                                        P{{ $meeting->meeting_number }} · {{ $meeting->meeting_date->format('d M Y') }} · {{ $meeting->classroom->name }} · {{ $meeting->subject->name }}
                                    </option>
                                @endforeach
                            </x-select>
                            <p class="mt-1 text-xs text-slate-500">Pilih pertemuan agar tugas dapat dipantau sesuai rencana pembelajaran.</p>
                            <x-input-error :messages="$errors->get('learning_meeting_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="material_id" :value="__('Materi Pembelajaran Terkait (Opsional)')" />
                            <x-select id="material_id" name="material_id">
                                <option value="">-- Belum dikaitkan ke materi --</option>
                                @foreach($materials as $material)
                                    <option value="{{ $material->id }}" @selected(old('material_id', $assignment->material_id) == $material->id)>
                                        {{ $material->title }} ({{ $material->classroom->name }} · {{ $material->subject->code }})
                                    </option>
                                @endforeach
                            </x-select>
                            <p class="mt-1 text-xs text-slate-500">Materi akan difilter berdasarkan pertemuan yang dipilih.</p>
                            <x-input-error :messages="$errors->get('material_id')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Section 2: Detail Tugas -->
                <div class="border-b border-slate-100 pb-3 pt-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        Detail Tugas
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Isi judul tugas, petunjuk pengerjaan, deadline, dan lampiran soal jika ada.</p>
                </div>

                <div>
                    <x-input-label for="title" :value="__('Judul Tugas')" />
                    <x-text-input id="title" name="title" type="text" :value="old('title', $assignment->title)" required placeholder="Contoh: Latihan Soal Logaritma" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" :value="__('Instruksi / Soal')" />
                    <textarea id="description" name="description" rows="5" required placeholder="Tuliskan petunjuk pengerjaan tugas secara jelas..."
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:bg-white transition duration-150">{{ old('description', $assignment->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t pt-6">
                    <div>
                        <x-input-label for="deadline" :value="__('Tenggat Waktu (Deadline)')" />
                        <x-text-input id="deadline" name="deadline" type="datetime-local" :value="old('deadline', $assignment->deadline->format('Y-m-d\TH:i'))" required />
                        <x-input-error :messages="$errors->get('deadline')" class="mt-2" />
                    </div>

                    <div x-data="fileUpload('attachment')">
                        <x-input-label for="attachment" value="Ubah Lampiran Pendukung (Opsional)" />
                        
                        @if($assignment->attachment_path)
                            <div class="mb-3 flex items-center justify-between p-3 bg-blue-50 border border-blue-100 rounded-lg">
                                <div class="flex items-center gap-3 text-sm">
                                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">Lampiran Saat Ini</p>
                                        <a href="{{ route('guru.assignments.download', $assignment) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline">Lihat Berkas</a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div 
                            @dragover.prevent="dragover = true"
                            @dragleave.prevent="dragover = false"
                            @drop.prevent="drop($event)"
                            :class="{'border-accent bg-blue-50/50': dragover, 'border-slate-200 bg-slate-50': !dragover}"
                            class="mt-2 flex justify-center rounded-lg border-2 border-dashed px-6 pt-5 pb-6 transition-colors"
                        >
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-300" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-slate-600 justify-center">
                                    <label for="attachment" class="relative cursor-pointer rounded-md font-medium text-accent hover:text-blue-500 focus-within:outline-none">
                                        <span>Browse file</span>
                                        <input id="attachment" name="attachment" type="file" class="sr-only" @change="handleFileChange">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-slate-500">Maks. 20MB. Biarkan kosong jika tidak ingin mengubah lampiran.</p>
                            </div>
                        </div>
                        
                        <div x-show="fileName" style="display: none;" class="mt-3 flex items-center p-3 bg-slate-50 border border-slate-200 rounded-lg-lg">
                            <svg class="h-6 w-6 text-accent mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm3.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75z" />
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900 truncate" x-text="fileName"></p>
                                <p class="text-xs text-slate-500" x-text="fileSize"></p>
                            </div>
                            <button type="button" @click="removeFile" class="ml-2 text-slate-400 hover:text-danger" title="Hapus Lampiran">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('attachment')" class="mt-2" />
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <x-button variant="secondary" href="{{ route('guru.assignments.index') }}">Batal</x-button>
                    <x-button variant="primary" type="submit">Perbarui Tugas</x-button>
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
