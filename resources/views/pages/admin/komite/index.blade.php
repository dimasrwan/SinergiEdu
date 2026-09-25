<x-layouts.app>
    <x-slot:title>Manajemen Komite Sekolah</x-slot:title>

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between border-b border-slate-200 pb-5 gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Komite Sekolah</h1>
                <p class="mt-2 text-sm text-slate-500 max-w-2xl">
                    Kelola akun Komite Sekolah untuk peninjauan dan aspirasi institusi.
                    @if($totalKomite > 0)
                        <span class="inline-flex items-center ml-2 px-2.5 py-0.5 rounded-lg bg-slate-100 text-slate-600 text-xs font-semibold border border-slate-200">
                            {{ $totalKomite }} anggota komite
                        </span>
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto shrink-0">
                <x-button variant="primary" href="{{ route('admin.komite.create') }}">
                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Komite
                </x-button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm text-emerald-800 flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Search Toolbar -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
            <form action="{{ route('admin.komite.index') }}" method="GET" class="w-full">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." 
                        class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-lg leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent text-sm transition-shadow">
                </div>
            </form>
        </div>

        <!-- Table / Mobile Cards Container -->
        <x-card padding="none" class="overflow-visible">
            <!-- Mobile Cards View (lg:hidden) -->
            <div class="lg:hidden divide-y divide-slate-100">
                @forelse($komiteUsers as $user)
                    <div class="p-4 space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-slate-900 text-base leading-snug break-words">{{ $user->name }}</h3>
                                <div class="text-xs text-slate-500 font-medium mt-0.5 break-all">{{ $user->email }}</div>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                            <a href="{{ route('admin.komite.show', $user) }}" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors min-h-[38px] min-w-[38px] inline-flex items-center justify-center" title="Lihat Detail">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </a>
                            <a href="{{ route('admin.komite.edit', $user) }}" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors min-h-[38px] min-w-[38px] inline-flex items-center justify-center" title="Edit">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500 text-sm font-medium">
                        Belum ada akun Komite Sekolah
                    </div>
                @endforelse
            </div>

            <!-- Desktop View Table (hidden lg:block) -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Anggota</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($komiteUsers as $user)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 text-sm">{{ $user->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.komite.show', $user) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </a>
                                        <a href="{{ route('admin.komite.edit', $user) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500 text-sm font-medium">
                                    Belum ada akun Komite Sekolah terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($komiteUsers->hasPages())
                <div class="p-5 border-t border-slate-100 bg-slate-50/50">
                    {{ $komiteUsers->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
