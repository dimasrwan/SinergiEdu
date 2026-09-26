<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - SinergiEdu</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}?v=3">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }

        /* Desktop Push Grid with Independent Scrolling Containers */
        .app-body-shell {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            height: calc(100vh - 64px);
            height: calc(100dvh - 64px);
            overflow: hidden;
            transition: grid-template-columns 200ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        @media (min-width: 1024px) {
            .app-body-shell {
                grid-template-columns: 0px minmax(0, 1fr);
            }
            .app-body-shell.desktop-sidebar-open {
                grid-template-columns: 230px minmax(0, 1fr);
            }
        }

        /* Subtle Page Enter Transition (160ms ease-out) */
        @keyframes pageFadeIn {
            from {
                opacity: 0.96;
                transform: translateY(2px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-content-enter {
            animation: pageFadeIn 160ms cubic-bezier(0.4, 0, 0.2, 1) forwards;
            will-change: opacity, transform;
        }

        /* Accessibility: Reduced Motion Support */
        @media (prefers-reduced-motion: reduce) {
            .app-body-shell,
            .desktop-sidebar-pane,
            .main-column-pane,
            .page-content-enter,
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function() {
            try {
                // Theme pre-init
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
<body class="h-full overflow-hidden font-sans text-slate-800 antialiased tracking-tight bg-slate-50 flex flex-col"
      x-data="{
          sidebarOpen: false,
          sidebarDesktopOpen: (localStorage.getItem('desktop_sidebar_open') === 'true'),
          toggleSidebar() {
              if (window.innerWidth >= 1024) {
                  this.sidebarDesktopOpen = !this.sidebarDesktopOpen;
                  localStorage.setItem('desktop_sidebar_open', this.sidebarDesktopOpen ? 'true' : 'false');
                  setTimeout(() => { window.dispatchEvent(new Event('resize')); }, 220);
              } else {
                  this.sidebarOpen = !this.sidebarOpen;
              }
          }
      }"
      @keydown.escape.window="sidebarOpen = false">

    <!-- 1. TOP GLOBAL HEADER (Fixed Stationary, h-16, w-full, z-40, shrink-0) -->
    <header class="sticky top-0 z-40 flex h-16 w-full shrink-0 items-center justify-between border-b border-slate-200 bg-white px-4 sm:px-6 lg:px-8 shadow-2xs">
        <!-- LEFT GROUP: [ ☰ ] [ LOGO SINERGIEDU ] │ [ PAGE TITLE ] -->
        <div class="flex items-center gap-x-3 sm:gap-x-3.5 min-w-0 flex-1">
            <!-- Global Hamburger Button (Toggles Desktop Push Sidebar / Mobile Off-canvas Drawer) -->
            <button type="button"
                    class="inline-flex items-center justify-center h-10 w-10 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 active:scale-95 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all shrink-0 cursor-pointer"
                    @click="toggleSidebar()"
                    title="Menu Navigasi"
                    aria-label="Buka atau tutup menu navigasi"
                    :aria-expanded="window.innerWidth >= 1024 ? sidebarDesktopOpen : sidebarOpen">
                <span class="sr-only">Toggle navigation</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            <!-- Brand Logo SinergiEdu (Positioned directly beside Hamburger at x=0) -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 shrink-0" title="SinergiEdu">
                <img src="{{ asset('images/logo.svg') }}?v=3" alt="Logo SinergiEdu" class="h-7 sm:h-8 w-auto object-contain shrink-0">
            </a>

            <!-- Divider -->
            <div class="h-6 w-px bg-slate-200 shrink-0" aria-hidden="true"></div>

            <!-- Page Title & Pengawas School Selector -->
            <div class="flex items-center gap-x-3 min-w-0 flex-1">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight leading-6 min-w-0 truncate">{{ $title ?? 'Dashboard' }}</h2>

                @if(strtolower(Auth::user()->role->name ?? '') === 'pengawas')
                    @php
                        $activePengawasSchoolId = session('pengawas_school_id');
                        $activePengawasSchool = $activePengawasSchoolId ? \App\Models\School::find($activePengawasSchoolId) : null;
                        $assignedPengawasSchools = Auth::user()->assignedSchools()->where('is_active', true)->orderBy('name')->get();
                    @endphp
                    <div class="relative shrink-0" x-data="{ openSchoolDropdown: false }">
                        <button type="button" @click="openSchoolDropdown = !openSchoolDropdown" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-blue-50 border border-blue-200 text-xs font-semibold text-primary hover:bg-blue-100 transition-colors">
                            <svg class="w-3.5 h-3.5 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="max-w-[130px] sm:max-w-[200px] truncate">{{ $activePengawasSchool?->name ?? 'Pilih Sekolah' }}</span>
                            <svg class="w-3.5 h-3.5 text-primary/70 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <div x-show="openSchoolDropdown" @click.away="openSchoolDropdown = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="absolute left-0 sm:left-auto sm:right-0 mt-2 w-64 bg-white rounded-xl shadow-xl ring-1 ring-slate-900/10 py-2 z-50 overflow-hidden" style="display: none;">
                            <div class="px-3 py-1.5 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                Sekolah Pengawasan
                            </div>
                            <div class="max-h-56 overflow-y-auto py-1">
                                @foreach($assignedPengawasSchools as $pSchool)
                                    <form action="{{ route('pengawas.set-school') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="school_id" value="{{ $pSchool->id }}">
                                        <button type="submit" class="w-full text-left px-3 py-2 text-xs flex items-center justify-between hover:bg-slate-50 transition-colors {{ $activePengawasSchoolId == $pSchool->id ? 'font-bold text-primary bg-blue-50/50' : 'text-slate-700' }}">
                                            <span class="truncate">{{ $pSchool->name }}</span>
                                            @if($activePengawasSchoolId == $pSchool->id)
                                                <svg class="w-3.5 h-3.5 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                            <div class="border-t border-slate-100 pt-1 mt-1 px-1">
                                <a href="{{ route('pengawas.select-school') }}" class="block px-3 py-1.5 text-center text-xs font-semibold text-primary hover:bg-blue-50 rounded-lg transition-colors">
                                    Kelola Pemilihan Sekolah
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- RIGHT GROUP: [ SEARCH ] │ [ USER PROFILE ] -->
        <div class="flex items-center gap-x-3 sm:gap-x-4 shrink-0">
            <!-- Global Search -->
            <div class="hidden md:block relative" x-data="globalSearch()" @click.away="close()">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" x-model="query" @input.debounce.500ms="fetchResults" @keydown.escape="close()" class="block w-40 sm:w-52 xl:w-60 rounded-lg border-0 py-1.5 pl-9 pr-3 text-slate-900 ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6 bg-slate-50" placeholder="Cari data...">

                <!-- Dropdown Results -->
                <div x-show="open" x-transition.opacity class="absolute top-full mt-2 w-[400px] bg-white rounded-xl shadow-lg ring-1 ring-slate-900/5 py-2 z-50 right-0 max-h-96 overflow-y-auto" style="display: none;">
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

            <!-- Divider -->
            <div class="hidden md:block h-6 w-px bg-slate-200" aria-hidden="true"></div>

            <!-- User Profile Dropdown -->
            <div class="relative shrink-0" x-data="{ open: false }">
                <button type="button" class="-m-1.5 flex items-center p-1.5 gap-x-2.5 rounded-lg hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-200 cursor-pointer" @click="open = !open">
                    <span class="sr-only">Buka menu user</span>
                    <x-avatar :user="Auth::user()" size="h-8 w-8" textSize="text-sm" />
                    <div class="hidden xl:flex xl:items-center">
                        <span class="text-sm font-semibold leading-6 text-slate-900" aria-hidden="true">{{ Auth::user()->name ?? 'Guest' }}</span>
                        <svg class="ml-1.5 h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
                <!-- Dropdown panel -->
                <div class="absolute right-0 z-50 mt-2.5 w-56 origin-top-right rounded-xl bg-white py-1 shadow-lg ring-1 ring-slate-900/5 focus:outline-none"
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
    </header>

    <!-- 2. OFF-CANVAS MOBILE DRAWER (<1024px) -->
    <div class="relative z-50 lg:hidden" role="dialog" aria-modal="true" x-show="sidebarOpen" x-cloak>
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity duration-300 ease-linear"
             x-show="sidebarOpen"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"></div>

        <div class="fixed inset-y-0 left-0 flex" @click="sidebarOpen = false">
            <aside class="relative flex w-[85vw] max-w-[300px] flex-col bg-white border-r border-slate-200/80 shadow-2xl transition-transform duration-300 ease-in-out h-full"
                   @click.stop
                   x-show="sidebarOpen"
                   x-transition:enter="transition ease-in-out duration-300 transform"
                   x-transition:enter-start="-translate-x-full"
                   x-transition:enter-end="translate-x-0"
                   x-transition:leave="transition ease-in-out duration-300 transform"
                   x-transition:leave-start="translate-x-0"
                   x-transition:leave-end="-translate-x-full">

                <!-- Drawer Header -->
                <div class="flex items-center justify-between px-4 py-3.5 border-b border-slate-100 shrink-0 bg-white">
                    <div class="flex items-center gap-2.5">
                        <img src="{{ asset('images/logo.svg') }}?v=3" alt="Logo SinergiEdu" class="h-7 w-auto object-contain shrink-0">
                        <span class="text-lg font-bold text-slate-900 tracking-tight">SinergiEdu</span>
                    </div>
                    <button type="button"
                            class="h-9 w-9 inline-flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 active:scale-95 transition-all focus:outline-none focus:ring-2 focus:ring-primary/20 cursor-pointer"
                            aria-label="Tutup menu navigasi"
                            @click="sidebarOpen = false">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Drawer Navigation Content -->
                <div class="flex-1 overflow-y-auto px-3.5 py-4">
                    <nav class="space-y-1.5">
                        @if(isset($sidebar) && trim((string)$sidebar) !== '')
                            {{ $sidebar }}
                        @else
                            <ul role="list" class="space-y-1.5">
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
                                @elseif($role === 'komite')
                                    <x-sidebars.komite />
                                @endif
                            </ul>
                        @endif
                    </nav>
                </div>

                @if(in_array(strtolower(Auth::user()->role->name ?? ''), ['admin', 'super_admin']))
                <div class="p-3.5 border-t border-slate-100 bg-slate-50/50 shrink-0">
                    <p class="text-xs font-bold text-slate-700">SinergiEdu</p>
                    <p class="text-[11px] font-medium text-slate-400 mt-0.5">
                        {{ strtolower(Auth::user()->role->name ?? '') === 'super_admin' ? 'Platform Console' : 'Admin Workspace' }}
                    </p>
                </div>
                @endif
            </aside>
        </div>
    </div>

    <!-- 3. APP BODY SHELL (Desktop Push Grid with Independent Scrolling Containers) -->
    <div class="app-body-shell w-full flex-1 min-h-0"
         :class="{ 'desktop-sidebar-open': sidebarDesktopOpen }">

        <!-- Desktop Sidebar (Scrollbar #1: Independent scroll container, min-h-0 h-full overflow-hidden) -->
        <aside class="desktop-sidebar-pane hidden lg:flex lg:flex-col border-r border-slate-200 bg-white h-full min-h-0 w-[230px] min-w-[230px] overflow-hidden">
            <div class="flex flex-grow flex-col overflow-y-auto overflow-x-hidden py-4 px-3.5 w-[230px] h-full min-h-0">
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-2">
                        <li>
                            <ul role="list" class="space-y-1.5">
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
                                @elseif($role === 'komite')
                                    <x-sidebars.komite />
                                @else
                                    {{ $sidebar ?? '' }}
                                @endif
                            </ul>
                        </li>

                        @if(in_array(strtolower(Auth::user()->role->name ?? ''), ['admin', 'super_admin']))
                        <li class="mt-auto pt-4 pb-0 shrink-0">
                            <div class="border-t border-slate-200/60 pt-3 px-1">
                                <p class="text-xs font-bold text-slate-700 truncate">SinergiEdu</p>
                                <p class="text-[11px] font-medium text-slate-400 mt-0.5 truncate">
                                    {{ strtolower(Auth::user()->role->name ?? '') === 'super_admin' ? 'Platform Console' : 'Admin Workspace' }}
                                </p>
                            </div>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Main Column Pane (Scrollbar #2: Independent scroll container, min-h-0 min-w-0 h-full overflow-y-auto) -->
        <div class="main-column-pane flex flex-col flex-1 min-w-0 min-h-0 h-full w-full overflow-y-auto overflow-x-hidden">
            <main class="py-6 sm:py-8 flex-1 min-w-0 w-full">
                <div class="px-4 sm:px-6 lg:px-8 page-content-enter max-w-full">
                    {{ $slot }}
                </div>
            </main>

            <!-- Footer (Scrolls naturally inside Main Column Container) -->
            <x-layouts.footer />
        </div>
    </div>

    @if(isset($modals))
        {{ $modals }}
    @endif

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
