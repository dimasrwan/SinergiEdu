<x-layouts.app title="Edit Komite Sekolah">
    <div class="w-full space-y-6 pt-2 sm:pt-4">
        <!-- Back Navigation -->
        <div class="mb-2 px-1">
            <a href="{{ route('super_admin.schools.show', $school) }}" class="inline-flex items-center text-xs sm:text-sm font-semibold text-slate-500 hover:text-blue-600 gap-1.5 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Detail Sekolah
            </a>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl p-4 sm:p-6 md:p-8 shadow-sm max-w-3xl">
            <div class="border-b border-slate-100 pb-4 mb-6">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Edit Komite Sekolah</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Perbarui akun Komite Sekolah untuk {{ $school->name }}.</p>
            </div>

            <form action="{{ route('super_admin.schools.komite.update', [$school, $komite]) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $komite->name) }}" required class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary">
                    @error('name')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $komite->email) }}" required class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary">
                    @error('email')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Password Baru (Opsional)</label>
                        <input type="password" name="password" id="password" class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary" placeholder="Kosongkan jika tidak diubah">
                        @error('password')
                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary">
                    </div>
                </div>

                <div>
                    <label for="is_active" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Status Akun *</label>
                    <select name="is_active" id="is_active" required class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary">
                        <option value="1" {{ old('is_active', $komite->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active', $komite->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('is_active')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <a href="{{ route('super_admin.schools.show', $school) }}" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-primary hover:bg-blue-800 rounded-lg shadow-xs transition-colors">
                        Perbarui Komite
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
