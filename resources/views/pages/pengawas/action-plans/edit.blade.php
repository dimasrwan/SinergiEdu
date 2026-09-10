<x-layouts.app>
    <x-slot:title>Edit Rencana Aksi</x-slot:title>

    <div class="w-full">
        <div class="mb-6">
            <a href="{{ route('pengawas.action-plans.index') }}"
                class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali ke Daftar
            </a>
            <h1 class="text-2xl font-bold text-slate-950 mt-4">Edit Rencana Aksi</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui rencana aksi peningkatan mutu sekolah.</p>
        </div>

        <form action="{{ route('pengawas.action-plans.update', $actionPlan) }}" method="POST"
            class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-semibold text-slate-700 mb-2">Judul Rencana Aksi <span
                            class="text-red-500">*</span></label>
                    <x-text-input id="title" name="title" type="text" :value="old('title', $actionPlan->title)" required
                        class="w-full" />
                    @error('title')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="class_id" class="block text-sm font-semibold text-slate-700 mb-2">Sasaran Kelas</label>
                    <x-select id="class_id" name="class_id" placeholder="Seluruh Sekolah" :selected="old('class_id', $actionPlan->class_id)" :options="$classrooms->map(fn($c) => ['value' => $c->id, 'label' => $c->name])->toArray()" />
                </div>

                <div>
                    <label for="academic_year_id" class="block text-sm font-semibold text-slate-700 mb-2">Tahun Ajaran</label>
                    <x-select id="academic_year_id" name="academic_year_id" placeholder="– Pilih Tahun Ajaran –" :selected="old('academic_year_id', $actionPlan->academic_year_id)" :options="$academicYears->map(fn($y) => ['value' => $y->id, 'label' => $y->name])->toArray()" />
                </div>

                <div>
                    <label for="priority" class="block text-sm font-semibold text-slate-700 mb-2">Prioritas <span class="text-red-500">*</span></label>
                    <x-select id="priority" name="priority" required :selected="old('priority', $actionPlan->priority)" :options="[
                        ['value' => 'high', 'label' => '🔴 Tinggi'],
                        ['value' => 'medium', 'label' => '🟡 Sedang'],
                        ['value' => 'low', 'label' => '⚪ Rendah']
                    ]" />
                    @error('priority')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <x-select id="status" name="status" required :selected="old('status', $actionPlan->status)" :options="[
                        ['value' => 'draft', 'label' => 'Draft'],
                        ['value' => 'published', 'label' => 'Diterbitkan']
                    ]" />
                    @error('status')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="content" class="block text-sm font-semibold text-slate-700 mb-2">Isi Rencana Aksi <span
                        class="text-red-500">*</span></label>
                <textarea id="content" name="content" rows="8" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:bg-white transition duration-150 resize-none">{{ old('content', $actionPlan->content) }}</textarea>
                @error('content')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition shadow-sm">
                    Simpan Perubahan
                </button>
                <a href="{{ route('pengawas.action-plans.index') }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>