<x-layouts.app>
    <x-slot:title>Kirim Feedback Strategis</x-slot:title>

    <div class="max-w-3xl space-y-6 mx-auto">
        <div>
            <a href="{{ route('kepala-sekolah.feedback.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-primary gap-1.5 transition-colors mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Kembali ke Daftar Feedback
            </a>
            <x-page-header title="Kirim Feedback Strategis" description="Berikan umpan balik konstruktif kepada guru, waka, atau pengawas." />
        </div>

        <x-card padding="lg">
            <form action="{{ route('kepala-sekolah.feedback.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="recipient_role" :value="__('Tujuan')" />
                        <x-select id="recipient_role" name="recipient_role" required>
                            <option value="">-- Pilih Role Penerima --</option>
                            <option value="guru" {{ old('recipient_role') === 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="waka" {{ old('recipient_role') === 'waka' ? 'selected' : '' }}>Waka Kurikulum</option>
                            <option value="pengawas" {{ old('recipient_role') === 'pengawas' ? 'selected' : '' }}>Pengawas</option>
                        </x-select>
                        @error('recipient_role') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <x-input-label for="recipient_id" :value="__('Penerima (Opsional)')" />
                        <x-select id="recipient_id" name="recipient_id">
                            <option value="">-- Semua (Umum) --</option>
                            @php $groups = ['guru' => $teachers, 'waka' => $wakas, 'pengawas' => $pengawas]; @endphp
                            @foreach($groups as $roleKey => $people)
                                <optgroup label="{{ ucfirst($roleKey) }}">
                                    @foreach($people as $person)
                                        <option value="{{ $person['id'] }}" {{ (string) old('recipient_id') === (string) $person['id'] ? 'selected' : '' }}>{{ $person['label'] }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </x-select>
                        @error('recipient_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="category" :value="__('Kategori')" />
                        <x-select id="category" name="category" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="strategic" {{ old('category') === 'strategic' ? 'selected' : '' }}>Strategis</option>
                            <option value="academic" {{ old('category') === 'academic' ? 'selected' : '' }}>Akademik</option>
                            <option value="operational" {{ old('category') === 'operational' ? 'selected' : '' }}>Operasional</option>
                            <option value="recognition" {{ old('category') === 'recognition' ? 'selected' : '' }}>Penghargaan</option>
                        </x-select>
                        @error('category') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <x-input-label for="priority" :value="__('Prioritas')" />
                        <x-select id="priority" name="priority" required>
                            <option value="">-- Pilih Prioritas --</option>
                            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Rendah</option>
                            <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Sedang</option>
                            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>Tinggi</option>
                            <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Segera</option>
                        </x-select>
                        @error('priority') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <x-input-label for="title" :value="__('Judul')" />
                    <x-text-input id="title" name="title" value="{{ old('title') }}" required placeholder="Ringkasan feedback Anda" class="w-full" />
                    @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <x-input-label for="message" :value="__('Isi Feedback')" />
                    <textarea id="message" name="message" rows="5" required placeholder="Tuliskan umpan balik Anda secara mendetail..."
                        class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white border border-slate-200 rounded-lg text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">{{ old('message') }}</textarea>
                    @error('message') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="action_plan" :value="__('Rencana Tindak Lanjut (Opsional)')" />
                        <textarea id="action_plan" name="action_plan" rows="3" placeholder="Langkah tindak lanjut yang diharapkan..."
                            class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white border border-slate-200 rounded-lg text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">{{ old('action_plan') }}</textarea>
                        @error('action_plan') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <x-input-label for="action_deadline" :value="__('Tenggat Tindak Lanjut (Opsional)')" />
                        <x-text-input id="action_deadline" name="action_deadline" type="date" value="{{ old('action_deadline') }}" class="w-full" />
                        @error('action_deadline') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <x-button variant="primary" type="submit">
                        Kirim Feedback
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>