<x-layouts.app>
    <x-slot:title>Dukungan Belajar Orang Tua</x-slot:title>

    <div class="space-y-8">
        <x-page-header 
            title="Dukungan Belajar & Kolaborasi Orang Tua" 
            description="Tuliskan bentuk dukungan mingguan, umpan balik, dan rencana aksi Anda untuk membantu meningkatkan capaian hasil belajar anak di rumah." 
        />

        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-2xl bg-green-50 border border-green-200" role="alert">
                <span class="font-bold">Berhasil!</span> {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Input Dukungan Mingguan (Left 2 Columns) -->
            <div class="lg:col-span-2 space-y-6">
                <x-card padding="lg" class="shadow-sm border border-slate-200">
                    <div class="border-b border-slate-100 pb-4 mb-6 flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Form Dukungan Mingguan & Umpan Balik</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Isi dokumen kolaborasi mingguan untuk anak Anda</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $activeYear ? $activeYear->year : 'Tahun Ajaran Aktif' }}
                        </span>
                    </div>

                    <form action="{{ route('orangtua.support.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Pilih Anak -->
                        <div>
                            <x-input-label for="student_id" :value="__('Pilih Anak')" />
                            <x-select id="student_id" name="student_id" class="mt-1 block w-full">
                                @foreach($children as $child)
                                    <option value="{{ $child->id }}" {{ $selectedStudentId == $child->id ? 'selected' : '' }}>
                                        {{ $child->user->name ?? 'Anak' }} ({{ $child->activeClassroom()->name ?? 'Kelas Tidak Ada' }})
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                        </div>

                        <!-- 1. Dukungan Orang Tua Minggu Ini -->
                        <div>
                            <x-input-label for="support_description" :value="__('1. Dukungan Orang Tua Minggu Ini (Terhadap Anak)')" />
                            <p class="text-xs text-slate-500 mb-2">Tuliskan dukungan konkret yang Anda berikan, misalnya: mendampingi belajar di rumah, mendaftarkan les privat, mengawasi penyelesaian PR/tugas awal, dll.</p>
                            <x-textarea id="support_description" name="support_description" rows="3" class="w-full" placeholder="Contoh: Meminta anak belajar 1 jam setiap malam, mengawasi pengerjaan tugas awal matematika..." required>{{ old('support_description') }}</x-textarea>
                            <x-input-error :messages="$errors->get('support_description')" class="mt-2" />
                        </div>

                        <!-- 2. Umpan Balik Secara Umum dari Orang Tua -->
                        <div>
                            <x-input-label for="general_feedback" :value="__('2. Umpan Balik Secara Umum (Terhadap Capaian Hasil Belajar)')" />
                            <p class="text-xs text-slate-500 mb-2">Masukan atau tanggapan Anda mengenai hasil tes, hafalan, atau catatan perilaku anak minggu ini.</p>
                            <x-textarea id="general_feedback" name="general_feedback" rows="3" class="w-full" placeholder="Contoh: Anak terlihat lebih percaya diri dalam pengerjaan tes awal, namun perlu perhatian ekstra pada hafalan..."><?php echo old('general_feedback'); ?></x-textarea>
                            <x-input-error :messages="$errors->get('general_feedback')" class="mt-2" />
                        </div>

                        <!-- 3. Rencana Aksi Orang Tua -->
                        <div>
                            <x-input-label for="action_plan" :value="__('3. Usulan / Saran Rencana Aksi Orang Tua')" />
                            <p class="text-xs text-slate-500 mb-2">Rencana kegiatan yang akan dilakukan orang tua untuk meningkatkan atau mempertahankan capaian minggu depan.</p>
                            <x-textarea id="action_plan" name="action_plan" rows="3" class="w-full" placeholder="Contoh: Menambah jadwal setoran hafalan mandiri di rumah setiap Sabtu pagi..."><?php echo old('action_plan'); ?></x-textarea>
                            <x-input-error :messages="$errors->get('action_plan')" class="mt-2" />
                        </div>

                        <div class="flex justify-end pt-2">
                            <x-primary-button class="w-full md:w-auto justify-center">
                                Simpan Dukungan & Kolaborasi
                            </x-primary-button>
                        </div>
                    </form>
                </x-card>
            </div>

            <!-- Riwayat Dukungan (Right Column) -->
            <div class="col-span-1 space-y-6">
                <div class="bg-slate-900 text-white rounded-3xl p-6 shadow-xl relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="h-10 w-10 bg-white/10 rounded-2xl flex items-center justify-center text-blue-300 mb-4 border border-white/10">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-1">Panduan Kolaborasi 4 Pilar</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Form ini adalah sarana komunikasi 4 pilar (Guru, Orang Tua, Waka Kurikulum, Pengawas). Catatan dukungan Anda akan dibaca oleh Guru dan Waka Kurikulum untuk menyelaraskan pendampingan belajar di sekolah.
                        </p>
                    </div>
                </div>

                <x-card padding="md" class="border border-slate-200">
                    <h3 class="font-bold text-slate-900 text-sm mb-4">Riwayat Dukungan Yang Diinput</h3>

                    <div class="space-y-4">
                        @forelse($supports as $support)
                            <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl space-y-2">
                                <div class="flex justify-between items-center border-b border-slate-200/60 pb-2">
                                    <span class="font-bold text-xs text-blue-700 bg-blue-100/60 px-2.5 py-0.5 rounded-full">
                                        {{ $support->week_number }}
                                    </span>
                                    <span class="text-[10px] text-slate-400">
                                        {{ $support->created_at->format('d M Y') }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-800">Dukungan Rumah:</p>
                                    <p class="text-xs text-slate-600 line-clamp-2">{{ $support->support_description }}</p>
                                </div>
                                @if($support->action_plan)
                                    <div>
                                        <p class="text-xs font-semibold text-emerald-800">Rencana Aksi:</p>
                                        <p class="text-xs text-slate-600 line-clamp-2">{{ $support->action_plan }}</p>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8 text-slate-400 text-xs">
                                Belum ada riwayat dukungan yang diinput.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $supports->links() }}
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.app>

