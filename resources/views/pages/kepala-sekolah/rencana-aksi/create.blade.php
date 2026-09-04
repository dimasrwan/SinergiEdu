<x-layouts.app>
    <x-slot:title>Buat Rencana Aksi</x-slot:title>

    <div class="max-w-3xl space-y-6 mx-auto">
        <div>
            <a href="{{ route('kepala-sekolah.rencana-aksi.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-primary gap-1.5 transition-colors mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Kembali ke Daftar Rencana Aksi
            </a>
            <x-page-header title="Buat Rencana Aksi" description="Tetapkan langkah tindak lanjut yang jelas dan terukur." />
        </div>

        <x-card padding="lg">
            <form action="{{ route('kepala-sekolah.rencana-aksi.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="title" :value="__('Judul')" />
                    <x-text-input id="title" name="title" value="{{ old('title') }}" required placeholder="Judul rencana aksi" class="w-full" />
                    @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <x-input-label for="description" :value="__('Deskripsi (Opsional)')" />
                    <textarea id="description" name="description" rows="4" placeholder="Uraikan rencana aksi secara detail..."
                        class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white border border-slate-200 rounded-lg text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">{{ old('description') }}</textarea>
                    @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="target_role" :value="__('Target Role')" />
                        <x-select id="target_role" name="target_role">
                            <option value="">-- Umum --</option>
                            <option value="guru" {{ old('target_role') === 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="waka" {{ old('target_role') === 'waka' ? 'selected' : '' }}>Waka Kurikulum</option>
                            <option value="pengawas" {{ old('target_role') === 'pengawas' ? 'selected' : '' }}>Pengawas</option>
                        </x-select>
                        @error('target_role') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <x-input-label for="target_user_id" :value="__('Target Orang (Opsional)')" />
                        <x-select id="target_user_id" name="target_user_id">
                            <option value="">-- Semua sesuai role --</option>
                            @foreach($targets as $roleKey => $people)
                                <optgroup label="{{ ucfirst($roleKey) }}">
                                    @foreach($people as $person)
                                        <option value="{{ $person['id'] }}" {{ (string) old('target_user_id') === (string) $person['id'] ? 'selected' : '' }}>{{ $person['label'] }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </x-select>
                        @error('target_user_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="category" :value="__('Kategori')" />
                        <x-select id="category" name="category" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="academic" {{ old('category') === 'academic' ? 'selected' : '' }}>Akademik</option>
                            <option value="character" {{ old('category') === 'character' ? 'selected' : '' }}>Karakter</option>
                            <option value="memorization" {{ old('category') === 'memorization' ? 'selected' : '' }}>Hafalan</option>
                            <option value="operational" {{ old('category') === 'operational' ? 'selected' : '' }}>Operasional</option>
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

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="start_date" :value="__('Tanggal Mulai')" />
                        <x-text-input id="start_date" name="start_date" type="date" value="{{ old('start_date') }}" class="w-full" />
                        @error('start_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <x-input-label for="due_date" :value="__('Tanggal Tenggat')" />
                        <x-text-input id="due_date" name="due_date" type="date" value="{{ old('due_date') }}" class="w-full" />
                        @error('due_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <x-input-label for="notes" :value="__('Catatan (Opsional)')" />
                    <textarea id="notes" name="notes" rows="3" placeholder="Catatan tambahan..."
                        class="w-full px-4 py-2.5 bg-slate-50 hover:bg-white border border-slate-200 rounded-lg text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">{{ old('notes') }}</textarea>
                    @error('notes') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <x-button variant="primary" type="submit">
                        Simpan Rencana Aksi
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>