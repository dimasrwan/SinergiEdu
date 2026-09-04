<x-layouts.app title="Detail Admin Sekolah">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Detail Admin Sekolah</h1>
            <p class="text-sm text-slate-500 mt-1">Informasi lengkap profil Admin untuk {{ $school->name }}.</p>
        </div>
        <div class="flex gap-x-3">
            <a href="{{ route('super_admin.schools.show', $school) }}" class="inline-flex items-center justify-center rounded-lg-lg bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">
                Kembali
            </a>
            <a href="{{ route('super_admin.schools.admins.edit', [$school, $admin]) }}" class="inline-flex items-center justify-center rounded-lg-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                Edit Admin
            </a>
        </div>
    </div>

    <!-- Alert Status Admin -->
    @if(!$admin->is_active)
        <div class="rounded-xl bg-red-50 p-4 mb-6 shadow-sm ring-1 ring-inset ring-red-600/20">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Akses Admin Dinonaktifkan</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>Pengguna ini sedang dalam status tidak aktif dan tidak dapat mengakses area operasional sekolah.</p>
                    </div>
                </div>
                <div class="ml-auto pl-3">
                    <form action="{{ route('super_admin.schools.admins.toggle-status', [$school, $admin]) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="inline-flex rounded-lg bg-red-50 px-2 py-1.5 text-sm font-medium text-red-800 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-red-50">Aktifkan Akses</button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <div class="overflow-hidden bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl max-w-3xl">
        <div class="px-4 py-5 sm:px-6 flex items-center justify-between border-b border-slate-200">
            <h3 class="text-base font-semibold leading-6 text-slate-900">Profil Admin</h3>
            @if($admin->is_active)
                <span class="inline-flex items-center rounded-lg bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Aktif</span>
            @endif
        </div>
        <div class="border-t border-slate-100">
            <dl class="divide-y divide-slate-100">
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 hover:bg-slate-50 transition-colors">
                    <dt class="text-sm font-medium text-slate-500">Nama Lengkap</dt>
                    <dd class="mt-1 text-sm text-slate-900 font-semibold sm:col-span-2 sm:mt-0">{{ $admin->name }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 hover:bg-slate-50 transition-colors">
                    <dt class="text-sm font-medium text-slate-500">Email Login</dt>
                    <dd class="mt-1 text-sm text-slate-900 sm:col-span-2 sm:mt-0">{{ $admin->email }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 hover:bg-slate-50 transition-colors">
                    <dt class="text-sm font-medium text-slate-500">Sekolah Penugasan</dt>
                    <dd class="mt-1 text-sm text-slate-900 font-semibold sm:col-span-2 sm:mt-0 text-primary">{{ $school->name }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 hover:bg-slate-50 transition-colors">
                    <dt class="text-sm font-medium text-slate-500">Role Sistem</dt>
                    <dd class="mt-1 text-sm text-slate-900 sm:col-span-2 sm:mt-0">{{ $admin->role->display_name }} ({{ $admin->role->name }})</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 hover:bg-slate-50 transition-colors">
                    <dt class="text-sm font-medium text-slate-500">Tanggal Bergabung</dt>
                    <dd class="mt-1 text-sm text-slate-900 sm:col-span-2 sm:mt-0">{{ $admin->created_at->translatedFormat('d F Y, H:i') }} WIB</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 hover:bg-slate-50 transition-colors">
                    <dt class="text-sm font-medium text-slate-500">Pembaruan Terakhir</dt>
                    <dd class="mt-1 text-sm text-slate-900 sm:col-span-2 sm:mt-0">{{ $admin->updated_at->diffForHumans() }}</dd>
                </div>
            </dl>
        </div>
        @if($admin->is_active)
        <div class="bg-slate-50 px-4 py-4 sm:px-6 border-t border-slate-200 flex justify-end">
            <form action="{{ route('super_admin.schools.admins.toggle-status', [$school, $admin]) }}" method="POST" onsubmit="return confirm('Nonaktifkan Admin Sekolah?\n\nNama: {{ $admin->name }}\nEmail: {{ $admin->email }}\nSekolah: {{ $school->name }}\n\nTindakan ini hanya menonaktifkan akses akun, tidak akan menghapus data sekolah.')">
                @csrf
                @method('PATCH')
                <input type="hidden" name="is_active" value="0">
                <button type="submit" class="inline-flex rounded-lg bg-white px-3 py-2 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-slate-50 transition-colors">Nonaktifkan Akses Admin</button>
            </form>
        </div>
        @endif
    </div>
</x-layouts.app>
