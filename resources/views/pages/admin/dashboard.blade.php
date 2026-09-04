<x-layouts.app>
    <x-slot:title>Dashboard Admin</x-slot:title>

    <div class="space-y-6">
        <!-- System Management Banner -->
        <div class="bg-primary rounded-2xl p-6 text-white shadow-xl shadow-primary/20 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight mb-1">System Management Workspace</h1>
                    <p class="text-blue-200 text-xs sm:text-sm max-w-xl">
                        @if($activeAcademicYear)
                            Tahun Ajaran {{ $activeAcademicYear->year }} • {{ $activeSemester ? $activeSemester->name : 'Belum ada semester aktif' }}
                        @else
                            Belum ada tahun ajaran aktif
                        @endif
                    </p>
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
                            <a href="{{ route('admin.teachers.create') }}" class="block px-4 py-2 hover:bg-slate-50 hover:text-primary transition-colors">Tambah Guru</a>
                            <a href="{{ route('admin.students.create') }}" class="block px-4 py-2 hover:bg-slate-50 hover:text-primary transition-colors">Tambah Siswa</a>
                            <a href="{{ route('admin.parents.create') }}" class="block px-4 py-2 hover:bg-slate-50 hover:text-primary transition-colors">Tambah Orang Tua</a>
                            <div class="h-px bg-slate-100 my-1"></div>
                            <a href="{{ route('admin.classes.create') }}" class="block px-4 py-2 hover:bg-slate-50 hover:text-primary transition-colors">Tambah Kelas Baru</a>
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
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-bold tracking-tight text-slate-900 leading-none">{{ $totalTeachers }}</h3>
                    <p class="text-xs text-slate-500 mt-2 font-medium">Guru terdaftar</p>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Siswa</span>
                    <div class="text-accent bg-sky-50 p-2.5 rounded-xl border border-sky-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-bold tracking-tight text-slate-900 leading-none">{{ $totalStudents }}</h3>
                    <p class="text-xs text-slate-500 mt-2 font-medium">Siswa terdaftar</p>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Orang Tua</span>
                    <div class="text-primary bg-blue-50 p-2.5 rounded-xl border border-blue-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-bold tracking-tight text-slate-900 leading-none">{{ $totalParents }}</h3>
                    <p class="text-xs text-slate-500 mt-2 font-medium">Orang tua/wali terdaftar</p>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Kelas</span>
                    <div class="text-accent bg-sky-50 p-2.5 rounded-xl border border-sky-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 22v-4a2 2 0 1 0-4 0v4"/><path d="m18 10 4 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-8l4-2"/><path d="M18 5v17"/><path d="m4 6 8-4 8 4"/><path d="M6 5v17"/><circle cx="12" cy="9" r="2"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-bold tracking-tight text-slate-900 leading-none">{{ $totalClasses }}</h3>
                    <p class="text-xs text-slate-500 mt-2 font-medium">{{ $educationLevelsString }}</p>
                </div>
            </x-card>
        </div>

        <!-- System Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- User Growth Line Chart -->
            <div class="lg:col-span-2">
                <x-card padding="md" class="h-full border-t border-slate-100 flex flex-col">
                    <h3 class="text-base font-bold text-slate-900 mb-1">Pertumbuhan Pengguna ({{ date('Y') }})</h3>
                    <p class="text-xs text-slate-500 mb-6">Akumulasi pengguna dalam 6 bulan terakhir.</p>
                    
                    @if(count($growthValues) == 0 || max($growthValues) == 0)
                        <div class="flex-1 flex flex-col items-center justify-center text-slate-400 py-10">
                            <svg class="w-12 h-12 mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                            <span class="text-sm font-medium">Belum ada data pertumbuhan pengguna yang cukup.</span>
                        </div>
                    @else
                        <div class="relative w-full" style="height: 280px;">
                            <canvas id="userGrowthChart"></canvas>
                        </div>
                    @endif
                </x-card>
            </div>

            <!-- User Distribution Chart -->
            <div class="lg:col-span-1">
                <x-card padding="md" class="h-full border-t border-slate-100">
                    <h3 class="text-base font-bold text-slate-900 mb-1">Distribusi Pengguna</h3>
                    <p class="text-xs text-slate-500 mb-6">Berdasarkan role dalam sistem.</p>
                    
                    @if(count($distributionData) == 0)
                        <div class="flex-1 flex flex-col items-center justify-center text-slate-400 py-10 h-full">
                            <span class="text-sm font-medium">Tidak ada data pengguna.</span>
                        </div>
                    @else
                        <div class="relative w-full flex justify-center items-center" style="height: 220px;">
                            <canvas id="userDistChart"></canvas>
                        </div>
                        
                        <div class="mt-4 space-y-3">
                            @php
                                $colors = ['#123B82', '#119FEA', '#CBD5E1', '#F59E0B', '#10B981', '#6366F1', '#EC4899'];
                            @endphp
                            @foreach($distributionData as $index => $item)
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full" style="background-color: {{ $colors[$index % count($colors)] }}"></span>
                                        <span class="text-slate-600 font-medium">{{ ucfirst($item->name) }}</span>
                                    </div>
                                    <span class="font-bold text-slate-900">{{ $item->total }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
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
                                @forelse($recentUsers as $user)
                                    @php
                                        $initials = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $user->name), 0, 2));
                                        $bgColors = ['bg-indigo-50 text-indigo-600', 'bg-blue-50 text-blue-600', 'bg-emerald-50 text-emerald-600', 'bg-amber-50 text-amber-600'];
                                        $colorClass = $bgColors[$user->id % count($bgColors)];
                                        $r_name = strtolower($user->role?->name ?? '');
                                        $primaryRole = 'Pengguna';
                                        if ($r_name === 'guru') $primaryRole = 'Guru';
                                        elseif ($r_name === 'siswa') $primaryRole = 'Siswa';
                                        elseif ($r_name === 'orang_tua' || $r_name === 'orang tua') $primaryRole = 'Orang Tua';
                                        elseif ($r_name === 'waka' || $r_name === 'waka_kurikulum' || $r_name === 'waka kurikulum') $primaryRole = 'Waka Kurikulum';
                                        elseif ($r_name === 'pengawas') $primaryRole = 'Pengawas';
                                        elseif ($r_name === 'kepala_sekolah' || $r_name === 'kepala sekolah') $primaryRole = 'Kepala Sekolah/Madrasah';
                                        elseif ($r_name === 'admin') $primaryRole = 'Admin';
                                        else $primaryRole = \Illuminate\Support\Str::title(str_replace('_', ' ', $r_name));
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 transition-colors group">
                                        <td class="px-6 py-4 font-semibold text-slate-900 text-sm flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full {{ $colorClass }} flex items-center justify-center text-xs font-bold">{{ $initials }}</div>
                                            {{ $user->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-600">{{ $user->email }}</td>
                                        <td class="px-6 py-4"><span class="px-2 py-1 rounded bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider border border-slate-200">{{ $primaryRole }}</span></td>
                                        <td class="px-6 py-4 text-sm text-slate-500">{{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d M Y') }}</td>
                                        <td class="px-6 py-4">
                                            @if($user->is_active)
                                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-green-50 text-green-700 text-[10px] font-bold uppercase tracking-wider">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider border border-slate-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                @php
                                                    $r = strtolower($user->role?->name ?? '');
                                                    $showUrl = '#';
                                                    $editUrl = '#';
                                                    if ($user->role_model_id) {
                                                        if ($r === 'guru') { $showUrl = route('admin.teachers.show', $user->role_model_id); $editUrl = route('admin.teachers.edit', $user->role_model_id); }
                                                        elseif ($r === 'siswa') { $showUrl = route('admin.students.show', $user->role_model_id); $editUrl = route('admin.students.edit', $user->role_model_id); }
                                                        elseif ($r === 'orang tua') { $showUrl = route('admin.parents.show', $user->role_model_id); $editUrl = route('admin.parents.edit', $user->role_model_id); }
                                                        elseif ($r === 'waka kurikulum' || $r === 'waka') { $showUrl = route('admin.wakas.show', $user->role_model_id); $editUrl = route('admin.wakas.edit', $user->role_model_id); }
                                                        elseif ($r === 'pengawas') { $showUrl = route('admin.pengawas.show', $user->role_model_id); $editUrl = route('admin.pengawas.edit', $user->role_model_id); }
                                                        elseif ($r === 'kepala sekolah') { $showUrl = route('admin.kepala-sekolah.show', $user->role_model_id); $editUrl = route('admin.kepala-sekolah.edit', $user->role_model_id); }
                                                    }
                                                @endphp
                                                <a href="{{ $showUrl }}" class="p-1.5 text-slate-400 hover:text-primary transition" title="Lihat"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg></a>
                                                <a href="{{ $editUrl }}" class="p-1.5 text-slate-400 hover:text-accent transition" title="Edit" aria-label="Edit"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg></a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500">
                                            Belum ada pengguna terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
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
            @if(count($growthValues) > 0 && max($growthValues) > 0)
            const ctxGrowth = document.getElementById('userGrowthChart').getContext('2d');
            new Chart(ctxGrowth, {
                type: 'line',
                data: {
                    labels: {!! json_encode($growthLabels) !!},
                    datasets: [{
                        label: 'Total Pengguna Aktif',
                        data: {!! json_encode($growthValues) !!},
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
                                color: '#64748B',
                                stepSize: 1 // ensure integers
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
            @endif

            // Doughnut Chart: Distribusi Pengguna
            @if(count($distributionData) > 0)
            const ctxDist = document.getElementById('userDistChart').getContext('2d');
            const distLabels = {!! json_encode($distributionData->pluck('name')->map(fn($v) => ucfirst($v))->toArray()) !!};
            const distValues = {!! json_encode($distributionData->pluck('total')->toArray()) !!};
            const colors = ['#123B82', '#119FEA', '#CBD5E1', '#F59E0B', '#10B981', '#6366F1', '#EC4899'];
            
            new Chart(ctxDist, {
                type: 'doughnut',
                data: {
                    labels: distLabels,
                    datasets: [{
                        data: distValues,
                        backgroundColor: colors.slice(0, distValues.length),
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
            @endif
        });
    </script>
    @endpush
</x-layouts.app>
