@props([
    'name' => 'school-status-modal',
])

<div
    x-data="{
        open: false,
        school: null,
        variant: 'deactivate', // 'activate' | 'deactivate'
        loading: false,
        errorMessage: '',
        init() {
            window.addEventListener('open-school-status-modal', (e) => {
                this.school = e.detail.school;
                this.variant = e.detail.variant || (this.school?.is_active ? 'deactivate' : 'activate');
                this.loading = false;
                this.errorMessage = '';
                this.open = true;
                this.$nextTick(() => {
                    this.$refs.confirmBtn?.focus();
                });
            });
        },
        closeModal() {
            if (this.loading) return;
            this.open = false;
            this.errorMessage = '';
        },
        handleKeydown(e) {
            if (e.key === 'Escape') {
                this.closeModal();
            }
        }
    }"
    x-show="open"
    x-cloak
    @keydown.window="handleKeydown($event)"
    role="dialog"
    aria-modal="true"
    aria-labelledby="school-status-modal-title"
    aria-describedby="school-status-modal-desc"
    class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center"
>
    <!-- Backdrop -->
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-[#0F172A]/45 backdrop-blur-[2px] transition-opacity"
        @click="closeModal()"
    ></div>

    <!-- Modal Content Card -->
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-1 scale-[0.97]"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-1 scale-[0.97]"
        class="bg-white border border-[#DCE8F3] rounded-3xl p-6 sm:p-8 shadow-xl shadow-[#123B82]/5 max-w-[480px] w-full relative z-10 text-center font-['Outfit'] space-y-5"
    >
        <!-- Icon Badge (Variant-based) -->
        <template x-if="variant === 'activate'">
            <div class="mx-auto w-14 h-14 rounded-2xl bg-[#EAF6FF] border border-[#DCE8F3] flex items-center justify-center text-[#119FEA] shadow-xs">
                <svg class="w-7 h-7 text-[#119FEA]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </template>
        <template x-if="variant === 'deactivate'">
            <div class="mx-auto w-14 h-14 rounded-2xl bg-amber-50 border border-amber-200/80 flex items-center justify-center text-amber-600 shadow-xs">
                <svg class="w-7 h-7 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
        </template>

        <!-- Heading Title -->
        <div>
            <h3 id="school-status-modal-title" class="text-xl sm:text-2xl font-extrabold text-[#0B1733] tracking-tight" x-text="variant === 'activate' ? 'Aktifkan Sekolah?' : 'Nonaktifkan Sekolah?'"></h3>
            
            <p class="text-xs sm:text-sm text-[#64748B] mt-1.5">
                <span x-text="variant === 'activate' ? 'Anda akan mengaktifkan kembali:' : 'Anda akan menonaktifkan:'"></span>
            </p>

            <!-- Dynamic School Name Box -->
            <div class="mt-2.5 p-3 bg-[#F8FAFC] border border-[#DCE8F3] rounded-xl text-sm sm:text-base font-bold text-[#0B1733] break-words whitespace-normal" x-text="school?.name"></div>
        </div>

        <!-- Description -->
        <p id="school-status-modal-desc" class="text-xs sm:text-sm text-[#475569] leading-relaxed text-left sm:text-center">
            <template x-if="variant === 'activate'">
                <span>Setelah diaktifkan, pengguna yang terdaftar pada sekolah ini dapat kembali mengakses platform sesuai hak akses masing-masing.</span>
            </template>
            <template x-if="variant === 'deactivate'">
                <span>Setelah dinonaktifkan, pengguna yang terdaftar pada sekolah ini tidak dapat mengakses platform hingga sekolah diaktifkan kembali.</span>
            </template>
        </p>

        <!-- Informational Impact Box (Khusus Nonaktifkan) -->
        <template x-if="variant === 'deactivate'">
            <div class="p-3.5 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl text-left flex items-start gap-2.5">
                <svg class="w-4 h-4 text-[#123B82] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-[11px] sm:text-xs text-[#64748B] leading-relaxed">
                    <strong class="text-[#0B1733] font-semibold block mb-0.5">Catatan Penting:</strong>
                    Status akun pengguna tidak diubah. Akses akan tersedia kembali secara otomatis ketika sekolah diaktifkan.
                </div>
            </div>
        </template>

        <!-- Error Alert Container (jika gagal) -->
        <div x-show="errorMessage" x-cloak class="p-3 bg-red-50 border border-red-200 rounded-xl text-left text-xs font-semibold text-red-700 flex items-start gap-2">
            <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <span x-text="errorMessage"></span>
        </div>

        <!-- Action Buttons -->
        <form :action="'/super-admin/schools/' + school?.id + '/toggle-status'" method="POST" @submit="loading = true" class="pt-2">
            @csrf
            @method('PATCH')
            <input type="hidden" name="is_active" :value="variant === 'activate' ? '1' : '0'">

            <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2.5 sm:gap-3">
                <button
                    type="button"
                    @click="closeModal()"
                    :disabled="loading"
                    class="w-full sm:w-auto px-5 py-2.5 text-xs sm:text-sm font-bold text-[#475569] bg-[#F1F5F9] hover:bg-[#E2E8F0] active:scale-[0.98] rounded-xl transition cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Batal
                </button>

                <!-- Tombol Aktifkan (Primary Navy #123B82) -->
                <template x-if="variant === 'activate'">
                    <button
                        type="submit"
                        x-ref="confirmBtn"
                        :disabled="loading"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 text-xs sm:text-sm font-bold text-white bg-[#123B82] hover:bg-[#0F3170] active:scale-[0.98] rounded-xl transition shadow-sm focus:outline-none focus:ring-2 focus:ring-[#123B82] cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed"
                    >
                        <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="loading ? 'Mengaktifkan...' : 'Aktifkan'"></span>
                    </button>
                </template>

                <!-- Tombol Nonaktifkan (Soft Amber/Warning #D97706) -->
                <template x-if="variant === 'deactivate'">
                    <button
                        type="submit"
                        x-ref="confirmBtn"
                        :disabled="loading"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 text-xs sm:text-sm font-bold text-white bg-amber-600 hover:bg-amber-700 active:scale-[0.98] rounded-xl transition shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed"
                    >
                        <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="loading ? 'Menonaktifkan...' : 'Nonaktifkan'"></span>
                    </button>
                </template>
            </div>
        </form>
    </div>
</div>
