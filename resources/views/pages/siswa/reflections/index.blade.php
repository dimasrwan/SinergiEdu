<x-layouts.app>
    <x-slot:title>Refleksi Pembelajaran</x-slot:title>

    <div class="space-y-6">
        <x-page-header 
            title="Refleksi Pembelajaran" 
            description="Tuliskan pemahaman, kendala, dan kesan belajar Anda untuk setiap pertemuan pembelajaran." 
        />

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-sm text-emerald-800 flex items-center gap-3 shadow-2xs">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($meetings->isEmpty())
            <div class="bg-slate-50 border border-slate-200/75 rounded-2xl py-12 px-8 text-center shadow-sm max-w-3xl mx-auto w-full">
                <div class="h-16 w-16 bg-white border border-slate-200 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-16.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-16.25v16.25" /></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Pertemuan Pembelajaran</h3>
                <p class="text-sm text-slate-500 font-medium">Belum ada pertemuan pembelajaran yang dicatat oleh guru pada semester aktif ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form Tulis Refleksi -->
                <div class="lg:col-span-2 space-y-6">
                    <x-card padding="lg" class="border border-slate-200">
                        <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 mb-5">Tulis / Perbarui Refleksi</h3>
                        
                        <form action="{{ route('siswa.reflections.store') }}" method="POST" class="space-y-5" x-data="{
                            selectedMeeting: '{{ old('learning_meeting_id', $selectedMeetingId ?? $meetings->first()->id) }}',
                            reflectionsMap: @js($reflections->mapWithKeys(fn($r) => [$r->learning_meeting_id => $r->content])),
                            content: '',
                            init() {
                                this.updateContent();
                                this.$watch('selectedMeeting', () => this.updateContent());
                            },
                            updateContent() {
                                this.content = this.reflectionsMap[this.selectedMeeting] || '';
                            }
                        }">
                            @csrf

                            <div>
                                <x-input-label for="learning_meeting_id" :value="__('Pilih Pertemuan Pembelajaran')" />
                                <x-select id="learning_meeting_id" name="learning_meeting_id" required x-model="selectedMeeting" :options="$meetings->map(fn($m) => [
                                    'value' => $m->id, 
                                    'label' => 'Pertemuan ' . $m->meeting_number . ' - ' . ($m->subject->name ?? 'Mapel') . ' (' . $m->topic . ')'
                                ])->toArray()" />
                                @error('learning_meeting_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <x-input-label for="content" :value="__('Isi Refleksi Pembelajaran')" />
                                <p class="text-xs text-slate-500 mb-2 font-medium">
                                    💡 Panduan: Apa yang kamu pelajari hari ini? Apa yang sudah kamu pahami? Apa yang masih terasa sulit?
                                </p>
                                <textarea id="content" name="content" x-model="content" rows="6" required 
                                    placeholder="Tuliskan refleksi Anda di sini..."
                                    class="w-full px-4 py-3 bg-slate-50 hover:bg-white border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors leading-relaxed"></textarea>
                                @error('content') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex justify-end">
                                <x-button variant="primary" type="submit" class="w-full sm:w-auto min-h-[44px] justify-center">
                                    Simpan Refleksi
                                </x-button>
                            </div>
                        </form>
                    </x-card>
                </div>

                <!-- Histori Refleksi Siswa -->
                <div class="lg:col-span-1 space-y-4 min-w-0">
                    <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm min-w-0">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Riwayat Refleksi</h3>
                        <div class="space-y-4 max-h-[500px] overflow-y-auto pr-1 min-w-0">
                            @foreach($meetings as $m)
                                @php
                                    $ref = $reflections->get($m->id);
                                @endphp
                                <div class="p-3.5 rounded-xl border {{ $ref ? 'bg-blue-50/40 border-blue-100' : 'bg-slate-50/70 border-slate-200/70' }} space-y-2 min-w-0">
                                    <div class="flex items-center justify-between gap-2 min-w-0">
                                        <span class="text-xs font-bold text-slate-800 shrink-0">Pertemuan {{ $m->meeting_number }}</span>
                                        <span class="text-[10px] font-semibold text-slate-500 shrink-0">{{ $m->meeting_date ? $m->meeting_date->format('d/m/Y') : '' }}</span>
                                    </div>
                                    <div class="text-xs text-slate-500 font-medium break-words min-w-0">
                                        {{ $m->subject->name ?? 'Mapel' }} &bull; {{ $m->topic }}
                                    </div>
                                    @if($ref)
                                        <p class="text-xs text-slate-700 bg-white p-2.5 rounded-lg border border-blue-100 leading-relaxed whitespace-pre-line break-words min-w-0">
                                            {{ $ref->content }}
                                        </p>
                                    @else
                                        <p class="text-[11px] text-slate-400 italic">Belum ada refleksi untuk pertemuan ini.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
