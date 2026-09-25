<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F5FAFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Akses Sementara Tidak Tersedia — SinergiEdu</title>

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
    <div class="min-h-screen flex flex-col justify-between py-8 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        
        <!-- Subtle Decorative Background Circles -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-[#119FEA]/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-[#123B82]/5 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Main Content Card Box -->
        <div class="my-auto max-w-lg w-full mx-auto relative z-10">
            <div class="bg-white border border-[#DCE8F3] shadow-lg shadow-[#123B82]/5 rounded-3xl p-8 sm:p-10 text-center">
                
                <!-- Brand Logo SinergiEdu -->
                <div class="flex items-center justify-center gap-3 mb-8">
                    <img src="{{ asset('images/logo.svg') }}?v={{ filemtime(public_path('images/logo.svg')) }}" alt="Logo SinergiEdu" class="h-10 sm:h-11 w-auto object-contain">
                    <span class="text-2xl sm:text-[26px] font-extrabold text-[#0B1733] tracking-tight">Sinergi<span class="text-[#119FEA]">Edu</span></span>
                </div>

                <!-- Status Icon Badge -->
                <div class="mx-auto w-16 h-16 rounded-2xl bg-[#EAF6FF] border border-[#DCE8F3] flex items-center justify-center text-[#123B82] mb-6 shadow-xs">
                    <svg class="w-8 h-8 text-[#123B82]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>

                <!-- Heading -->
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0B1733] tracking-tight mb-3">
                    Akses Sementara Tidak Tersedia
                </h1>

                <!-- Informative & Friendly Explanations -->
                <p class="text-sm sm:text-base text-[#475569] leading-relaxed mb-4">
                    Sekolah Anda saat ini sedang dalam status nonaktif, sehingga akses ke platform SinergiEdu sementara tidak tersedia.
                </p>

                <p class="text-xs sm:text-sm text-[#64748B] leading-relaxed mb-8 bg-[#F8FAFC] border border-[#E2E8F0] p-4 rounded-xl">
                    Silakan hubungi <strong class="text-[#0B1733] font-semibold">Administrator Sekolah</strong> untuk mendapatkan informasi lebih lanjut mengenai status akses akun Anda.
                </p>

                <!-- CTA Action Button -->
                <div class="space-y-3">
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3.5 text-sm font-bold text-white bg-[#123B82] hover:bg-[#0F3170] active:scale-[0.99] rounded-xl transition shadow-sm focus:outline-none focus:ring-2 focus:ring-[#123B82] cursor-pointer">
                                Kembali ke Halaman Login
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center px-6 py-3.5 text-sm font-bold text-white bg-[#123B82] hover:bg-[#0F3170] active:scale-[0.99] rounded-xl transition shadow-sm focus:outline-none focus:ring-2 focus:ring-[#123B82]">
                            Kembali ke Halaman Login
                        </a>
                    @endauth
                    
                    <div>
                        <a href="{{ route('landing') }}" class="inline-block text-xs font-semibold text-[#64748B] hover:text-[#123B82] transition-colors mt-2">
                            &larr; Menuju Beranda Utama
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Footer Note -->
        <div class="text-center text-xs text-[#94A3B8] relative z-10 mt-6">
            &copy; {{ date('Y') }} SinergiEdu. Hak Cipta Dilindungi.
        </div>

    </div>
</body>
</html>
