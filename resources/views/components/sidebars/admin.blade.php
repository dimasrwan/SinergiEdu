<x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" /><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" /></svg>
    </x-slot:icon>
    Dashboard
</x-sidebar-link>

<!-- PENGGUNA -->
<li class="mt-4 mb-1">
    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3">Pengguna</span>
</li>
<x-sidebar-link href="{{ route('admin.teachers.index') }}" :active="request()->routeIs('admin.teachers.*')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
    </x-slot:icon>
    Manajemen Guru
</x-sidebar-link>
<x-sidebar-link href="{{ route('admin.students.index') }}" :active="request()->routeIs('admin.students.*')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
    </x-slot:icon>
    Manajemen Siswa
</x-sidebar-link>
<x-sidebar-link href="{{ route('admin.parents.index') }}" :active="request()->routeIs('admin.parents.*')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
    </x-slot:icon>
    Manajemen Orang Tua
</x-sidebar-link>
<x-sidebar-link href="{{ route('admin.wakas.index') }}" :active="request()->routeIs('admin.wakas.*')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
    </x-slot:icon>
    Manajemen Waka Kurikulum
</x-sidebar-link>
<x-sidebar-link href="{{ route('admin.pengawas.index') }}" :active="request()->routeIs('admin.pengawas.*')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>
    </x-slot:icon>
    Manajemen Pengawas
</x-sidebar-link>
<x-sidebar-link href="{{ route('admin.kepala-sekolah.index') }}" :active="request()->routeIs('admin.kepala-sekolah.*')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M2 21a8 8 0 0 1 10.434-7.62"/><circle cx="10" cy="8" r="5"/><circle cx="18" cy="18" r="3"/><path d="m19.5 14.3-.4.9"/><path d="m16.9 20.8-.4.9"/><path d="m21.7 19.5-.9-.4"/><path d="m15.2 16.9-.9-.4"/><path d="m21.7 16.5-.9.4"/><path d="m15.2 19.1-.9.4"/><path d="m19.5 21.7-.4-.9"/><path d="m16.9 15.2-.4-.9"/></svg>
    </x-slot:icon>
    Manajemen Kepala Sekolah/Madrasah
</x-sidebar-link>

<!-- AKADEMIK -->
<li class="mt-4 mb-1">
    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3">Akademik</span>
</li>
<x-sidebar-link href="{{ route('admin.classes.index') }}" :active="request()->routeIs('admin.classes.*')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 22v-4a2 2 0 1 0-4 0v4"/><path d="m18 10 4 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-8l4-2"/><path d="M18 5v17"/><path d="m4 6 8-4 8 4"/><path d="M6 5v17"/><circle cx="12" cy="9" r="2"/></svg>
    </x-slot:icon>
    Kelas
</x-sidebar-link>

<x-sidebar-link href="{{ route('admin.subjects.index') }}" :active="request()->routeIs('admin.subjects.*')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
    </x-slot:icon>
    Mata Pelajaran
</x-sidebar-link>
<x-sidebar-link href="{{ route('admin.academic-years.index') }}" :active="request()->routeIs('admin.academic-years.*')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
    </x-slot:icon>
    Tahun Ajaran
</x-sidebar-link>
<x-sidebar-link href="{{ route('admin.semesters.index') }}" :active="request()->routeIs('admin.semesters.*')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M3 10h18"/><path d="M8 2v4"/><path d="M17 14h-6"/><path d="M13 18H7"/><path d="M7 14h.01"/><path d="M17 18h.01"/></svg>
    </x-slot:icon>
    Semester
</x-sidebar-link>

<!-- SISTEM -->
<li class="mt-4 mb-1">
    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3">Sistem</span>
</li>
<x-sidebar-link href="{{ route('admin.settings.index') }}" :active="request()->routeIs('admin.settings.*')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
    </x-slot:icon>
    Pengaturan Sistem
</x-sidebar-link>
