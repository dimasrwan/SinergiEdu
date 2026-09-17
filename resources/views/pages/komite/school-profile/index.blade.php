<x-layouts.app>
    <x-slot:title>Profil Sekolah</x-slot:title>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Profil Sekolah</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Informasi resmi institusi (Read Only).</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold border border-slate-200 self-start sm:self-auto">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
                Mode Lihat Saja
            </span>
        </div>

        <x-card padding="md" class="space-y-6">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-6 pb-6 border-b border-slate-100">
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center shrink-0 overflow-hidden shadow-2xs">
                    @if(!empty($school->logo))
                        <img src="{{ Storage::url($school->logo) }}" alt="{{ $school->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-3xl font-extrabold text-primary">{{ strtoupper(substr($school->name ?? 'S', 0, 1)) }}</span>
                    @endif
                </div>
                <div>
                    <span class="px-2.5 py-0.5 rounded bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-wider border border-blue-200">
                        {{ $school->level ?? 'Sekolah' }}
                    </span>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-1">{{ $school->name }}</h2>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">NPSN: {{ $school->npsn ?? '-' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Nama Resmi Sekolah</label>
                    <p class="text-sm font-semibold text-slate-900 p-3 rounded-lg bg-slate-50 border border-slate-200/80">{{ $school->name }}</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">NPSN (Nomor Pokok Sekolah Nasional)</label>
                    <p class="text-sm font-semibold text-slate-900 p-3 rounded-lg bg-slate-50 border border-slate-200/80">{{ $school->npsn ?? '-' }}</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Email Sekolah</label>
                    <p class="text-sm font-semibold text-slate-900 p-3 rounded-lg bg-slate-50 border border-slate-200/80">{{ $school->email ?? '-' }}</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Nomor Telepon / Kontak</label>
                    <p class="text-sm font-semibold text-slate-900 p-3 rounded-lg bg-slate-50 border border-slate-200/80">{{ $school->phone ?? '-' }}</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Alamat Lengkap</label>
                    <p class="text-sm font-semibold text-slate-900 p-3 rounded-lg bg-slate-50 border border-slate-200/80 leading-relaxed">{{ $school->address ?? '-' }}</p>
                </div>
            </div>
        </x-card>
    </div>
</x-layouts.app>
