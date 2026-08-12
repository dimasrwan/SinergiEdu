<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SinergiEdu') }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-slate-900 antialiased tracking-tight bg-slate-50">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Brand Identity / Pattern Area (Sisi Kiri) -->
        <div class="hidden md:flex flex-1 relative bg-[#123B82] flex-col justify-between overflow-hidden p-12 lg:p-20">
            <!-- Pola Geometris Abstrak -->
            <div class="absolute top-0 right-0 p-12 opacity-20 pointer-events-none transform translate-x-1/4 -translate-y-1/4">
                <svg width="400" height="400" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="40" stroke="#119FEA" stroke-width="2"/>
                    <path d="M50 10L90 50L50 90L10 50L50 10Z" stroke="#FFFFFF" stroke-width="2"/>
                    <circle cx="50" cy="50" r="20" fill="#119FEA"/>
                </svg>
            </div>
            
            <div class="absolute bottom-0 left-0 p-12 opacity-10 pointer-events-none transform -translate-x-1/4 translate-y-1/4">
                <svg width="500" height="500" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="45" stroke="#FFFFFF" stroke-width="1" stroke-dasharray="2 2"/>
                    <path d="M20 20L80 80M80 20L20 80" stroke="#119FEA" stroke-width="1"/>
                </svg>
            </div>

            <!-- Konten Merek Utama -->
            <div class="relative z-10 flex items-center gap-4">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center p-2 shadow-lg shadow-[#119FEA]/20">
                    <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <span class="text-3xl font-bold text-white tracking-tight">SinergiEdu</span>
            </div>

            <div class="relative z-10 mt-auto">
                <h1 class="text-4xl lg:text-5xl font-bold text-white leading-tight mb-4">
                    Pusat Ekosistem<br/>Pendidikan Modern.
                </h1>
                <p class="text-lg text-blue-100 max-w-md leading-relaxed">
                    Akses terpusat untuk memantau nilai, mengelola pembelajaran, dan menjalin sinergi antara guru, siswa, dan orang tua.
                </p>
                <div class="mt-8 flex items-center gap-2 text-blue-200/80 text-sm">
                    <span>&copy; {{ date('Y') }} SinergiEdu. Semua Hak Cipta Dilindungi.</span>
                </div>
            </div>
        </div>

        <!-- Form Area (Sisi Kanan) -->
        <div class="flex-1 flex flex-col justify-center px-6 py-12 lg:px-20 bg-white">
            <!-- Header Mobile (Hanya muncul jika di mobile) -->
            <div class="md:hidden flex flex-col items-center mb-8">
                <div class="w-16 h-16 bg-[#123B82] rounded-2xl flex items-center justify-center p-3 shadow-lg shadow-[#123B82]/20 mb-4">
                    <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="w-full h-full object-contain drop-shadow-md">
                </div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">SinergiEdu</h1>
            </div>

            <!-- Kontainer Slot -->
            <div class="w-full max-w-sm mx-auto">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
