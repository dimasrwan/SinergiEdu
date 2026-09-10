        <x-sidebar-link href="{{ route('pengawas.dashboard') }}" :active="request()->routeIs('pengawas.dashboard')">
            <x-slot:icon>
                <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" /><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" /></svg>
            </x-slot:icon>
            Dashboard
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('pengawas.students.index') }}" :active="request()->routeIs('pengawas.students.*')">
            <x-slot:icon>
                <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
            </x-slot:icon>
            Monitoring Siswa
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('pengawas.feedback.index') }}" :active="request()->routeIs('pengawas.feedback.*')">
            <x-slot:icon>
                <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
            </x-slot:icon>
            Feedback & Rencana Aksi
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('pengawas.evaluations.index') }}" :active="request()->routeIs('pengawas.evaluations.*')">
            <x-slot:icon>
                <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
            </x-slot:icon>
            Dokumen Evaluasi
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('pengawas.reports.index') }}" :active="request()->routeIs('pengawas.reports.*')">
            <x-slot:icon>
                <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
            </x-slot:icon>
            Laporan Kinerja
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('pengawas.inspections.index') }}" :active="request()->routeIs('pengawas.inspections.*')">
            <x-slot:icon>
                <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>
            </x-slot:icon>
            Jadwal Inspeksi
        </x-sidebar-link>
