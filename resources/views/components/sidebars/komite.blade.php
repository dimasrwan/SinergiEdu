<x-sidebar-link href="{{ route('komite.dashboard') }}" :active="request()->routeIs('komite.dashboard')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
        </svg>
    </x-slot:icon>
    Dashboard
</x-sidebar-link>

<!-- SEKOLAH -->
<li class="mt-4 mb-1">
    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3">Sekolah</span>
</li>
<x-sidebar-link href="{{ route('komite.school-profile') }}" :active="request()->routeIs('komite.school-profile')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.5M4.5 21V10.5" />
        </svg>
    </x-slot:icon>
    Profil Sekolah
</x-sidebar-link>
<x-sidebar-link href="{{ route('komite.school-programs') }}" :active="request()->routeIs('komite.school-programs')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
        </svg>
    </x-slot:icon>
    Program Sekolah
</x-sidebar-link>

<!-- KINERJA -->
<li class="mt-4 mb-1">
    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3">Kinerja</span>
</li>
<x-sidebar-link href="{{ route('komite.performance-summary') }}" :active="request()->routeIs('komite.performance-summary')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75M9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625M16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
        </svg>
    </x-slot:icon>
    Ringkasan Kinerja
</x-sidebar-link>

<!-- ASPIRASI -->
<li class="mt-4 mb-1">
    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3">Aspirasi</span>
</li>
<x-sidebar-link href="{{ route('komite.aspirations.index') }}" :active="request()->routeIs('komite.aspirations.*')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <path d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
        </svg>
    </x-slot:icon>
    Aspirasi & Masukan
</x-sidebar-link>
