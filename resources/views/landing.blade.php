<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SinergiEdu — Platform Manajemen Sekolah Terintegrasi</title>
    <meta name="description" content="Satu platform terintegrasi untuk membantu sekolah mengelola pembelajaran, penilaian, monitoring, dan komunikasi antar pihak sekolah dalam satu sistem yang aman dan terisolasi.">

    <!-- Canonical URL -->
    <link rel="canonical" href="https://sinergiedu.com/">

    <!-- Open Graph Tags -->
    <meta property="og:url" content="https://sinergiedu.com/">
    <meta property="og:title" content="SinergiEdu — Platform Manajemen Sekolah Terintegrasi">
    <meta property="og:description" content="Platform terintegrasi untuk membantu sekolah mengelola KBM, tugas, penilaian, monitoring, dan komunikasi antar pihak sekolah.">
    <meta property="og:type" content="website">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F5FAFF] font-['Outfit'] text-[#0B1733] antialiased selection:bg-[#123B82] selection:text-white">

    <!-- NAVBAR -->
    <header class="bg-white/95 backdrop-blur-md border-b border-[#DCE8F3] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-6">
            <!-- Brand Logo -->
            <a href="{{ route('landing') }}" class="flex items-center gap-3.5 group focus:outline-none focus:ring-2 focus:ring-[#123B82] rounded-xl p-1">
                <img src="{{ asset('images/logo.svg') }}?v={{ filemtime(public_path('images/logo.svg')) }}" alt="Logo SinergiEdu" class="h-9 sm:h-10 w-auto group-hover:scale-105 transition-transform duration-200">
                <span class="text-2xl font-extrabold tracking-tight text-[#0B1733]">Sinergi<span class="text-[#119FEA]">Edu</span></span>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-[#0B1733]">
                <a href="#platform" class="hover:text-[#123B82] transition-colors focus:outline-none focus:text-[#123B82]">Platform</a>
                <a href="#fitur" class="hover:text-[#123B82] transition-colors focus:outline-none focus:text-[#123B82]">Fitur</a>
                <a href="#peran" class="hover:text-[#123B82] transition-colors focus:outline-none focus:text-[#123B82]">Peran</a>
                <a href="#alur" class="hover:text-[#123B82] transition-colors focus:outline-none focus:text-[#123B82]">Alur Kerja</a>
            </nav>

            <!-- CTA Right -->
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-[#123B82] hover:bg-[#0F3170] active:scale-[0.98] rounded-xl transition shadow-sm focus:outline-none focus:ring-2 focus:ring-[#123B82]">
                        Ke Dashboard &rarr;
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-[#123B82] hover:bg-[#0F3170] active:scale-[0.98] rounded-xl transition shadow-sm focus:outline-none focus:ring-2 focus:ring-[#123B82]">
                        Masuk ke Sistem
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main id="platform">
        <!-- 1. HERO SECTION -->
        <section class="relative py-16 sm:py-24 lg:py-28 bg-[#F5FAFF] overflow-hidden">
            <!-- Subtle Brand Blue Blur Circles -->
            <div class="absolute top-0 right-1/4 w-[500px] h-[500px] bg-[#EAF6FF]/80 rounded-full blur-3xl pointer-events-none -z-10"></div>
            <div class="absolute bottom-10 left-10 w-[400px] h-[400px] bg-[#119FEA]/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-10 items-center">
                    
                    <!-- HERO LEFT -->
                    <div class="lg:col-span-6 space-y-6 sm:space-y-7 text-center lg:text-left">
                        <!-- 1. EYEBROW -->
                        <x-eyebrow variant="light">
                            PLATFORM MANAJEMEN SEKOLAH
                        </x-eyebrow>

                        <!-- 2. HEADLINE UTAMA -->
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#0B1733] tracking-tight leading-[1.1]">
                            Monitoring Hasil Belajar Siswa <br class="hidden sm:block">
                            <span class="bg-gradient-to-r from-[#119FEA] to-[#123B82] bg-clip-text text-transparent">Lebih Sinergis &amp; Terintegrasi</span>
                        </h1>

                        <!-- 3. TAGLINE UTAMA -->
                        <p class="text-xl sm:text-2xl font-bold tracking-tight text-[#123B82] leading-snug">
                            Peduli Prosesnya, <span class="text-[#119FEA]">Tumbuh Hasilnya.</span>
                        </p>

                        <!-- 4. SUPPORTING DESCRIPTION -->
                        <p class="text-base sm:text-lg text-[#64748B] font-normal leading-relaxed max-w-xl mx-auto lg:mx-0">
                            Pemantauan dan Dukungan Kolaboratif untuk Hasil Belajar
                        </p>

                        <!-- 5. HUMAN BRAND STATEMENT (EDITORIAL QUOTE FOCAL POINT) -->
                        <div class="pt-2 sm:pt-3">
                            <div class="relative pl-4 sm:pl-5 border-l-3 border-[#119FEA] text-left max-w-xl mx-auto lg:mx-0">
                                <blockquote class="text-lg sm:text-xl lg:text-2xl font-bold text-[#123B82] tracking-tight leading-[1.3]">
                                    &ldquo;Dengan <span class="text-[#119FEA]">keikhlasan diri</span> membangun pendidikan,<br>
                                    dengan <span class="text-[#119FEA]">kepedulian</span> menumbuhkan masa depan.&rdquo;
                                </blockquote>
                            </div>
                        </div>
                    </div>

                    <!-- HERO RIGHT (PRODUCT PREVIEW MOCKUP) -->
                    <div class="lg:col-span-6 relative min-w-0">
                        <div class="bg-[#0B1733] border border-[#123B82]/50 rounded-3xl p-5 sm:p-7 shadow-2xl space-y-5 text-white relative">
                            <!-- Window Header Dots -->
                            <div class="flex items-center justify-between pb-3 border-b border-[#123B82]/40">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-[#119FEA] inline-block"></span>
                                    <span class="w-3 h-3 rounded-full bg-[#123B82] inline-block"></span>
                                    <span class="w-3 h-3 rounded-full bg-[#EAF6FF]/40 inline-block"></span>
                                    <span class="text-xs font-mono text-[#D7E4F3] ml-2">sinergiedu.id / dashboard</span>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full bg-[#119FEA]/20 text-[#119FEA] text-[11px] font-bold">Live Monitoring</span>
                            </div>

                            <!-- Overview Stats Grid -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-[#123B82]/40 border border-[#123B82]/60 p-4 rounded-2xl">
                                    <span class="text-xs text-[#D7E4F3] font-medium">Rata-rata Nilai Siswa</span>
                                    <div class="text-3xl font-extrabold text-white mt-1">85.4</div>
                                    <span class="text-[11px] font-semibold text-[#119FEA] mt-1 inline-block">↑ Ter-rekonsiliasi</span>
                                </div>
                                <div class="bg-[#123B82]/40 border border-[#123B82]/60 p-4 rounded-2xl">
                                    <span class="text-xs text-[#D7E4F3] font-medium">Ketuntasan KBM</span>
                                    <div class="text-3xl font-extrabold text-white mt-1">92%</div>
                                    <span class="text-[11px] font-semibold text-[#119FEA] mt-1 inline-block">Target Semester</span>
                                </div>
                            </div>

                            <!-- Student Progress Visualization Bar -->
                            <div class="bg-[#123B82]/40 border border-[#123B82]/60 p-4 rounded-2xl space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-semibold text-slate-200">Pengumpulan Tugas &amp; Evaluasi</span>
                                    <span class="font-bold text-[#119FEA]">98.5% Selesai</span>
                                </div>
                                <div class="w-full bg-[#0B1733] rounded-full h-2.5">
                                    <div class="bg-gradient-to-r from-[#119FEA] to-[#123B82] h-2.5 rounded-full" style="width: 95%"></div>
                                </div>
                            </div>

                            <!-- Mock Real-time Log Rows -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between p-3 bg-[#123B82]/30 rounded-xl text-xs border border-[#123B82]/40">
                                    <div class="flex items-center gap-2 truncate">
                                        <span class="w-2 h-2 rounded-full bg-[#119FEA] shrink-0"></span>
                                        <span class="text-[#D7E4F3] font-medium truncate">Matematika — Evaluasi Pertemuan 4</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-[#119FEA] shrink-0">TERSUBMIT</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-[#123B82]/30 rounded-xl text-xs border border-[#123B82]/40">
                                    <div class="flex items-center gap-2 truncate">
                                        <span class="w-2 h-2 rounded-full bg-[#119FEA] shrink-0"></span>
                                        <span class="text-[#D7E4F3] font-medium truncate">Laporan Supervisi Akademik Guru</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-[#119FEA] shrink-0">TERVERIFIKASI</span>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Overlay Card -->
                        <div class="hidden sm:flex absolute -bottom-6 -left-6 bg-white border border-[#DCE8F3] p-4 rounded-2xl shadow-xl items-center gap-4 text-[#0B1733] z-10">
                            <div class="w-12 h-12 rounded-xl bg-[#EAF6FF] text-[#123B82] flex items-center justify-center font-extrabold text-lg shrink-0">
                                8
                            </div>
                            <div>
                                <div class="text-sm font-extrabold text-[#0B1733]">Hak Akses Peran</div>
                                <div class="text-xs text-[#64748B]">Admin, Kepsek, Guru, Siswa, dll.</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- 2. VALUE STRIP (MONOCHROMATIC BRAND STRIP) -->
        <section class="py-8 bg-white border-y border-[#DCE8F3]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 divide-y md:divide-y-0 md:divide-x divide-[#DCE8F3] text-center">
                    
                    <div class="pt-2 md:pt-0 px-4">
                        <span class="text-xs font-extrabold text-[#123B82] uppercase tracking-wider block mb-1">AKADEMIK TERINTEGRASI</span>
                        <p class="text-xs sm:text-sm text-[#64748B]">Tahun ajaran, semester, mapel, &amp; kelas terpusat.</p>
                    </div>

                    <div class="pt-4 md:pt-0 px-4">
                        <span class="text-xs font-extrabold text-[#123B82] uppercase tracking-wider block mb-1">MONITORING REAL-TIME</span>
                        <p class="text-xs sm:text-sm text-[#64748B]">Pantau progres nilai &amp; tugas transparan.</p>
                    </div>

                    <div class="pt-4 md:pt-0 px-4">
                        <span class="text-xs font-extrabold text-[#123B82] uppercase tracking-wider block mb-1">MULTI-ROLE ACCESS</span>
                        <p class="text-xs sm:text-sm text-[#64748B]">8 peran tersinkronisasi dalam 1 portal.</p>
                    </div>

                    <div class="pt-4 md:pt-0 px-4">
                        <span class="text-xs font-extrabold text-[#123B82] uppercase tracking-wider block mb-1">DATA TERISOLASI</span>
                        <p class="text-xs sm:text-sm text-[#64748B]">Proteksi data multi-tenant antar-sekolah.</p>
                    </div>

                </div>
            </div>
        </section>

        <!-- 3. FEATURE SHOWCASE -->
        <section id="fitur" class="py-20 sm:py-28 bg-[#F5FAFF]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-20">
                
                <div class="text-center max-w-3xl mx-auto space-y-3">
                    <span class="text-xs font-extrabold tracking-widest text-[#119FEA] uppercase">KAPABILITAS SISTEM</span>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#0B1733] tracking-tight">Semua Kebutuhan Sekolah Dalam Satu Platform</h2>
                    <p class="text-base text-[#64748B]">SinergiEdu dirancang khusus untuk memfasilitasi seluruh rantai kegiatan KBM, evaluasi, hingga supervisi.</p>
                </div>

                <!-- FEATURE GROUP 01 -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center pt-4">
                    <div class="lg:col-span-5 space-y-4">
                        <span class="text-4xl font-extrabold text-[#119FEA] font-mono">01</span>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-[#123B82] tracking-tight">Manajemen Akademik</h3>
                        <p class="text-base text-[#64748B] leading-relaxed">
                            Pengelolaan master data sekolah yang fleksibel dan terstruktur. Mengatur tahun ajaran aktif, semester berjalan, mata pelajaran, penempatan kelas, hingga tautan akun pengguna antar-role.
                        </p>
                        <ul class="space-y-2 text-sm text-[#0B1733] font-medium pt-2">
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#119FEA]"></span>
                                Pengaturan Tahun Ajaran &amp; Semester Aktif
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#119FEA]"></span>
                                Pemetaan Mata Pelajaran &amp; Penugasan Guru
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#119FEA]"></span>
                                Pengelompokan Rombongan Belajar / Kelas
                            </li>
                        </ul>
                    </div>
                    <div class="lg:col-span-7 bg-white border border-[#DCE8F3] rounded-3xl p-4 sm:p-6 shadow-sm">
                        <div class="bg-[#0B1733] text-white rounded-2xl p-3.5 sm:p-5 space-y-3.5 sm:space-y-4 font-sans">
                            <div class="flex items-center justify-between pb-3 border-b border-[#123B82]/50 text-xs text-[#D7E4F3]">
                                <span class="font-medium truncate pr-2">Master Data Sekolah</span>
                                <span class="text-[#119FEA] font-bold shrink-0">TP 2026/2027 — Ganjil</span>
                            </div>
                            <div class="grid grid-cols-3 gap-2 sm:gap-3">
                                <div class="p-2 sm:p-3 bg-[#123B82]/40 rounded-xl border border-[#123B82]/50 flex flex-col justify-between">
                                    <span class="text-[9px] sm:text-[10px] md:text-xs text-[#D7E4F3] uppercase font-bold tracking-tight leading-tight block">Mata Pelajaran</span>
                                    <div class="mt-2 sm:mt-2.5">
                                        <div class="text-base sm:text-xl font-extrabold text-white leading-none">14</div>
                                        <div class="text-[10px] sm:text-xs text-[#D7E4F3] font-medium mt-0.5 sm:mt-1 leading-tight">Mapel</div>
                                    </div>
                                </div>
                                <div class="p-2 sm:p-3 bg-[#123B82]/40 rounded-xl border border-[#123B82]/50 flex flex-col justify-between">
                                    <span class="text-[9px] sm:text-[10px] md:text-xs text-[#D7E4F3] uppercase font-bold tracking-tight leading-tight block">Rombongan Kelas</span>
                                    <div class="mt-2 sm:mt-2.5">
                                        <div class="text-base sm:text-xl font-extrabold text-white leading-none">12</div>
                                        <div class="text-[10px] sm:text-xs text-[#D7E4F3] font-medium mt-0.5 sm:mt-1 leading-tight">Kelas</div>
                                    </div>
                                </div>
                                <div class="p-2 sm:p-3 bg-[#123B82]/40 rounded-xl border border-[#123B82]/50 flex flex-col justify-between">
                                    <span class="text-[9px] sm:text-[10px] md:text-xs text-[#D7E4F3] uppercase font-bold tracking-tight leading-tight block">Guru Pengampu</span>
                                    <div class="mt-2 sm:mt-2.5">
                                        <div class="text-base sm:text-xl font-extrabold text-white leading-none">32</div>
                                        <div class="text-[10px] sm:text-xs text-[#D7E4F3] font-medium mt-0.5 sm:mt-1 leading-tight">Pengajar</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FEATURE GROUP 02 -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center pt-8">
                    <div class="lg:col-span-7 lg:order-1 order-2 bg-white border border-[#DCE8F3] rounded-3xl p-6 shadow-sm">
                        <div class="bg-[#0B1733] text-white rounded-2xl p-5 space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-[#123B82]/50 text-xs text-[#D7E4F3]">
                                <span>Portal KBM &amp; Assessments</span>
                                <span class="text-[#119FEA] font-bold">Status: Aktif</span>
                            </div>
                            <div class="space-y-3">
                                <div class="p-3 bg-[#123B82]/40 rounded-xl flex items-center justify-between text-xs border border-[#123B82]/50">
                                    <div>
                                        <div class="font-bold text-white">Pertemuan &amp; Pengumpulan Berkas</div>
                                        <div class="text-[#D7E4F3]">Materi PDF, Video, &amp; Tugas Mandiri</div>
                                    </div>
                                    <span class="px-2 py-1 bg-[#119FEA]/20 text-[#119FEA] rounded font-bold">Lengkap</span>
                                </div>
                                <div class="p-3 bg-[#123B82]/40 rounded-xl flex items-center justify-between text-xs border border-[#123B82]/50">
                                    <div>
                                        <div class="font-bold text-white">Komponen Penilaian Kompleks</div>
                                        <div class="text-[#D7E4F3]">Pre-test, Post-test, Karakter, Hafalan</div>
                                    </div>
                                    <span class="px-2 py-1 bg-[#119FEA]/20 text-[#119FEA] rounded font-bold">Terstruktur</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-5 lg:order-2 order-1 space-y-4">
                        <span class="text-4xl font-extrabold text-[#119FEA] font-mono">02</span>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-[#123B82] tracking-tight">Pembelajaran &amp; Penilaian</h3>
                        <p class="text-base text-[#64748B] leading-relaxed">
                            Mendukung penyampaian KBM secara digital. Guru dapat mengunggah materi, membuat pertemuan, dan menerima pengumpulan tugas berkas secara privat. Penilaian mencakup nilai akademis hingga aspek karakter siswa.
                        </p>
                        <ul class="space-y-2 text-sm text-[#0B1733] font-medium pt-2">
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#119FEA]"></span>
                                Pengumpulan &amp; Pemeriksaan Berkas Tugas Online
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#119FEA]"></span>
                                Penilaian Pre-test, Post-test, &amp; Nilai Karakter
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#119FEA]"></span>
                                Portofolio Perkembangan Nilai Siswa
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- FEATURE GROUP 03 -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center pt-8">
                    <div class="lg:col-span-5 space-y-4">
                        <span class="text-4xl font-extrabold text-[#119FEA] font-mono">03</span>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-[#123B82] tracking-tight">Monitoring &amp; Supervisi</h3>
                        <p class="text-base text-[#64748B] leading-relaxed">
                            Transparansi pemantauan bagi pimpinan sekolah dan pengawas. Kepala Sekolah dan Waka dapat meninjau kinerja guru, Pengawas memantau sekolah binaan, dan Komite menyampaikan aspirasi secara terpusat.
                        </p>
                        <ul class="space-y-2 text-sm text-[#0B1733] font-medium pt-2">
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#119FEA]"></span>
                                Dashboard Monitoring Kepala Sekolah &amp; Waka
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#119FEA]"></span>
                                Akses Pengawasan Multi-School untuk Pengawas
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#119FEA]"></span>
                                Kanal Aspirasi Resmi Komite Sekolah
                            </li>
                        </ul>
                    </div>
                    <div class="lg:col-span-7 bg-white border border-[#DCE8F3] rounded-3xl p-6 shadow-sm">
                        <div class="bg-[#0B1733] text-white rounded-2xl p-5 space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-[#123B82]/50 text-xs text-[#D7E4F3]">
                                <span>Supervisi &amp; Transparansi Rekap</span>
                                <span class="text-[#119FEA] font-bold">Ekspor PDF / Excel</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-xs">
                                <div class="p-3 bg-[#123B82]/40 rounded-xl border border-[#123B82]/50">
                                    <div class="font-bold text-white mb-1">Rekap Kinerja Guru</div>
                                    <div class="text-[#D7E4F3]">Kelengkapan Jurnal KBM</div>
                                </div>
                                <div class="p-3 bg-[#123B82]/40 rounded-xl border border-[#123B82]/50">
                                    <div class="font-bold text-white mb-1">Aspirasi Komite</div>
                                    <div class="text-[#D7E4F3]">Masukan Mutu Sekolah</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- 4. ROLE ECOSYSTEM -->
        <section id="peran" class="py-20 sm:py-28 bg-white border-t border-[#DCE8F3]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
                
                <div class="text-center max-w-3xl mx-auto space-y-3">
                    <span class="text-xs font-extrabold tracking-widest text-[#119FEA] uppercase">EKOSISTEM PENGGUNA</span>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#0B1733] tracking-tight">Terhubung Untuk Seluruh Pihak Sekolah</h2>
                    <p class="text-base text-[#64748B]">Delapan peran khusus saling terhubung dalam alur kewenangan yang jelas dan aman.</p>
                </div>

                <!-- ECOSYSTEM DIAGRAM COMPOSITION -->
                <div class="bg-[#F5FAFF] border border-[#DCE8F3] rounded-3xl p-8 lg:p-12 space-y-10">
                    
                    <!-- Core School Management Tier -->
                    <div class="space-y-4 text-center">
                        <span class="text-xs font-bold text-[#123B82] tracking-widest uppercase">INTI MANAJEMEN SEKOLAH</span>
                        <div class="flex flex-wrap items-center justify-center gap-4">
                            <div class="bg-white border border-[#DCE8F3] px-5 py-3.5 rounded-2xl shadow-xs text-left hover:border-[#119FEA] transition">
                                <div class="font-extrabold text-[#123B82] text-sm">Admin Sekolah</div>
                                <div class="text-xs text-[#64748B]">Kelola master data &amp; pengguna</div>
                            </div>
                            <div class="bg-white border border-[#DCE8F3] px-5 py-3.5 rounded-2xl shadow-xs text-left hover:border-[#119FEA] transition">
                                <div class="font-extrabold text-[#123B82] text-sm">Waka Kurikulum</div>
                                <div class="text-xs text-[#64748B]">Monitoring jadwal &amp; KBM guru</div>
                            </div>
                        </div>
                    </div>

                    <!-- Visual Connector Line -->
                    <div class="flex items-center justify-center">
                        <div class="w-0.5 h-8 bg-[#119FEA]"></div>
                    </div>

                    <!-- KBM & Learning Tier -->
                    <div class="space-y-4 text-center">
                        <span class="text-xs font-bold text-[#123B82] tracking-widest uppercase">PELAKSANAAN KBM &amp; PEMBELAJARAN</span>
                        <div class="flex flex-wrap items-center justify-center gap-4">
                            <div class="bg-white border border-[#DCE8F3] px-5 py-3.5 rounded-2xl shadow-xs text-left hover:border-[#119FEA] transition">
                                <div class="font-extrabold text-[#123B82] text-sm">Guru Pengampu</div>
                                <div class="text-xs text-[#64748B]">Materi, tugas, &amp; nilai</div>
                            </div>
                            <div class="bg-white border border-[#DCE8F3] px-5 py-3.5 rounded-2xl shadow-xs text-left hover:border-[#119FEA] transition">
                                <div class="font-extrabold text-[#123B82] text-sm">Siswa</div>
                                <div class="text-xs text-[#64748B]">Tugas &amp; capaian belajar</div>
                            </div>
                            <div class="bg-white border border-[#DCE8F3] px-5 py-3.5 rounded-2xl shadow-xs text-left hover:border-[#119FEA] transition">
                                <div class="font-extrabold text-[#123B82] text-sm">Orang Tua / Wali</div>
                                <div class="text-xs text-[#64748B]">Pantau perkembangan anak</div>
                            </div>
                        </div>
                    </div>

                    <!-- Visual Connector Line -->
                    <div class="flex items-center justify-center">
                        <div class="w-0.5 h-8 bg-[#119FEA]"></div>
                    </div>

                    <!-- Supervision & Governance Tier -->
                    <div class="space-y-4 text-center">
                        <span class="text-xs font-bold text-[#123B82] tracking-widest uppercase">SUPERVISI &amp; TATA KELOLA</span>
                        <div class="flex flex-wrap items-center justify-center gap-4">
                            <div class="bg-white border border-[#DCE8F3] px-5 py-3.5 rounded-2xl shadow-xs text-left hover:border-[#119FEA] transition">
                                <div class="font-extrabold text-[#123B82] text-sm">Kepala Sekolah</div>
                                <div class="text-xs text-[#64748B]">Evaluasi &amp; supervisi internal</div>
                            </div>
                            <div class="bg-white border border-[#DCE8F3] px-5 py-3.5 rounded-2xl shadow-xs text-left hover:border-[#119FEA] transition">
                                <div class="font-extrabold text-[#123B82] text-sm">Pengawas Multi-School</div>
                                <div class="text-xs text-[#64748B]">Monitoring sekolah binaan</div>
                            </div>
                            <div class="bg-white border border-[#DCE8F3] px-5 py-3.5 rounded-2xl shadow-xs text-left hover:border-[#119FEA] transition">
                                <div class="font-extrabold text-[#123B82] text-sm">Komite Sekolah</div>
                                <div class="text-xs text-[#64748B]">Aspirasi &amp; sarana sekolah</div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- 5. HOW IT WORKS (WORKFLOW - DARK NAVY SECTION) -->
        <section id="alur" class="py-20 sm:py-28 bg-[#0B1733] text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
                
                <div class="text-center max-w-3xl mx-auto space-y-3">
                    <span class="text-xs font-extrabold tracking-widest text-[#119FEA] uppercase">ALUR KERJA PLATFORM</span>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight">Bagaimana SinergiEdu Bekerja?</h2>
                    <p class="text-base text-[#D7E4F3]">Proses pencatatan hasil belajar terhubung dalam 3 tahapan utama.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                    <!-- Step 01 -->
                    <div class="bg-[#123B82] border border-[#DCE8F3]/15 p-8 rounded-3xl space-y-4">
                        <span class="text-3xl font-extrabold text-[#119FEA] font-mono">01</span>
                        <h3 class="text-xl font-extrabold text-white">SETUP</h3>
                        <p class="text-sm text-[#D7E4F3] leading-relaxed">
                            Sekolah menyiapkan data akademik, kelas, guru pengampu, serta mendaftarkan akun pengguna.
                        </p>
                    </div>

                    <!-- Step 02 -->
                    <div class="bg-[#123B82] border border-[#DCE8F3]/15 p-8 rounded-3xl space-y-4">
                        <span class="text-3xl font-extrabold text-[#119FEA] font-mono">02</span>
                        <h3 class="text-xl font-extrabold text-white">PEMBELAJARAN</h3>
                        <p class="text-sm text-[#D7E4F3] leading-relaxed">
                            Guru menjalankan KBM, mengunggah materi, memberikan tugas, dan mengumpulkan jawaban berkas siswa.
                        </p>
                    </div>

                    <!-- Step 03 -->
                    <div class="bg-[#123B82] border border-[#DCE8F3]/15 p-8 rounded-3xl space-y-4">
                        <span class="text-3xl font-extrabold text-[#119FEA] font-mono">03</span>
                        <h3 class="text-xl font-extrabold text-white">MONITORING</h3>
                        <p class="text-sm text-[#D7E4F3] leading-relaxed">
                            Hasil pembelajaran dapat dipantau secara real-time oleh Orang Tua, Kepala Sekolah, Pengawas, dan Komite.
                        </p>
                    </div>
                </div>

            </div>
        </section>

        <!-- 6. SECURITY / TRUST SECTION -->
        <section class="py-20 sm:py-28 bg-[#F5FAFF]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white border border-[#DCE8F3] rounded-3xl p-8 lg:p-14 space-y-8">
                    
                    <div class="max-w-3xl space-y-3">
                        <span class="text-xs font-extrabold tracking-widest text-[#119FEA] uppercase">ARSITEKTUR TEKNIS</span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0B1733] tracking-tight">
                            Data Sekolah Tetap Berada Dalam Konteksnya
                        </h2>
                        <p class="text-base text-[#64748B] leading-relaxed">
                            SinergiEdu menggunakan pemisahan tenant dan role-based access control (RBAC) untuk memastikan akses data terisolasi sesuai sekolah dan kewenangan pengguna.
                        </p>
                    </div>

                    <!-- Abstract Flow Line -->
                    <div class="p-6 bg-[#0B1733] text-white rounded-2xl">
                        <div class="flex flex-wrap items-center justify-around gap-4 text-xs font-mono font-bold text-center">
                            <span>[ User Session ]</span>
                            <span class="text-[#119FEA]">&rarr;</span>
                            <span>[ Role Authorization ]</span>
                            <span class="text-[#119FEA]">&rarr;</span>
                            <span>[ School Tenant Context ]</span>
                            <span class="text-[#119FEA]">&rarr;</span>
                            <span class="text-[#119FEA]">[ Isolated Data ]</span>
                        </div>
                    </div>

                    <!-- 4 Technical Pillars -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pt-4 text-sm font-semibold text-[#0B1733]">
                        <div class="p-4 bg-[#EAF6FF] rounded-2xl border border-[#DCE8F3]">
                            <span class="block text-[#119FEA] font-extrabold text-xs uppercase mb-1">Pillar 1</span>
                            Tenant Isolation
                        </div>
                        <div class="p-4 bg-[#EAF6FF] rounded-2xl border border-[#DCE8F3]">
                            <span class="block text-[#119FEA] font-extrabold text-xs uppercase mb-1">Pillar 2</span>
                            Role-Based Access
                        </div>
                        <div class="p-4 bg-[#EAF6FF] rounded-2xl border border-[#DCE8F3]">
                            <span class="block text-[#119FEA] font-extrabold text-xs uppercase mb-1">Pillar 3</span>
                            Private Storage
                        </div>
                        <div class="p-4 bg-[#EAF6FF] rounded-2xl border border-[#DCE8F3]">
                            <span class="block text-[#119FEA] font-extrabold text-xs uppercase mb-1">Pillar 4</span>
                            Secure Auth Session
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- 7. VALUE / STATISTICS HIGHLIGHTS -->
        <section class="py-12 bg-white border-y border-[#DCE8F3]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                    <div>
                        <span class="text-3xl font-extrabold text-[#119FEA] block">1</span>
                        <span class="text-xs font-semibold text-[#123B82] uppercase tracking-wide">Platform Terpadu</span>
                    </div>
                    <div>
                        <span class="text-3xl font-extrabold text-[#119FEA] block">8</span>
                        <span class="text-xs font-semibold text-[#123B82] uppercase tracking-wide">Peran Sekolah</span>
                    </div>
                    <div>
                        <span class="text-3xl font-extrabold text-[#119FEA] block">Multi-Tenant</span>
                        <span class="text-xs font-semibold text-[#123B82] uppercase tracking-wide">Data Terisolasi</span>
                    </div>
                    <div>
                        <span class="text-3xl font-extrabold text-[#119FEA] block">Private</span>
                        <span class="text-xs font-semibold text-[#123B82] uppercase tracking-wide">Storage Berkas</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- 8. CTA SECTION (BRAND GRADIENT #119FEA → #123B82) -->
        <section class="py-20 sm:py-24 bg-gradient-to-r from-[#119FEA] to-[#123B82] text-white relative overflow-hidden">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6 relative z-10">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight">
                    Bangun Pengelolaan Sekolah yang Lebih Terintegrasi
                </h2>
                <p class="text-base sm:text-lg text-[#EAF6FF] max-w-2xl mx-auto font-normal leading-relaxed">
                    Satu platform untuk membantu sekolah mengelola pembelajaran, penilaian, monitoring, dan komunikasi antar pihak sekolah.
                </p>
            </div>
        </section>
    </main>

    <!-- FOOTER (DARK NAVY #0B1733) -->
    <footer class="bg-[#0B1733] text-[#AFC4DA] py-12 border-t border-[#123B82]/40 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pb-8 border-b border-[#123B82]/40">
                
                <!-- Col 1: Brand & Tagline -->
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.svg') }}?v={{ filemtime(public_path('images/logo.svg')) }}" alt="Logo SinergiEdu" class="h-8 w-auto">
                        <span class="text-xl font-extrabold text-white">Sinergi<span class="text-[#119FEA]">Edu</span></span>
                    </div>
                    <p class="text-xs text-[#AFC4DA] leading-relaxed">
                        Platform Manajemen Sekolah &amp; Monitoring Hasil Belajar Siswa Terintegrasi.
                    </p>
                </div>

                <!-- Col 2: Navigation Links -->
                <div class="space-y-2">
                    <span class="text-xs font-extrabold text-white uppercase tracking-wider block mb-2">Platform Navigation</span>
                    <ul class="space-y-1.5 text-xs text-[#D7E4F3]">
                        <li><a href="#platform" class="hover:text-[#119FEA] transition-colors">Platform</a></li>
                        <li><a href="#fitur" class="hover:text-[#119FEA] transition-colors">Fitur Utama</a></li>
                        <li><a href="#peran" class="hover:text-[#119FEA] transition-colors">Peran Sekolah</a></li>
                        <li><a href="#alur" class="hover:text-[#119FEA] transition-colors">Alur Kerja</a></li>
                    </ul>
                </div>

                <!-- Col 3: System Access -->
                <div class="space-y-2">
                    <span class="text-xs font-extrabold text-white uppercase tracking-wider block mb-2">Akses Portal</span>
                    <p class="text-xs text-[#AFC4DA]">Akses pintu masuk utama seluruh peran pengguna sekolah.</p>
                </div>

            </div>

            <!-- Bottom Copyright Bar -->
            <x-layouts.footer variant="dark" as="div" class="bg-transparent border-t-0 !py-0 !px-0" />
        </div>
    </footer>

</body>
</html>
