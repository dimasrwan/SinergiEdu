<x-layouts.app title="Super Admin Dashboard">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Platform Dashboard</h1>
        <p class="text-sm text-slate-500 mt-1">Ringkasan statistik seluruh tenant SinergiEdu.</p>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Total Schools -->
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-900/5">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-slate-500">Total Sekolah Terdaftar</dt>
                            <dd class="mt-1 flex items-baseline">
                                <div class="text-2xl font-bold text-slate-900">{{ number_format($totalSchools) }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('super_admin.schools.index') }}" class="font-medium text-blue-600 hover:text-blue-500">Lihat detail sekolah &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Active Schools -->
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-900/5">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-emerald-50">
                            <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-slate-500">Sekolah Aktif</dt>
                            <dd class="mt-1 flex items-baseline">
                                <div class="text-2xl font-bold text-slate-900">{{ number_format($activeSchools) }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('super_admin.schools.index', ['status' => 'aktif']) }}" class="font-medium text-emerald-600 hover:text-emerald-500">Lihat sekolah aktif &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-900/5">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-50">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-slate-500">Total Pengguna (Platform)</dt>
                            <dd class="mt-1 flex items-baseline">
                                <div class="text-2xl font-bold text-slate-900">{{ number_format($totalUsers) }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 px-5 py-3">
                <div class="text-sm">
                    <span class="font-medium text-slate-500">Total seluruh entitas user.</span>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
