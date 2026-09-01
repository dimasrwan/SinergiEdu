        <x-sidebar-link href="{{ route('pengawas.dashboard') }}" :active="request()->routeIs('pengawas.dashboard')">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
            </x-slot:icon>
            Dashboard
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('pengawas.students.index') }}" :active="request()->routeIs('pengawas.students.*')">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
            </x-slot:icon>
            Monitoring Siswa
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('pengawas.feedback.index') }}" :active="request()->routeIs('pengawas.feedback.*')">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-1.804 9-4.025V8.25c0-2.221-4.03-4.025-9-4.025S3 6.029 3 8.25v8c0 2.221 4.03 4.025 9 4.025Zm0-13.5c2.485 0 4.845.385 6.823 1.029.854.435 1.677.891 2.332 1.39m0 0A9.865 9.865 0 0 1 21 12c0 1.331-.072 2.64-.208 3.927m0 0A9.995 9.995 0 0 1 12 20.25M3.009 11.129A9.865 9.865 0 0 1 3 12c0 1.331.072 2.64.208 3.927M3 9.75c.73.49 1.555.946 2.332 1.39 1.978.644 4.338 1.029 6.823 1.029"/></svg>
            </x-slot:icon>
            Feedback & Rencana Aksi
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('pengawas.evaluations.index') }}" :active="request()->routeIs('pengawas.evaluations.*')">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
            </x-slot:icon>
            Dokumen Evaluasi
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('pengawas.reports.index') }}" :active="request()->routeIs('pengawas.reports.*')">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
            </x-slot:icon>
            Laporan Kinerja
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('pengawas.inspections.index') }}" :active="request()->routeIs('pengawas.inspections.*')">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
            </x-slot:icon>
            Jadwal Inspeksi
        </x-sidebar-link>
