<x-layouts.app>
    <x-slot:title>Dukungan Belajar Orang Tua</x-slot:title>

    <div class="space-y-8">
        <x-page-header 
            title="Dukungan Belajar Anak" 
            description="Catat dukungan, perkembangan, dan rencana Anda untuk membantu proses belajar anak di rumah." 
        />

        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-200" role="alert">
                <span class="font-bold">Berhasil!</span> {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Child Selector -->
                <div class="bg-white border border-slate-200/75 rounded-2xl p-5 shadow-sm">
                    <h2 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">Anak yang Dipantau</h2>
                    <form action="{{ route('orangtua.support.index') }}" method="GET" class="w-full md:max-w-md">
                        <x-select name="student_id" onchange="this.form.submit()" :selected="$selectedStudentId" :options="$children->map(fn($c) => ['value' => $c->id, 'label' => ($c->user->name ?? 'Anak') . ' (' . ($c->activeClassroom()->name ?? 'Kelas Tidak Ada') . ')'])->toArray()" />
                    </form>
                </div>

                <div class="bg-white border border-slate-200/75 rounded-2xl p-6 md:p-8 shadow-sm">
                    <h2 class="text-[17px] font-bold text-slate-900 mb-6">Tulis Dukungan Belajar</h2>
                    <form action="{{ route('orangtua.support.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $selectedStudentId }}">

                        <!-- 1. Dukungan Orang Tua Minggu Ini -->
                        <div>
                            <x-input-label for="support_description" value="Apa yang Anda lakukan untuk mendukung anak minggu ini?" class="text-slate-700 font-bold mb-2 text-sm" />
                            <x-textarea id="support_description" name="support_description" rows="3" class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-primary focus:border-primary" placeholder="Contoh: Mengajak anak berdiskusi soal materi, mendaftarkan les tambahan..." required>{{ old('support_description') }}</x-textarea>
                            <x-input-error :messages="$errors->get('support_description')" class="mt-2" />
                        </div>

                        <!-- 2. Umpan Balik Secara Umum dari Orang Tua -->
                        <div>
                            <x-input-label for="general_feedback" value="Catatan perkembangan anak" class="text-slate-700 font-bold mb-2 text-sm" />
                            <x-textarea id="general_feedback" name="general_feedback" rows="3" class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-primary focus:border-primary" placeholder="Contoh: Anak terlihat lebih percaya diri mengerjakan PR Matematika..."><?php echo old('general_feedback'); ?></x-textarea>
                            <x-input-error :messages="$errors->get('general_feedback')" class="mt-2" />
                        </div>

                        <!-- 3. Rencana Aksi Orang Tua -->
                        <div>
                            <x-input-label for="action_plan" value="Rencana dukungan minggu depan" class="text-slate-700 font-bold mb-2 text-sm" />
                            <x-textarea id="action_plan" name="action_plan" rows="3" class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-primary focus:border-primary" placeholder="Contoh: Menjadwalkan jam belajar rutin tiap jam 7 malam..."><?php echo old('action_plan'); ?></x-textarea>
                            <x-input-error :messages="$errors->get('action_plan')" class="mt-2" />
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full md:w-auto inline-flex justify-center items-center px-6 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-xl text-[14px] font-bold transition shadow-sm">
                                Simpan Dukungan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Riwayat Dukungan (Right Column) -->
            <div class="col-span-1 space-y-6">
                <!-- Panduan Kolaborasi (Compact) -->
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 shadow-sm flex items-start gap-4">
                    <div class="h-10 w-10 bg-white rounded-xl flex items-center justify-center text-blue-600 shrink-0 shadow-sm border border-blue-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-blue-900 text-sm mb-1">Kolaborasi 4 Pilar</h3>
                        <p class="text-[12px] text-blue-800/80 leading-relaxed font-medium">Catatan dukungan Anda dibaca oleh guru untuk menyelaraskan pendampingan anak.</p>
                    </div>
                </div>

                <!-- Dukungan Sebelumnya -->
                <div class="bg-white border border-slate-200/75 rounded-2xl p-6 shadow-sm">
                    <h3 class="font-bold text-slate-900 text-[15px] mb-5">Dukungan Sebelumnya</h3>

                    <div class="space-y-4">
                        @forelse($supports as $support)
                            <div class="p-4 bg-slate-50 border border-slate-100 rounded-xl space-y-3">
                                <div class="flex justify-between items-center border-b border-slate-200/60 pb-2">
                                    <span class="font-bold text-xs text-primary bg-blue-50 px-2.5 py-1 rounded border border-blue-100 uppercase tracking-wider">
                                        {{ $support->week_number }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium">
                                        {{ $support->created_at->format('d M Y') }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Dukungan Rumah</p>
                                    <p class="text-[13px] text-slate-700 font-medium line-clamp-3 leading-relaxed">{{ $support->support_description }}</p>
                                </div>
                                @if($support->action_plan)
                                    <div class="pt-2 border-t border-slate-200/60">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Rencana Aksi</p>
                                        <p class="text-[13px] text-slate-700 font-medium line-clamp-3 leading-relaxed">{{ $support->action_plan }}</p>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8 text-slate-400 text-xs">
                                Belum ada riwayat dukungan.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $supports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

