<x-sidebar-link href="{{ route('kepala-sekolah.dashboard') }}" :active="request()->routeIs('kepala-sekolah.dashboard')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75M9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625M16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
    </x-slot:icon>
    Dashboard
</x-sidebar-link>

<!-- MONITORING AKADEMIK -->
<li class="mt-4 mb-1">
    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3">Monitoring Akademik</span>
</li>
<x-sidebar-link href="{{ route('kepala-sekolah.academic.rekap') }}" :active="request()->routeIs('kepala-sekolah.academic.rekap')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/></svg>
    </x-slot:icon>
    Rekapitulasi Nilai
</x-sidebar-link>
<x-sidebar-link href="{{ route('kepala-sekolah.academic.perkembangan') }}" :active="request()->routeIs('kepala-sekolah.academic.perkembangan')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="m7 14 4-4 4 4 6-6"/><path d="M15 8h4v4"/></svg>
    </x-slot:icon>
    Perkembangan Siswa
</x-sidebar-link>
<x-sidebar-link href="{{ route('kepala-sekolah.academic.subjects') }}" :active="request()->routeIs('kepala-sekolah.academic.subjects')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
    </x-slot:icon>
    Analitik Mata Pelajaran
</x-sidebar-link>

<!-- SUPERVISI KINERJA GURU -->
<li class="mt-4 mb-1">
    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3">Supervisi Kinerja</span>
</li>
<x-sidebar-link href="{{ route('kepala-sekolah.supervision.grading-status') }}" :active="request()->routeIs('kepala-sekolah.supervision.grading-status')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
    </x-slot:icon>
    Status Penilaian Guru
</x-sidebar-link>
<x-sidebar-link href="{{ route('kepala-sekolah.supervision.teacher-report') }}" :active="request()->routeIs('kepala-sekolah.supervision.teacher-report')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/></svg>
    </x-slot:icon>
    Laporan Kinerja Guru
</x-sidebar-link>

<!-- EVALUASI & FEEDBACK -->
<li class="mt-4 mb-1">
    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3">Evaluasi & Feedback</span>
</li>
<x-sidebar-link href="{{ route('kepala-sekolah.feedback.index') }}" :active="request()->routeIs('kepala-sekolah.feedback.*')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
    </x-slot:icon>
    Feedback Strategis
</x-sidebar-link>
<x-sidebar-link href="{{ route('kepala-sekolah.rencana-aksi.index') }}" :active="request()->routeIs('kepala-sekolah.rencana-aksi.*')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
    </x-slot:icon>
    Rencana Aksi
</x-sidebar-link>
<x-sidebar-link href="{{ route('kepala-sekolah.evaluasi.index') }}" :active="request()->routeIs('kepala-sekolah.evaluasi.*')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="m9 9 5 7"/><path d="m15 9-5 7"/><path d="M9 9h.01"/><path d="M15 15h.01"/></svg>
    </x-slot:icon>
    Evaluasi Sekolah
</x-sidebar-link>

<!-- LAPORAN -->
<li class="mt-4 mb-1">
    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3">Laporan</span>
</li>
<x-sidebar-link href="{{ route('kepala-sekolah.reports.index') }}" :active="request()->routeIs('kepala-sekolah.reports.*')">
    <x-slot:icon>
        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg>
    </x-slot:icon>
    Laporan & Export
</x-sidebar-link>
