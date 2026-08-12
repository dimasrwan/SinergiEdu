<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SinergiEdu - Sistem Informasi Monitoring Hasil Belajar</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-['Outfit'] text-slate-800 antialiased min-h-screen flex flex-col">
    <header class="bg-white border-b border-slate-100 sticky top-0 z-40 backdrop-blur-md bg-white/95">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo SinergiEdu" class="h-8 w-auto">
                <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">SinergiEdu</span>
            </div>
            <div>
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition duration-150 shadow-sm shadow-blue-200">
                    Masuk ke Sistem
                </a>
            </div>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center py-20 px-4">
        <div class="max-w-4xl text-center space-y-8">
            <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight text-slate-900 leading-tight">
                Monitoring Hasil Belajar Siswa <br>
                <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Lebih Sinergis & Terintegrasi</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-600 max-w-2xl mx-auto font-light leading-relaxed">
                Platform pemantauan nilai akademik, kehadiran, dan laporan perkembangan siswa secara realtime untuk Guru, Siswa, Orang Tua, dan Manajemen Sekolah.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 text-base font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-2xl transition duration-150 shadow-lg shadow-blue-200">
                    Mulai Sekarang
                </a>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-slate-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-slate-500">
            &copy; {{ date('Y') }} SinergiEdu. All rights reserved.
        </div>
    </footer>
</body>
</html>
