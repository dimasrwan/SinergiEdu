<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - SinergiEdu</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg?v=2') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function() {
            try {
                var theme = '{{ auth()->check() && auth()->user()->preferences ? auth()->user()->preferences->theme : 'system' }}';
                if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        })();
    </script>
</head>
<body class="h-full font-sans text-slate-800 antialiased tracking-tight" x-data="{ sidebarOpen: false }">
    <div>
        <!-- Off-canvas menu untuk mobile -->
        <div class="relative z-50 lg:hidden" role="dialog" aria-modal="true" x-show="sidebarOpen" x-description="Off-canvas menu overlay" style="display: none;">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-slate-900/80 transition-opacity duration-300 ease-linear-out" 
                 x-show="sidebarOpen"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false"></div>

            <div class="fixed inset-0 flex">
                <!-- Sidebar panel -->
                <div class="relative flex w-full max-w-xs flex-1 flex-col bg-white border-r border-slate-200 pt-5 pb-4 transition-transform duration-300 ease-in-out"
                     x-show="sidebarOpen"
                     x-transition:enter="transition ease-in-out duration-300 transform"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in-out duration-300 transform"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="-translate-x-full">
                    
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button type="button" class="ml-1 flex h-10 w-10 items-center justify-center rounded-lg focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="sidebarOpen = false">
                            <span class="sr-only">Tutup sidebar</span>
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" x-description="Heroicon name: outline/x-mark" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex flex-shrink-0 items-center gap-3 px-6">
                        <img src="{{ asset('images/logo.svg') }}" alt="Logo SinergiEdu" class="h-9 w-auto">
                        <span class="text-2xl font-bold text-slate-900 tracking-tight">SinergiEdu</span>
                    </div>
                    
                    <div class="mt-8 h-0 flex-1 overflow-y-auto px-4">
                        <nav class="space-y-1">
                            {{ $sidebar ?? '' }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Static sidebar untuk desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-40 lg:flex lg:w-[230px] lg:flex-col">
            <div class="flex flex-grow flex-col overflow-y-auto border-r border-slate-200 bg-white px-4 pb-1">
                <div class="flex h-[60px] flex-shrink-0 items-center gap-3">
                    <img src="{{ asset('images/logo.svg') }}" alt="Logo SinergiEdu" class="h-8 w-auto">
                    <span class="text-xl font-bold text-slate-900 tracking-tight">SinergiEdu</span>
                </div>
                <nav class="mt-4 flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-2">
                        <li>
                            <ul role="list" class="space-y-1">
                                @php
                                    $role = strtolower(Auth::user()->role->name ?? '');
                                @endphp
                                @if($role === 'super_admin')
                                    <x-sidebars.super-admin />
                                @elseif($role === 'admin')
                                    <x-sidebars.admin />
                                @elseif($role === 'guru')
                                    <x-sidebars.guru />
                                @elseif($role === 'siswa')
                                    <x-sidebars.siswa />
                                @elseif($role === 'orangtua')
                                    <x-sidebars.orangtua />
                                @elseif($role === 'waka')
                                    <x-sidebars.waka />
                                @elseif($role === 'pengawas')
                                    <x-sidebars.pengawas />
                                @elseif($role === 'kepala_sekolah')
                                    <x-sidebars.kepala-sekolah />
                                @else
                                    {{ $sidebar ?? '' }}
                                @endif
                            </ul>
                        </li>
                        @if(in_array(strtolower(Auth::user()->role->name ?? ''), ['admin', 'super_admin']))
                        <li class="mt-auto pt-4 pb-0">
                            <!-- Admin Subtle Footer -->
                            <div class="border-t border-slate-200/60 pt-3">
                                <p class="text-xs font-bold text-slate-700">SinergiEdu</p>
                                <p class="text-[11px] font-medium text-slate-400 mt-0.5">
                                    {{ strtolower(Auth::user()->role->name ?? '') === 'super_admin' ? 'Platform Console' : 'Admin Workspace' }}
                                </p>
                            </div>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Main column -->
        <div class="lg:pl-[230px] flex flex-col min-h-screen">
            <!-- Navbar -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-slate-200 bg-white px-4 sm:gap-x-6 sm:px-6 lg:px-8">
                <button type="button" class="-m-2.5 p-2.5 text-slate-700 lg:hidden" @click="sidebarOpen = true">
                    <span class="sr-only">Buka sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <div class="h-6 w-px bg-slate-200 lg:hidden" aria-hidden="true"></div>

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6 items-center justify-between">
                    <div class="flex items-center gap-x-3">
                        <!-- Desktop Logo/Brand -->
                        <div class="h-8 w-8 bg-primary rounded flex items-center justify-center lg:hidden">
                            <span class="text-white font-bold text-sm">S</span>
                        </div>
                        <h2 class="text-lg font-bold text-slate-900 tracking-tight leading-6">{{ $title ?? 'Dashboard' }}</h2>
                    </div>
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        @if(strtolower(Auth::user()->role->name ?? '') !== 'super_admin' && strtolower(Auth::user()->role->name ?? '') !== 'siswa')
                        <div class="hidden md:block relative" x-data="globalSearch()" @click.away="close()">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </div>
                            <input type="text" x-model="query" @input.debounce.500ms="fetchResults" @keydown.escape="close()" class="block w-64 rounded-lg border-0 py-1.5 pl-10 pr-4 text-slate-900 ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6 bg-slate-50" placeholder="Cari data...">
                            
                            <!-- Dropdown Results -->
                            <div x-show="open" x-transition.opacity class="absolute top-full mt-2 w-[400px] bg-white rounded-xl shadow-lg ring-1 ring-slate-900/5 py-2 z-50 lg:right-auto right-0 max-h-96 overflow-y-auto" style="display: none;">
                                <template x-if="loading">
                                    <div class="px-4 py-3 text-sm text-slate-500 text-center">Mencari...</div>
                                </template>
                                <template x-if="!loading && results.length === 0 && query.length >= 2">
                                    <div class="px-4 py-3 text-sm text-slate-500 text-center">Tidak ada hasil ditemukan.</div>
                                </template>
                                <template x-if="!loading && results.length > 0">
                                    <ul class="divide-y divide-slate-100">
                                        <template x-for="(result, index) in results" :key="index">
                                            <li>
                                                <a :href="result.url" class="block px-4 py-2.5 hover:bg-slate-50 transition-colors group">
                                                    <p class="text-[10px] font-bold uppercase tracking-wider text-primary mb-0.5" x-text="result.category"></p>
                                                    <p class="text-sm font-semibold text-slate-900 group-hover:text-primary transition-colors" x-text="result.title"></p>
                                                    <p class="text-xs text-slate-500 mt-0.5" x-text="result.subtitle"></p>
                                                </a>
                                            </li>
                                        </template>
                                    </ul>
                                </template>
                            </div>
                        </div>
                        @endif

                        <!-- Divider -->
                        <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-slate-200" aria-hidden="true"></div>

                        <!-- Dropdown Menu / Profile -->
                        <div class="relative" x-data="{ open: false }">
                            <button type="button" class="-m-1.5 flex items-center p-1.5 gap-x-3" @click="open = !open">
                                <span class="sr-only">Buka menu user</span>
                                <x-avatar :user="Auth::user()" size="h-8 w-8" textSize="text-sm" />
                                <div class="hidden lg:flex lg:items-center">
                                    <span class="text-sm font-semibold leading-6 text-slate-900" aria-hidden="true">{{ Auth::user()->name ?? 'Guest' }}</span>
                                    <svg class="ml-2 h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                            <!-- Dropdown panel -->
                            <div class="absolute right-0 z-10 mt-2.5 w-56 origin-top-right rounded-xl bg-white py-1 shadow-lg ring-1 ring-slate-900/5 focus:outline-none"
                                 x-show="open"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 @click.away="open = false"
                                 style="display: none;">
                                @if(in_array(strtolower(Auth::user()->role->name ?? ''), ['admin', 'super_admin']))
                                <div class="px-4 py-3 border-b border-slate-100 mb-1">
                                    <p class="text-sm font-semibold text-slate-900 truncate">{{ Auth::user()->name ?? 'Guest' }}</p>
                                    <p class="text-xs text-slate-500 truncate uppercase tracking-widest font-bold mt-0.5">{{ Auth::user()->role->display_name ?? Auth::user()->role->name ?? 'Admin' }}</p>
                                </div>
                                @endif
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Profil</a>
                                <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Pengaturan</a>
                                <div class="border-t border-slate-100 my-1"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">Keluar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <main class="py-10 flex-1">
                <div class="px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-slate-200/50 py-6 mt-auto">
                <div class="px-4 sm:px-6 lg:px-8 flex items-center justify-between text-sm text-slate-500">
                    <p>&copy; {{ date('Y') }} SinergiEdu. Semua Hak Cipta Dilindungi.</p>
                </div>
            </footer>
        </div>
    </div>
    
    @stack('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('globalSearch', () => ({
                query: '',
                results: [],
                loading: false,
                open: false,
                
                async fetchResults() {
                    if (this.query.length < 2) {
                        this.results = [];
                        this.open = false;
                        return;
                    }
                    
                    this.loading = true;
                    this.open = true;
                    
                    try {
                        const response = await fetch('/admin/search?q=' + encodeURIComponent(this.query));
                        if (response.ok) {
                            this.results = await response.json();
                        }
                    } catch (e) {
                        console.error('Search error', e);
                    } finally {
                        this.loading = false;
                    }
                },
                
                close() {
                    this.open = false;
                }
            }))
        });
    </script>
</body>
</html>
