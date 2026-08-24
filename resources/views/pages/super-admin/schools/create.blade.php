<x-layouts.app title="Tambah Sekolah">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Tambah Sekolah</h1>
            <p class="text-sm text-slate-500 mt-1">Daftarkan institusi pendidikan baru ke platform SinergiEdu.</p>
        </div>
        <div>
            <a href="{{ route('super_admin.schools.index') }}" class="text-sm font-semibold leading-6 text-slate-900 hover:text-slate-700">
                &larr; Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('super_admin.schools.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-sm ring-1 ring-slate-900/5 sm:rounded-xl md:col-span-2">
        @csrf
        <div class="px-4 py-6 sm:p-8">
            <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <!-- Nama Sekolah -->
                <div class="sm:col-span-4">
                    <label for="name" class="block text-sm font-medium leading-6 text-slate-900">Nama Sekolah/Madrasah <span class="text-red-500">*</span></label>
                    <div class="mt-2">
                        <input type="text" name="name" id="name" required value="{{ old('name') }}" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6">
                    </div>
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- NPSN -->
                <div class="sm:col-span-2">
                    <label for="npsn" class="block text-sm font-medium leading-6 text-slate-900">NPSN</label>
                    <div class="mt-2">
                        <input type="text" name="npsn" id="npsn" value="{{ old('npsn') }}" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6">
                    </div>
                    @error('npsn') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div class="sm:col-span-3">
                    <label for="email" class="block text-sm font-medium leading-6 text-slate-900">Email Utama</label>
                    <div class="mt-2">
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6">
                    </div>
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Telepon -->
                <div class="sm:col-span-3">
                    <label for="phone" class="block text-sm font-medium leading-6 text-slate-900">Nomor Telepon</label>
                    <div class="mt-2">
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6">
                    </div>
                    @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Alamat -->
                <div class="col-span-full">
                    <label for="address" class="block text-sm font-medium leading-6 text-slate-900">Alamat Lengkap</label>
                    <div class="mt-2">
                        <textarea id="address" name="address" rows="3" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6">{{ old('address') }}</textarea>
                    </div>
                    @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Logo -->
                <div class="col-span-full">
                    <label for="logo" class="block text-sm font-medium leading-6 text-slate-900">Logo Sekolah</label>
                    <div class="mt-2 flex items-center gap-x-3">
                        <div class="h-12 w-12 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0">
                            <svg class="h-6 w-6 text-slate-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="file" name="logo" id="logo" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-primary hover:file:bg-blue-100 transition-colors">
                    </div>
                    @error('logo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div class="sm:col-span-3">
                    <label for="is_active" class="block text-sm font-medium leading-6 text-slate-900">Status Awal</label>
                    <div class="mt-2">
                        <select id="is_active" name="is_active" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6">
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">Sekolah aktif dapat langsung digunakan oleh tenant terkait.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end gap-x-4 border-t border-slate-900/10 px-4 py-4 sm:px-8 bg-slate-50 rounded-b-xl">
            <a href="{{ route('super_admin.schools.index') }}" class="text-sm font-semibold leading-6 text-slate-900">Batal</a>
            <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors">
                Simpan Sekolah
            </button>
        </div>
    </form>
</x-layouts.app>
