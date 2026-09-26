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
                document.body.style.overflow = 'hidden';
                this.$nextTick(() => {
                    this.$refs.confirmBtn?.focus();
                });
            });
        },
        closeModal() {
            if (this.loading) return;
            this.open = false;
            this.errorMessage = '';
            document.body.style.overflow = '';
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
    class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 w-screen h-screen overflow-y-auto"
>
    <!-- Backdrop -->
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-180"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-[#0F172A]/48 backdrop-blur-[4px] transition-opacity"
        @click="closeModal()"
    ></div>

    <!-- Modal Content Card -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2 scale-[0.98]"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-180"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-2 scale-[0.98]"
        class="bg-white border border-[#E2E8F0] rounded-[18px] p-5 sm:p-7 shadow-[0_24px_70px_rgba(15,23,42,0.20)] w-full max-w-[460px] max-h-[calc(100vh-48px)] overflow-y-auto relative z-10 text-center font-sans space-y-3.5 my-auto"
    >
        <!-- 1. ICON -->
        <div class="flex justify-center">
            <template x-if="variant === 'activate'">
                <div class="w-14 h-14 rounded-[14px] bg-[#EFF6FF] border border-[#BAE6FD] flex items-center justify-center text-[#119FEA] shadow-2xs shrink-0">
                    <svg class="w-7 h-7 text-[#119FEA]" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </template>
            <template x-if="variant === 'deactivate'">
                <div class="w-14 h-14 rounded-[14px] bg-[#FFFBEB] border border-[#FDE68A] flex items-center justify-center text-[#D97706] shadow-2xs shrink-0">
                    <svg class="w-7 h-7 text-[#D97706]" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
            </template>
        </div>

        <!-- 2. TITLE & DESCRIPTION -->
        <div class="space-y-1">
            <h3 id="school-status-modal-title" class="text-[20px] sm:text-[22px] font-bold text-[#123B82] leading-[1.3] tracking-tight" x-text="variant === 'activate' ? 'Aktifkan Sekolah?' : 'Nonaktifkan Sekolah?'"></h3>
            
            <p id="school-status-modal-desc" class="text-[13px] sm:text-[14px] text-[#64748B] leading-[1.5]">
                <span x-text="variant === 'activate' ? 'Anda akan mengaktifkan kembali akses sekolah berikut:' : 'Anda akan menonaktifkan akses sekolah berikut:'"></span>
            </p>
        </div>

        <!-- 3. SCHOOL CARD -->
        <div class="px-4 py-3 bg-[#F8FAFC] border border-[#E2E8F0] rounded-[12px] flex items-center justify-center gap-2.5 text-center">
            <svg class="w-4 h-4 text-[#119FEA] shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
            </svg>
            <span class="text-[13px] sm:text-[14px] font-semibold text-[#123B82] truncate" x-text="school?.name"></span>
        </div>

        <!-- 4. INFORMATION BOX -->
        <template x-if="variant === 'activate'">
            <div class="p-3 bg-[#EFF6FF] border border-[#BAE6FD] rounded-[12px] text-left flex items-start gap-2.5">
                <svg class="w-4 h-4 text-[#119FEA] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                <div class="text-[13px] text-[#0F172A] leading-[1.5]">
                    Pengguna yang terdaftar pada sekolah ini dapat kembali mengakses platform sesuai hak akses masing-masing.
                </div>
            </div>
        </template>

        <template x-if="variant === 'deactivate'">
            <div class="p-3 bg-[#FFFBEB] border border-[#FDE68A] rounded-[12px] text-left flex items-start gap-2.5">
                <svg class="w-4 h-4 text-[#D97706] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                <div class="text-[13px] text-[#92400E] leading-[1.5]">
                    <span>Pengguna yang terdaftar pada sekolah ini tidak dapat mengakses platform sampai sekolah diaktifkan kembali.</span>
                    <span class="block text-[12px] text-[#B45309] font-medium mt-0.5">Status akun pengguna tidak diubah.</span>
                </div>
            </div>
        </template>

        <!-- Error Alert Container -->
        <div x-show="errorMessage" x-cloak class="p-2.5 bg-red-50 border border-red-200 rounded-[12px] text-left text-xs font-semibold text-red-700 flex items-start gap-2">
            <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <span x-text="errorMessage"></span>
        </div>

        <!-- 5. ACTION BUTTONS -->
        <form :action="'/super-admin/schools/' + school?.id + '/toggle-status'" method="POST" @submit="loading = true" class="pt-1.5">
            @csrf
            @method('PATCH')
            <input type="hidden" name="is_active" :value="variant === 'activate' ? '1' : '0'">

            <div class="flex items-center justify-end gap-2.5">
                <button
                    type="button"
                    @click="closeModal()"
                    :disabled="loading"
                    class="h-[44px] min-w-[90px] px-4 inline-flex items-center justify-center text-[13px] sm:text-[14px] font-semibold text-[#475569] bg-white hover:bg-[#F8FAFC] active:bg-slate-100 border border-[#CBD5E1] rounded-[10px] transition duration-150 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed shadow-2xs"
                >
                    Batal
                </button>

                <!-- Tombol Aktifkan -->
                <template x-if="variant === 'activate'">
                    <button
                        type="submit"
                        x-ref="confirmBtn"
                        :disabled="loading"
                        class="h-[44px] min-w-[125px] px-5 inline-flex items-center justify-center text-[13px] sm:text-[14px] font-semibold text-white bg-[#123B82] hover:bg-[#0E2D64] active:scale-[0.99] rounded-[10px] transition duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#123B82]/20 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed"
                    >
                        <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="loading ? 'Mengaktifkan...' : 'Aktifkan'"></span>
                    </button>
                </template>

                <!-- Tombol Nonaktifkan -->
                <template x-if="variant === 'deactivate'">
                    <button
                        type="submit"
                        x-ref="confirmBtn"
                        :disabled="loading"
                        class="h-[44px] min-w-[125px] px-5 inline-flex items-center justify-center text-[13px] sm:text-[14px] font-semibold text-white bg-[#D97706] hover:bg-[#B45309] active:scale-[0.99] rounded-[10px] transition duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#D97706]/20 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed"
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
