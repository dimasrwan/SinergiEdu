<div
    x-data="{
        show: false,
        title: 'Preview Berkas',
        fileUrl: '',
        downloadUrl: '',
        fileName: '',
        fileType: 'other',
        openModal(detail) {
            this.title = detail.title || 'Preview Berkas';
            this.fileUrl = detail.fileUrl || '';
            this.downloadUrl = detail.downloadUrl || detail.fileUrl || '';
            this.fileName = detail.fileName || 'berkas';
            this.fileUrl = detail.fileUrl || detail.url || '';
            this.downloadUrl = detail.downloadUrl || this.fileUrl || '';
            this.fileName = detail.fileName || detail.name || detail.title || 'berkas';

            const ext = (this.fileName.split('.').pop() || '').toLowerCase();
            if (['pdf'].includes(ext)) {
            const mime = (detail.mime || '').toLowerCase();
            const ext = (detail.ext || this.fileName.split('.').pop() || this.fileUrl.split('?')[0].split('.').pop() || '').toLowerCase();
            const requestedType = (detail.fileType || detail.type || '').toLowerCase();

            if (requestedType === 'pdf' || mime.includes('pdf') || ext === 'pdf' || this.fileUrl.toLowerCase().includes('.pdf') || this.downloadUrl.toLowerCase().includes('.pdf')) {
                this.fileType = 'pdf';
            } else if (['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'].includes(ext)) {
            } else if (requestedType === 'image' || mime.startsWith('image/') || ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'].includes(ext)) {
                this.fileType = 'image';
            } else if (['mp4', 'webm', 'ogg'].includes(ext)) {
            } else if (requestedType === 'video' || mime.startsWith('video/') || ['mp4', 'webm', 'ogg', 'mov'].includes(ext) || this.fileUrl.toLowerCase().includes('type=video') || this.downloadUrl.toLowerCase().includes('type=video')) {
                this.fileType = 'video';
            } else {
                this.fileType = 'other';
            }
            this.show = true;
        },
        closeModal() {
            this.show = false;
            this.fileUrl = '';
            this.downloadUrl = '';
        }
    }"
    @open-preview.window="openModal($event.detail)"
    @keydown.escape.window="closeModal()"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
    role="dialog"
    aria-modal="true"
>
    <!-- Backdrop -->
    <div
        x-show="show"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="closeModal()"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
    ></div>

    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center p-3 sm:p-4 md:p-6 text-center">
        <div
            x-show="show"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            @click.stop
            class="w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white text-left align-middle shadow-2xl transition-all border border-slate-200 flex flex-col max-h-[90vh]"
        >
            <!-- Header -->
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 bg-slate-50/75 shrink-0">
                <div class="flex items-center gap-3 min-w-0 pr-4">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-primary shrink-0">
                        <template x-if="fileType === 'pdf'">
                            <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </template>
                        <template x-if="fileType === 'image'">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </template>
                        <template x-if="fileType === 'video'">
                            <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </template>
                        <template x-if="fileType === 'other'">
                            <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </template>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-base font-bold text-slate-900 truncate" x-text="title">Preview Berkas</h3>
                        <p class="text-xs text-slate-500 font-mono truncate" x-text="fileName"></p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                <div class="flex items-center gap-1.5">
                    <a
                        :href="fileUrl"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors focus:outline-none"
                        title="Buka di Tab Baru"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                    </a>
                    <button
                        type="button"
                        @click="closeModal()"
                        class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors focus:outline-none"
                        aria-label="Tutup"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body Content -->
            <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-slate-100/50 flex flex-col justify-center min-h-[300px]">
                <!-- PDF Preview -->
                <template x-if="fileType === 'pdf'">
                    <div class="w-full h-[65vh] rounded-xl overflow-hidden bg-white border border-slate-200 shadow-inner">
                        <iframe
                            :src="fileUrl"
                            class="w-full h-full border-0"
                            title="PDF Viewer"
                        ></iframe>
                    </div>
                </template>

                <!-- Image Preview -->
                <template x-if="fileType === 'image'">
                    <div class="w-full max-h-[65vh] flex items-center justify-center overflow-auto p-2 bg-slate-900/5 rounded-xl border border-slate-200/80">
                        <img
                            :src="fileUrl"
                            :alt="fileName"
                            class="max-h-[60vh] max-w-full object-contain rounded-lg shadow-sm"
                        />
                    </div>
                </template>

                <!-- Video Preview -->
                <template x-if="fileType === 'video'">
                    <div class="w-full aspect-video max-h-[65vh] rounded-xl overflow-hidden bg-slate-950 flex items-center justify-center shadow-md">
                        <video
                            :src="fileUrl"
                            controls
                            class="w-full h-full object-contain"
                        ></video>
                    </div>
                </template>

                <!-- Unsupported / Other File Type -->
                <template x-if="fileType === 'other'">
                    <div class="py-10 px-4 sm:px-6 text-center bg-white border border-slate-200 rounded-xl shadow-xs max-w-lg mx-auto w-full">
                        <div class="w-14 h-14 mx-auto mb-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <h4 class="text-base font-bold text-slate-900 mb-1">Format file tidak dapat dipreview langsung</h4>
                        <p class="text-xs sm:text-sm text-slate-500 max-w-sm mx-auto mb-5 leading-relaxed">
                            File ini tidak dapat ditampilkan di browser secara langsung. Silakan download untuk membuka berkas dengan aplikasi yang sesuai.
                        </p>
                        <a
                            :href="downloadUrl"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary/90 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download File (<span x-text="fileName"></span>)
                        </a>
                    </div>
                </template>
            </div>

            <!-- Footer -->
            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 px-5 py-3.5 border-t border-slate-100 bg-white shrink-0">
                <span class="text-xs text-slate-400 truncate hidden sm:inline" x-text="fileName"></span>
                <div class="flex items-center justify-end gap-2 w-full sm:w-auto">
                    <button
                        type="button"
                        @click="closeModal()"
                        class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 text-xs font-semibold transition"
                    >
                        Tutup
                    </button>
                    <a
                        :href="downloadUrl"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary hover:bg-primary/90 text-white text-xs font-semibold transition shadow-xs"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

