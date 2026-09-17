<x-layouts.app>
    <x-slot:title>Dashboard Komite Sekolah</x-slot:title>

    <div class="space-y-6 sm:space-y-8">
        <!-- Banner Header -->
        <div class="bg-primary rounded-xl sm:rounded-2xl p-4 sm:p-6 text-white shadow-lg shadow-primary/20 relative">
            <div class="absolute inset-0 rounded-xl sm:rounded-2xl overflow-hidden pointer-events-none">
                <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            </div>
            
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                <div>
                    <h1 class="text-lg sm:text-2xl font-bold tracking-tight mb-1">Dashboard Komite Sekolah</h1>
                    <p class="text-blue-200 text-xs sm:text-sm font-medium max-w-xl">
                        Ringkasan informasi dan kinerja sekolah — {{ $school->name ?? 'Sekolah' }}
                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0 pt-1 sm:pt-0">
                    <a href="{{ route('komite.aspirations.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-xs sm:text-sm font-bold text-primary bg-white hover:bg-slate-100 rounded-xl shadow-xs transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Buat Aspirasi Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- 4 Statistics Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
            <x-card padding="sm" class="hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400">Total Siswa</span>
                    <div class="text-primary bg-blue-50 p-1.5 sm:p-2 rounded-lg border border-blue-100 shrink-0">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M22 10v6M2 10l10-5 10 5-10 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl sm:text-3xl font-extrabold tracking-tight text-slate-900 leading-none">{{ number_format($totalStudents) }}</h3>
                    <p class="text-[10px] sm:text-xs text-slate-500 mt-1 sm:mt-2 font-medium">Siswa terdaftar</p>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400">Total Guru</span>
                    <div class="text-accent bg-sky-50 p-1.5 sm:p-2 rounded-lg border border-sky-100 shrink-0">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path stroke-linecap="round" stroke-linejoin="round" d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl sm:text-3xl font-extrabold tracking-tight text-slate-900 leading-none">{{ number_format($totalTeachers) }}</h3>
                    <p class="text-[10px] sm:text-xs text-slate-500 mt-1 sm:mt-2 font-medium">Tenaga Pengajar</p>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400">Total Kelas</span>
                    <div class="text-indigo-600 bg-indigo-50 p-1.5 sm:p-2 rounded-lg border border-indigo-100 shrink-0">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14 22v-4a2 2 0 1 0-4 0v4"/><path stroke-linecap="round" stroke-linejoin="round" d="m18 10 4 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-8l4-2"/><path stroke-linecap="round" stroke-linejoin="round" d="M18 5v17"/><path stroke-linecap="round" stroke-linejoin="round" d="m4 6 8-4 8 4"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 5v17"/><circle cx="12" cy="9" r="2"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl sm:text-3xl font-extrabold tracking-tight text-slate-900 leading-none">{{ number_format($totalClasses) }}</h3>
                    <p class="text-[10px] sm:text-xs text-slate-500 mt-1 sm:mt-2 font-medium">Rombongan Belajar</p>
                </div>
            </x-card>

            <x-card padding="sm" class="hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400">Status Sekolah</span>
                    <div class="text-emerald-600 bg-emerald-50 p-1.5 sm:p-2 rounded-lg border border-emerald-100 shrink-0">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-base sm:text-xl font-extrabold tracking-tight text-slate-900 leading-tight">
                        {{ $school->is_active ?? true ? 'Aktif Terverifikasi' : 'Nonaktif' }}
                    </h3>
                    <p class="text-[10px] sm:text-xs text-slate-500 mt-1 font-medium truncate" title="NPSN: {{ $school->npsn ?? '-' }}">NPSN: {{ $school->npsn ?? '-' }}</p>
                </div>
            </x-card>
        </div>

        <!-- Section: Ringkasan Kinerja Sekolah -->
        <x-card padding="none" class="overflow-hidden">
            <div class="border-b border-slate-100 bg-white px-4 sm:px-6 py-4 sm:py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">Ringkasan Kinerja Sekolah</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Ringkasan indikator akademis & statistik operasional sekolah.</p>
                </div>
                <a href="{{ route('komite.performance-summary') }}" class="text-xs font-bold text-primary hover:underline self-start sm:self-auto">
                    Lihat Detail Kinerja &rarr;
                </a>
            </div>
            
            <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Rasio Guru & Siswa</p>
                    <p class="text-2xl font-extrabold text-slate-900">
                        @if($totalTeachers > 0)
                            1 : {{ round($totalStudents / $totalTeachers, 1) }}
                        @else
                            -
                        @endif
                    </p>
                    <p class="text-xs text-slate-500 mt-1">Estimasi jumlah siswa per 1 guru pengajar.</p>
                </div>
                
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Rata-rata Ukuran Kelas</p>
                    <p class="text-2xl font-extrabold text-slate-900">
                        @if($totalClasses > 0)
                            {{ round($totalStudents / $totalClasses, 1) }} Siswa
                        @else
                            -
                        @endif
                    </p>
                    <p class="text-xs text-slate-500 mt-1">Estimasi kapabilitas tampung rombel.</p>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tahun Ajaran Aktif</p>
                    <p class="text-lg font-bold text-primary">
                        {{ $activeAcademicYear->year ?? 'Belum Diatur' }}
                    </p>
                    <p class="text-xs text-slate-500 mt-1">
                        {{ $activeSemester->name ?? 'Belum ada semester aktif' }}
                    </p>
                </div>
            </div>
        </x-card>

        <!-- Section: Program Sekolah -->
        <x-card padding="none" class="overflow-hidden">
            <div class="border-b border-slate-100 bg-white px-4 sm:px-6 py-4 sm:py-5 flex items-center justify-between">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">Program Sekolah</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar agenda dan program prioritas sekolah.</p>
                </div>
                <a href="{{ route('komite.school-programs') }}" class="text-xs font-bold text-primary hover:underline">
                    Selengkapnya &rarr;
                </a>
            </div>
            
            <div class="p-4 sm:p-6">
                @if(count($programs) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($programs as $program)
                            <div class="p-4 rounded-xl border border-slate-200/80 hover:border-slate-300 transition-colors bg-white shadow-2xs">
                                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-wider">
                                    {{ $program['category'] ?? 'Program' }}
                                </span>
                                <h3 class="text-sm font-bold text-slate-900 mt-2">{{ $program['name'] }}</h3>
                                <p class="text-xs text-slate-600 mt-1 line-clamp-2">{{ $program['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                        <p class="text-sm font-semibold text-slate-700">Belum Ada Program Sekolah Ditambahkan</p>
                        <p class="text-xs text-slate-500 mt-1">Program kerja sekolah akan ditampilkan di sini setelah dipublikasikan oleh sekolah.</p>
                    </div>
                @endif
            </div>
        </x-card>

        <!-- Section: Aspirasi Terbaru -->
        <x-card padding="none" class="overflow-hidden">
            <div class="border-b border-slate-100 bg-white px-4 sm:px-6 py-4 sm:py-5 flex items-center justify-between">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">Aspirasi Terbaru</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Aspirasi dan masukan yang disampaikan Komite Sekolah.</p>
                </div>
                <a href="{{ route('komite.aspirations.index') }}" class="text-xs font-bold text-primary hover:underline">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentAspirations as $aspiration)
                    <div class="p-4 sm:p-5 hover:bg-slate-50/80 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-1.5">
                            <h3 class="text-sm font-bold text-slate-900">
                                <a href="{{ route('komite.aspirations.show', $aspiration) }}" class="hover:text-primary transition-colors">
                                    {{ $aspiration->title }}
                                </a>
                            </h3>
                            <div class="flex items-center gap-2">
                                @if($aspiration->status === 'pending')
                                    <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-bold uppercase tracking-wider border border-amber-200">
                                        Menunggu Tanggapan
                                    </span>
                                @elseif($aspiration->status === 'processed')
                                    <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-wider border border-blue-200">
                                        Sedang Diproses
                                    </span>
                                @elseif($aspiration->status === 'resolved')
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider border border-emerald-200">
                                        Selesai
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider border border-slate-200">
                                        {{ ucfirst($aspiration->status) }}
                                    </span>
                                @endif
                                <span class="text-xs text-slate-400 font-medium">
                                    {{ $aspiration->created_at->translatedFormat('d M Y') }}
                                </span>
                            </div>
                        </div>
                        <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                            {{ $aspiration->content }}
                        </p>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                        </svg>
                        <p class="text-sm font-semibold text-slate-700">Belum Ada Aspirasi Dikirim</p>
                        <p class="text-xs text-slate-500 mt-1">Sampaikan masukan, gagasan, atau aspirasi komite untuk kemajuan sekolah.</p>
                        <div class="mt-4">
                            <a href="{{ route('komite.aspirations.create') }}" class="inline-flex items-center px-3.5 py-1.5 text-xs font-bold text-white bg-primary hover:bg-blue-800 rounded-lg transition-colors">
                                + Buat Aspirasi Pertama
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </x-card>
    </div>
</x-layouts.app>
