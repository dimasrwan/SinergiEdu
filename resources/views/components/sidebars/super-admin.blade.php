@php
    $currentRoute = request()->route()->getName();
@endphp

<li>
    <a href="{{ route('super_admin.dashboard') }}" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ Str::startsWith($currentRoute, 'super_admin.dashboard') ? 'bg-slate-50 text-primary' : 'text-slate-700 hover:text-primary hover:bg-slate-50' }}">
        <svg class="h-6 w-6 shrink-0 {{ Str::startsWith($currentRoute, 'super_admin.dashboard') ? 'text-primary' : 'text-slate-400 group-hover:text-primary' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
        </svg>
        Dashboard
    </a>
</li>
<li>
    <a href="{{ route('super_admin.schools.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ Str::startsWith($currentRoute, 'super_admin.schools') ? 'bg-slate-50 text-primary' : 'text-slate-700 hover:text-primary hover:bg-slate-50' }}">
        <svg class="h-6 w-6 shrink-0 {{ Str::startsWith($currentRoute, 'super_admin.schools') ? 'text-primary' : 'text-slate-400 group-hover:text-primary' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
        </svg>
        Manajemen Sekolah
    </a>
</li>
