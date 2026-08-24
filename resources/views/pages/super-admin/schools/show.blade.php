<x-layouts.app title="Detail Sekolah">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Detail Sekolah</h1>
            <p class="text-sm text-slate-500 mt-1">Informasi lengkap tentang {{ $school->name }}.</p>
        </div>
        <div class="flex gap-x-3">
            <a href="{{ route('super_admin.schools.index') }}" class="inline-flex items-center justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">
                Kembali
            </a>
            <a href="{{ route('super_admin.schools.edit', $school) }}" class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                Edit Sekolah
            </a>
        </div>
    </div>

    <!-- Alert Status -->
    @if(!$school->is_active)
        <div class="rounded-xl bg-red-50 p-4 mb-6 shadow-sm ring-1 ring-inset ring-red-600/20">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Sekolah Dinonaktifkan</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>Tenant ini sedang dalam status tidak aktif. Seluruh pengguna dari sekolah ini (Admin, Guru, Siswa, dll) tidak dapat masuk ke sistem.</p>
                    </div>
                </div>
                <div class="ml-auto pl-3">
                    <form action="{{ route('super_admin.schools.toggle-status', $school) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="inline-flex rounded-md bg-red-50 px-2 py-1.5 text-sm font-medium text-red-800 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-red-50">Aktifkan Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="rounded-xl bg-emerald-50 p-4 mb-6 shadow-sm ring-1 ring-inset ring-emerald-600/20 flex items-center justify-between">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3 text-sm font-medium text-emerald-800">Sekolah Aktif</span>
            </div>
            <form action="{{ route('super_admin.schools.toggle-status', $school) }}" method="POST" onsubmit="return confirm('Yakin ingin menonaktifkan sekolah ini? Seluruh penggunanya tidak akan bisa login.')">
                @csrf
                @method('PATCH')
                <input type="hidden" name="is_active" value="0">
                <button type="submit" class="inline-flex rounded-md bg-emerald-50 px-2 py-1.5 text-xs font-semibold text-red-600 ring-1 ring-inset ring-red-600/20 hover:bg-red-50 transition-colors">Nonaktifkan</button>
            </form>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Kolom Info & Tenant (Kiri) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informasi Sekolah -->
            <div class="overflow-hidden bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl">
                <div class="px-4 py-5 sm:px-6 flex items-center justify-between border-b border-slate-200">
                    <h3 class="text-base font-semibold leading-6 text-slate-900">Informasi Sekolah</h3>
                </div>
                <div class="border-t border-slate-100">
                    <dl class="divide-y divide-slate-100">
                        <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-slate-500">Logo</dt>
                            <dd class="mt-1 text-sm text-slate-900 sm:col-span-2 sm:mt-0">
                                @if($school->logo)
                                    <img src="{{ Storage::url($school->logo) }}" alt="Logo" class="h-16 w-16 rounded-lg object-cover border border-slate-200">
                                @else
                                    <span class="text-slate-400 italic">Tidak ada logo</span>
                                @endif
                            </dd>
                        </div>
                        <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-slate-500">Nama Sekolah</dt>
                            <dd class="mt-1 text-sm text-slate-900 font-semibold sm:col-span-2 sm:mt-0">{{ $school->name }}</dd>
                        </div>
                        <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-slate-500">NPSN</dt>
                            <dd class="mt-1 text-sm text-slate-900 sm:col-span-2 sm:mt-0">{{ $school->npsn ?? '-' }}</dd>
                        </div>
                        <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-slate-500">Email</dt>
                            <dd class="mt-1 text-sm text-slate-900 sm:col-span-2 sm:mt-0">{{ $school->email ?? '-' }}</dd>
                        </div>
                        <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-slate-500">Telepon</dt>
                            <dd class="mt-1 text-sm text-slate-900 sm:col-span-2 sm:mt-0">{{ $school->phone ?? '-' }}</dd>
                        </div>
                        <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-slate-500">Alamat</dt>
                            <dd class="mt-1 text-sm text-slate-900 sm:col-span-2 sm:mt-0">{{ $school->address ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Informasi Tenant (Statistik) -->
            <div class="overflow-hidden bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl">
                <div class="px-4 py-5 sm:px-6 border-b border-slate-200">
                    <h3 class="text-base font-semibold leading-6 text-slate-900">Statistik Tenant</h3>
                </div>
                <div class="bg-slate-50 p-6">
                    <dl class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow-sm ring-1 ring-slate-200 sm:p-6 text-center">
                            <dt class="truncate text-sm font-medium text-slate-500">Total Pengguna</dt>
                            <dd class="mt-1 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($school->users_count) }}</dd>
                        </div>
                        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow-sm ring-1 ring-slate-200 sm:p-6 text-center">
                            <dt class="truncate text-sm font-medium text-slate-500">Jumlah Guru</dt>
                            <dd class="mt-1 text-3xl font-bold tracking-tight text-blue-600">{{ number_format($school->teachers_count) }}</dd>
                        </div>
                        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow-sm ring-1 ring-slate-200 sm:p-6 text-center">
                            <dt class="truncate text-sm font-medium text-slate-500">Jumlah Siswa</dt>
                            <dd class="mt-1 text-3xl font-bold tracking-tight text-emerald-600">{{ number_format($school->students_count) }}</dd>
                        </div>
                        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow-sm ring-1 ring-slate-200 sm:p-6 text-center">
                            <dt class="truncate text-sm font-medium text-slate-500">Jumlah Kelas</dt>
                            <dd class="mt-1 text-3xl font-bold tracking-tight text-amber-600">{{ number_format($school->classrooms_count) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Kolom Admin Sekolah (Kanan) -->
        <div class="space-y-6">
            <div class="overflow-hidden bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl">
                <div class="px-4 py-5 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-200 gap-y-4">
                    <h3 class="text-base font-semibold leading-6 text-slate-900">Admin Sekolah <span class="ml-2 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-700">{{ count($admins) }}</span></h3>
                    
                    <a href="{{ route('super_admin.schools.admins.create', $school) }}" class="inline-flex items-center justify-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-primary shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">
                        + Tambah Admin Sekolah
                    </a>
                </div>
                <div class="border-t border-slate-100">
                    <ul role="list" class="divide-y divide-slate-100">
                        @forelse($admins as $admin)
                            <li class="flex flex-col sm:flex-row sm:items-center justify-between gap-x-6 py-4 px-4 sm:px-6 hover:bg-slate-50 transition-colors">
                                <div class="flex min-w-0 gap-x-4">
                                    <div class="h-10 w-10 flex-none rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-500 text-sm ring-1 ring-slate-200">
                                        {{ substr($admin->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0 flex-auto">
                                        <p class="text-sm font-semibold leading-6 text-slate-900">
                                            <a href="{{ route('super_admin.schools.admins.show', [$school, $admin]) }}" class="hover:underline hover:text-primary">
                                                {{ $admin->name }}
                                            </a>
                                        </p>
                                        <p class="mt-1 truncate text-xs leading-5 text-slate-500">{{ $admin->email }} &bull; Bergabung {{ $admin->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="mt-4 sm:mt-0 flex flex-row items-center gap-x-3 sm:flex-col sm:items-end sm:gap-y-1">
                                    @if($admin->is_active)
                                        <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">Nonaktif</span>
                                    @endif

                                    <div class="flex items-center gap-2 mt-1">
                                        <a href="{{ route('super_admin.schools.admins.edit', [$school, $admin]) }}" class="text-xs font-medium text-slate-500 hover:text-amber-600 transition-colors">Edit</a>
                                        <span class="text-slate-300">|</span>
                                        <form action="{{ route('super_admin.schools.admins.toggle-status', [$school, $admin]) }}" method="POST" onsubmit="return confirm('{{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Admin Sekolah ini?\n\nNama: {{ $admin->name }}\nEmail: {{ $admin->email }}\nSekolah: {{ $school->name }}')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="is_active" value="{{ $admin->is_active ? 0 : 1 }}">
                                            @if($admin->is_active)
                                                <button type="submit" class="text-xs font-medium text-slate-500 hover:text-red-600 transition-colors">Nonaktifkan</button>
                                            @else
                                                <button type="submit" class="text-xs font-medium text-slate-500 hover:text-emerald-600 transition-colors">Aktifkan</button>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="py-8 px-4 text-center">
                                <p class="text-sm text-slate-500 font-medium">Belum ada Admin Sekolah</p>
                                <div class="mt-4">
                                    <a href="{{ route('super_admin.schools.admins.create', $school) }}" class="inline-flex items-center justify-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                                        + Tambah Admin Sekolah
                                    </a>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
