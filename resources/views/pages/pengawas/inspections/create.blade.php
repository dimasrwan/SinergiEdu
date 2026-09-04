<x-layouts.app>
    <x-slot:title>Jadwalkan Inspeksi - Pengawas</x-slot:title>

    <div class="space-y-6">
        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('pengawas.inspections.index') }}" class="hover:text-slate-800 font-semibold transition flex items-center gap-1">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Jadwal Inspeksi
            </a>
            <span>/</span>
            <span class="text-slate-800 font-semibold">Jadwalkan Inspeksi</span>
        </div>

        <div>
            <h1 class="text-2xl font-bold text-slate-900">Jadwalkan Inspeksi</h1>
            <p class="text-sm text-slate-500 mt-1">Buat jadwal inspeksi untuk memantau kinerja sekolah.</p>
        </div>

        <x-card padding="md">
            <form method="POST" action="{{ route('pengawas.inspections.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">
                            Judul Inspeksi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Contoh: Inspeksi Rutin April 2026">
                        @error('title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Sekolah</label>
                        <select name="school_id" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">Pilih Sekolah</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Lokasi</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Alamat atau lokasi inspeksi">
                    @error('location')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Tanggal Inspeksi</label>
                        <input type="date" name="inspection_date" value="{{ old('inspection_date') }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        @error('inspection_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Dijadwalkan</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Deskripsi / Catatan</label>
                    <textarea name="content" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none" placeholder="Tuliskan catatan atau deskripsi inspeksi...">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-4 border-t border-slate-200">
                    <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-hover transition font-semibold">
                        Simpan Jadwal
                    </button>
                    <a href="{{ route('pengawas.inspections.index') }}" class="px-6 py-2.5 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition font-semibold">
                        Batal
                    </a>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>