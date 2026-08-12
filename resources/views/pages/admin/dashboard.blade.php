<x-layouts.app>
    <x-slot:title>Dashboard Admin</x-slot:title>

    <div class="space-y-6">
        <!-- System Management Banner -->
        <div class="bg-primary rounded-2xl p-6 text-white shadow-xl shadow-primary/20 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight mb-1">System Management Workspace</h1>
                    <p class="text-blue-200 text-xs sm:text-sm max-w-xl">Kelola pengguna, data akademik, dan konfigurasi sistem SinergiEdu dari satu tempat.</p>
                </div>
                <div class="flex items-center gap-3 shrink-0" x-data="{ openMenu: false }">
                    <div class="relative">
                        <x-button variant="secondary" class="!bg-white/10 !border-white/20 !text-white hover:!bg-white/20 !py-2 !px-4" @click="openMenu = !openMenu" @click.away="openMenu = false">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tindakan Cepat
                        </x-button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="openMenu" x-transition.opacity class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg ring-1 ring-slate-900/5 py-1 z-50 text-slate-700 font-medium text-sm" style="display: none;">
                            <a href="#" class="block px-4 py-2 hover:bg-slate-50 hover:text-primary transition-colors">Tambah Guru</a>
                            <a href="#" class="block px-4 py-2 hover:bg-slate-50 hover:text-primary transition-colors">Tambah Siswa</a>
                            <a href="#" class="block px-4 py-2 hover:bg-slate-50 hover:text-primary transition-colors">Tambah Orang Tua</a>
                            <div class="h-px bg-slate-100 my-1"></div>
                            <a href="#" class="block px-4 py-2 hover:bg-slate-50 hover:text-primary transition-colors">Tambah Kelas Baru</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-card padding="sm" class="hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Guru</span>
                    <div class="text-primary bg-blue-50 p-2.5 rounded-xl border border-blue-100">
                        <!-- User / Teacher Icon -->
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-bold tracking-tight text-slate-900 leading-none">24</h3>
                    <p class="text-xs text-slate-500 mt-2 font-medium">Guru aktif terdaftar</p>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Siswa</span>
                    <div class="text-accent bg-sky-50 p-2.5 rounded-xl border border-sky-100">
                        <!-- Graduation Cap Icon -->
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-bold tracking-tight text-slate-900 leading-none">856</h3>
                    <p class="text-xs text-success mt-2 font-semibold flex items-center gap-1">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" /></svg>
                        +5.2% pendaftaran baru
                    </p>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Orang Tua</span>
                    <div class="text-primary bg-blue-50 p-2.5 rounded-xl border border-blue-100">
                        <!-- Family Icon -->
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.97 5.97 0 00-.75-2.98m-.001 0A9.01 9.01 0 0015 15h-.008a9.01 9.01 0 00-2.247.281m0 0a5.97 5.97 0 00-.75 2.98m0 0a8.96 8.96 0 013.28-3.18m-3.28 3.18a9 9 0 00-6.284-5.851m5.851 5.851a8.96 8.96 0 01-3.28-3.18" /></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-bold tracking-tight text-slate-900 leading-none">812</h3>
                    <p class="text-xs text-slate-500 mt-2 font-medium">Akun aktif / terkoneksi</p>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Kelas</span>
                    <div class="text-accent bg-sky-50 p-2.5 rounded-xl border border-sky-100">
                        <!-- School/Building Icon -->
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-bold tracking-tight text-slate-900 leading-none">24</h3>
                    <p class="text-xs text-slate-500 mt-2 font-medium">Tersebar di 3 tingkat (X, XI, XII)</p>
                </div>
            </x-card>
        </div>

        <!-- System Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- User Growth Line Chart -->
            <div class="lg:col-span-2">
                <x-card padding="md" class="h-full border-t border-slate-100">
                    <h3 class="text-base font-bold text-slate-900 mb-1">Pertumbuhan Pengguna (2026)</h3>
                    <p class="text-xs text-slate-500 mb-6">Akumulasi pendaftaran guru, siswa, dan orang tua dalam 6 bulan terakhir.</p>
                    
                    <div class="relative w-full" style="height: 280px;">
                        <!-- Menggunakan Chart.js -->
                        <canvas id="userGrowthChart"></canvas>
                    </div>
                </x-card>
            </div>

            <!-- User Distribution Chart -->
            <div class="lg:col-span-1">
                <x-card padding="md" class="h-full border-t border-slate-100">
                    <h3 class="text-base font-bold text-slate-900 mb-1">Distribusi Pengguna</h3>
                    <p class="text-xs text-slate-500 mb-6">Berdasarkan role dalam sistem.</p>
                    
                    <div class="relative w-full flex justify-center items-center" style="height: 220px;">
                        <canvas id="userDistChart"></canvas>
                    </div>
                    
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-primary"></span>
                                <span class="text-slate-600 font-medium">Siswa</span>
                            </div>
                            <span class="font-bold text-slate-900">856</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-accent"></span>
                                <span class="text-slate-600 font-medium">Orang Tua</span>
                            </div>
                            <span class="font-bold text-slate-900">812</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-slate-300"></span>
                                <span class="text-slate-600 font-medium">Guru / Staff</span>
                            </div>
                            <span class="font-bold text-slate-900">32</span>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>

        <!-- Dashboard Grid (Recent Users) -->
        <div class="grid grid-cols-1 gap-6 pt-2">
            <div class="col-span-1">
                <x-card padding="none" class="overflow-hidden">
                    <div class="border-b border-slate-100 bg-white px-6 py-5 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Pengguna Terdaftar Terbaru</h3>
                            <p class="text-xs text-slate-500 mt-1">Daftar pengguna yang baru saja dibuat atau bergabung ke sistem.</p>
                        </div>
                        <a href="{{ route('admin.students.index') }}" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-slate-50 text-slate-700 hover:bg-slate-100 transition border border-slate-200">Lihat Semua Data</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Pengguna</th>
                                    <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Tgl Bergabung</th>
                                    <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-6 py-4 font-semibold text-slate-900 text-sm flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">DW</div>
                                        Demo Waka Kurikulum
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">waka@sinergiedu.test</td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 rounded bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider border border-slate-200">Waka</span></td>
                                    <td class="px-6 py-4 text-sm text-slate-500">10 Okt 2026</td>
                                    <td class="px-6 py-4"><span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-green-50 text-green-700 text-[10px] font-bold uppercase tracking-wider"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif</span></td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button class="p-1.5 text-slate-400 hover:text-primary transition" title="Lihat"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg></button>
                                            <button class="p-1.5 text-slate-400 hover:text-accent transition" title="Edit"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-6 py-4 font-semibold text-slate-900 text-sm flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">DG</div>
                                        Demo Guru
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">guru@sinergiedu.test</td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 rounded bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider border border-slate-200">Guru</span></td>
                                    <td class="px-6 py-4 text-sm text-slate-500">08 Okt 2026</td>
                                    <td class="px-6 py-4"><span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-green-50 text-green-700 text-[10px] font-bold uppercase tracking-wider"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif</span></td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button class="p-1.5 text-slate-400 hover:text-primary transition"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg></button>
                                            <button class="p-1.5 text-slate-400 hover:text-accent transition"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-6 py-4 font-semibold text-slate-900 text-sm flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">DS</div>
                                        Demo Siswa
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">siswa@sinergiedu.test</td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 rounded bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider border border-slate-200">Siswa</span></td>
                                    <td class="px-6 py-4 text-sm text-slate-500">01 Sep 2026</td>
                                    <td class="px-6 py-4"><span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-green-50 text-green-700 text-[10px] font-bold uppercase tracking-wider"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif</span></td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button class="p-1.5 text-slate-400 hover:text-primary transition"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg></button>
                                            <button class="p-1.5 text-slate-400 hover:text-accent transition"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50/80 transition-colors group opacity-75">
                                    <td class="px-6 py-4 font-semibold text-slate-900 text-sm flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs font-bold">AL</div>
                                        Alumni Test
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">alumni@test.com</td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 rounded bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider border border-slate-200">Siswa</span></td>
                                    <td class="px-6 py-4 text-sm text-slate-500">20 Jun 2023</td>
                                    <td class="px-6 py-4"><span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider border border-slate-200"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive</span></td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button class="p-1.5 text-slate-400 hover:text-primary transition"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Line Chart: Pertumbuhan Pengguna
            const ctxGrowth = document.getElementById('userGrowthChart').getContext('2d');
            new Chart(ctxGrowth, {
                type: 'line',
                data: {
                    labels: ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Total Pengguna Aktif',
                        data: [1600, 1620, 1680, 1700, 1715, 1730],
                        borderColor: '#123B82', // Primary
                        backgroundColor: 'rgba(18, 59, 130, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#FFFFFF',
                        pointBorderColor: '#123B82',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.04)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#64748B'
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                color: '#64748B'
                            }
                        }
                    }
                }
            });

            // Doughnut Chart: Distribusi Pengguna
            const ctxDist = document.getElementById('userDistChart').getContext('2d');
            new Chart(ctxDist, {
                type: 'doughnut',
                data: {
                    labels: ['Siswa', 'Orang Tua', 'Guru'],
                    datasets: [{
                        data: [856, 812, 32],
                        backgroundColor: [
                            '#123B82', // Primary
                            '#119FEA', // Accent
                            '#CBD5E1'  // Slate-300
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed !== null) {
                                        label += context.parsed;
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-layouts.app>
