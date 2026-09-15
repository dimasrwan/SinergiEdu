<x-layouts.app>
    <x-slot:title>Hubungkan Pengawas</x-slot:title>

    <div class="max-w-4xl space-y-6 mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Hubungkan Pengawas Existing</h1>
                <p class="mt-1 text-sm text-slate-500">Cari dan hubungkan akun Pengawas yang sudah terdaftar ke sekolah Anda.</p>
            </div>
            <a href="{{ route('admin.pengawas.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <!-- Search Toolbar -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
            <form action="{{ route('admin.pengawas.connect.form') }}" method="GET" class="w-full relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, atau email Pengawas..." 
                    class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-lg leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent sm:text-sm transition-shadow">
            </form>
        </div>

        @php
            $adminSchoolId = auth()->user()->school_id;
        @endphp

        <!-- Available Pengawas List -->
        <x-card padding="none" class="overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama & NIP</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status Hubungan</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($availablePengawas as $pengawasItem)
                            @php
                                $isConnected = $pengawasItem->user->assignedSchools->contains('id', $adminSchoolId);
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 text-sm">{{ $pengawasItem->user->name ?? '-' }}</div>
                                    @if($pengawasItem->nip)
                                        <div class="text-xs text-slate-500 font-mono mt-0.5">NIP: {{ $pengawasItem->nip }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $pengawasItem->user->email ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($isConnected)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Terhubung
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold border border-slate-200">
                                            Belum Terhubung
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($isConnected)
                                        <button disabled class="px-3 py-1.5 text-xs font-semibold text-slate-400 bg-slate-100 rounded-lg cursor-not-allowed">
                                            Sudah Terhubung
                                        </button>
                                    @else
                                        <form action="{{ route('admin.pengawas.connect') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="pengawas_id" value="{{ $pengawasItem->id }}">
                                            <x-button variant="primary" type="submit" class="px-3 py-1.5 text-xs font-semibold">
                                                Hubungkan Pengawas
                                            </x-button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-500">
                                    @if(request('search'))
                                        Tidak ada Pengawas yang cocok dengan kata kunci pencarian.
                                    @else
                                        Belum ada akun Pengawas terdaftar di platform.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($availablePengawas->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $availablePengawas->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
