<x-layouts.app>
    <x-slot:title>Tambah Komite Sekolah</x-slot:title>

    <div class="space-y-6 max-w-3xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Tambah Komite Sekolah</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Buat akun Komite Sekolah baru untuk sekolah anda.</p>
            </div>
            <a href="{{ route('admin.komite.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
                &larr; Kembali
            </a>
        </div>

        <x-card padding="md">
            <form action="{{ route('admin.komite.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary">
                    @error('name')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary">
                    @error('email')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Password *</label>
                        <input type="password" name="password" id="password" required class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary">
                        @error('password')
                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Konfirmasi Password *</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary">
                    </div>
                </div>

                <div>
                    <label for="is_active" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Status Akun *</label>
                    <select name="is_active" id="is_active" required class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary">
                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('is_active')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <a href="{{ route('admin.komite.index') }}" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-primary hover:bg-blue-800 rounded-lg shadow-xs transition-colors">
                        Simpan Komite
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>
