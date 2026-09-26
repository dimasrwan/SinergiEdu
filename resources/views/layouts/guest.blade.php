@props(['title' => 'Masuk ke Sistem — SinergiEdu'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F5FAFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    <style>[x-cloak] { display: none !important; }</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-['Outfit'] text-[#0B1733] antialiased selection:bg-[#123B82] selection:text-white bg-[#F5FAFF]">
    <div class="min-h-screen flex flex-col md:flex-row">
        
        <!-- LEFT BRAND PANEL (Desktop 50% split) -->
        <div class="hidden md:flex md:w-1/2 relative bg-[#123B82] flex-col justify-between overflow-hidden p-10 lg:p-16 text-white">
            
            <!-- Geometric Decorative Elements (SinergiEdu Brand Inspired) -->
            <div class="absolute top-0 right-0 p-12 opacity-15 pointer-events-none transform translate-x-1/4 -translate-y-1/4">
                <svg width="450" height="450" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="20" y="20" width="60" height="60" rx="16" transform="rotate(45 50 50)" stroke="#119FEA" stroke-width="2.5"/>
                    <circle cx="50" cy="50" r="28" stroke="#FFFFFF" stroke-width="2"/>
                    <circle cx="50" cy="50" r="14" fill="#119FEA"/>
                </svg>
            </div>
            
            <div class="absolute bottom-0 left-0 p-12 opacity-10 pointer-events-none transform -translate-x-1/4 translate-y-1/4">
                <svg width="500" height="500" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="42" stroke="#FFFFFF" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <path d="M15 50 H85 M50 15 V85" stroke="#119FEA" stroke-width="1.5"/>
                </svg>
            </div>

            <!-- Header Brand Logo -->
            <a href="{{ route('landing') }}" class="relative z-10 inline-flex items-center gap-3.5 focus:outline-none focus:ring-2 focus:ring-[#119FEA] rounded-2xl p-1 group">
                <div class="h-12 sm:h-14 w-12 sm:w-14 bg-white rounded-2xl flex items-center justify-center p-2 sm:p-2.5 shadow-md shadow-[#0B1733]/30 transition-transform duration-200 group-hover:scale-105 shrink-0">
                    <img src="{{ asset('images/logo.svg') }}?v={{ filemtime(public_path('images/logo.svg')) }}" alt="Logo SinergiEdu" class="w-full h-full object-contain">
                </div>
                <span class="text-[26px] sm:text-[28px] font-extrabold text-white tracking-tight">Sinergi<span class="text-[#119FEA]">Edu</span></span>
            </a>

            <!-- Content Area -->
            <div class="relative z-10 my-auto py-8">
                <div class="inline-block text-xs font-bold tracking-[0.1em] uppercase text-[#119FEA] mb-6">
                    PLATFORM TERINTEGRASI
                </div>

                <h1 class="text-3xl lg:text-4xl font-extrabold text-white leading-tight mb-4 tracking-tight">
                    Platform Manajemen Sekolah Terintegrasi
                </h1>
                
                <p class="text-base text-[#EAF6FF] max-w-md leading-relaxed mb-8">
                    Kelola pembelajaran, penilaian, dan monitoring sekolah dalam satu platform terpusat.
                </p>

                <!-- 3 Micro Highlights -->
                <div class="space-y-3.5 text-sm font-semibold text-white">
                    <div class="flex items-center gap-3">
                        <span class="w-5 h-5 rounded-full bg-[#119FEA] flex items-center justify-center text-[#0B1733] text-xs font-extrabold shrink-0">✓</span>
                        <span>Manajemen Akademik Terpusat</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="w-5 h-5 rounded-full bg-[#119FEA] flex items-center justify-center text-[#0B1733] text-xs font-extrabold shrink-0">✓</span>
                        <span>Monitoring Real-Time Transparan</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="w-5 h-5 rounded-full bg-[#119FEA] flex items-center justify-center text-[#0B1733] text-xs font-extrabold shrink-0">✓</span>
                        <span>Hak Akses Multi-Role Terproteksi</span>
                    </div>
                </div>
            </div>

            <!-- Footer Note -->
            <x-layouts.footer variant="dark" as="div" class="relative z-10 !pt-4 !pb-0 !px-0 bg-transparent border-t-0 text-[#EAF6FF]/80" />
        </div>

        <!-- RIGHT FORM AREA (Desktop 50% split, Mobile compact) -->
        <div class="md:w-1/2 flex-1 flex flex-col justify-center px-6 py-12 lg:px-16 bg-white">
            
            <!-- Mobile Brand Header -->
            <div class="md:hidden flex flex-col items-center mb-8 text-center">
                <a href="{{ route('landing') }}" class="inline-flex items-center gap-2.5 mb-2 focus:outline-none">
                    <img src="{{ asset('images/logo.svg') }}?v={{ filemtime(public_path('images/logo.svg')) }}" alt="Logo SinergiEdu" class="h-9 sm:h-10 w-auto object-contain">
                    <span class="text-2xl font-extrabold text-[#0B1733] tracking-tight">Sinergi<span class="text-[#119FEA]">Edu</span></span>
                </a>
                <span class="text-xs font-semibold text-[#64748B]">Platform Manajemen Sekolah</span>
            </div>

            <!-- Form Container Slot -->
            <div class="w-full max-w-md mx-auto">
                {{ $slot }}
            </div>

            <!-- Mobile Footer Note -->
            <div class="mt-8 md:hidden">
                <x-layouts.footer as="div" class="bg-transparent border-t-0 !p-0" />
            </div>

        </div>

    </div>
</body>
</html>
