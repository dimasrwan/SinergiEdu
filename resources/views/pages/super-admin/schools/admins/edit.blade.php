<x-layouts.app title="Edit Admin Sekolah">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Admin Sekolah</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui data Admin Sekolah untuk <span class="font-semibold text-slate-700">{{ $school->name }}</span>.</p>
        </div>
        <div>
            <a href="{{ route('super_admin.schools.show', $school) }}" class="text-sm font-semibold leading-6 text-slate-900 hover:text-slate-700">
                &larr; Kembali ke Detail Sekolah
            </a>
        </div>
    </div>

    <form action="{{ route('super_admin.schools.admins.update', [$school, $admin]) }}" method="POST" class="bg-white shadow-sm ring-1 ring-slate-900/5 sm:rounded-xl md:col-span-2">
        @csrf
        @method('PUT')
        <div class="px-4 py-6 sm:p-8">
            <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <!-- Nama Lengkap -->
                <div class="sm:col-span-6">
                    <label for="name" class="block text-sm font-medium leading-6 text-slate-900">Nama Lengkap <span class="text-red-500">*</span></label>
                    <div class="mt-2">
                        <input type="text" name="name" id="name" required value="{{ old('name', $admin->name) }}" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6">
                    </div>
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Email Login -->
                <div class="sm:col-span-6">
                    <label for="email" class="block text-sm font-medium leading-6 text-slate-900">Email Login <span class="text-red-500">*</span></label>
                    <div class="mt-2">
                        <input type="email" name="email" id="email" required value="{{ old('email', $admin->email) }}" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6">
                    </div>
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div class="sm:col-span-3">
                    <label for="password" class="block text-sm font-medium leading-6 text-slate-900">Password Baru</label>
                    <div class="mt-2">
                        <input type="password" name="password" id="password" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6">
                    </div>
                    <p class="mt-2 text-xs text-slate-500">Isi hanya jika ingin mengubah kata sandi.</p>
                    @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div class="sm:col-span-3">
                    <label for="password_confirmation" class="block text-sm font-medium leading-6 text-slate-900">Konfirmasi Password Baru</label>
                    <div class="mt-2">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6">
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end gap-x-4 border-t border-slate-900/10 px-4 py-4 sm:px-8 bg-slate-50 rounded-b-xl">
            <a href="{{ route('super_admin.schools.show', $school) }}" class="text-sm font-semibold leading-6 text-slate-900">Batal</a>
            <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors">
                Simpan Perubahan
            </button>
        </div>
    </form>
</x-layouts.app>
