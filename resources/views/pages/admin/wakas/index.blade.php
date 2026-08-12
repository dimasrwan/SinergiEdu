<x-layouts.app>
    <x-slot:title>Manajemen Waka Kurikulum</x-slot:title>

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between border-b border-slate-200 pb-5 mb-6 gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Daftar Waka Kurikulum</h1>
                <p class="mt-2 text-sm text-slate-500 max-w-2xl">
                    Kelola data dan akun akses Waka Kurikulum.
                    <span class="inline-flex items-center ml-2 px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold border border-slate-200">
                        {{ $wakas->total() }} Waka Kurikulum
                    </span>
                </p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto shrink-0">
                <x-button variant="primary" href="{{ route('admin.wakas.create') }}">
                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Waka Kurikulum
                </x-button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm text-emerald-800 flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Search Toolbar -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
            <form action="{{ route('admin.wakas.index') }}" method="GET" class="w-full md:w-96 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, atau email..." 
                    class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent sm:text-sm transition-shadow">
            </form>
        </div>

        <!-- Table Container -->
        <x-card padding="none" class="overflow-visible">
            <div class="overflow-x-auto lg:overflow-visible">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-16">No</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama / NIP</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No. HP</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($wakas as $index => $waka)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $wakas->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <!-- Avatar Inisial -->
                                        @php
                                            $name = $waka->user->name ?? 'U N';
                                            $initials = collect(explode(' ', $name))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                                        @endphp
                                        <div class="w-10 h-10 rounded-full bg-blue-50 text-primary flex items-center justify-center text-sm font-bold border border-blue-100 shrink-0">
                                            {{ strtoupper($initials) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900 text-sm">{{ $waka->user->name ?? '-' }}</div>
                                            @if($waka->nip)
                                                <div class="text-xs text-slate-500 mt-0.5">NIP: {{ $waka->nip }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    {{ $waka->user->email ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    {{ $waka->phone ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div x-data="{ menuOpen: false }" class="relative inline-block text-left">
                                        <button @click="menuOpen = !menuOpen" @click.away="menuOpen = false" type="button" class="p-2 text-slate-400 hover:text-primary hover:bg-slate-50 rounded-lg transition-colors">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                            </svg>
                                        </button>
                                        <div x-show="menuOpen" x-transition class="absolute right-0 z-10 mt-1 w-40 origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-slate-900/5 focus:outline-none py-1" style="display: none;">
                                            <a href="{{ route('admin.wakas.show', $waka) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors">Lihat Detail</a>
                                            <a href="{{ route('admin.wakas.edit', $waka) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-accent transition-colors">Edit Data</a>
                                            <div class="my-1 border-t border-slate-100"></div>
                                            <button type="button" x-on:click="$dispatch('open-modal', 'delete-waka-{{ $waka->id }}'); menuOpen = false" class="block w-full text-left px-4 py-2 text-sm text-danger hover:bg-red-50 transition-colors">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Delete Modal -->
                                    <x-modal name="delete-waka-{{ $waka->id }}" maxWidth="sm">
                                        <div class="p-6">
                                            <div class="w-12 h-12 rounded-full bg-red-100 text-danger flex items-center justify-center mb-4">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                            </div>
                                            <h2 class="text-lg font-bold text-slate-900">Konfirmasi Penghapusan</h2>
                                            <p class="mt-2 text-sm text-slate-600">Apakah Anda yakin ingin menghapus data Waka Kurikulum <strong>{{ $waka->user->name ?? '' }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
                                            <div class="mt-6 flex justify-end gap-3">
                                                <x-button variant="secondary" x-on:click="$dispatch('close-modal', 'delete-waka-{{ $waka->id }}')">Batal</x-button>
                                                <form action="{{ route('admin.wakas.destroy', $waka) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-button variant="danger" type="submit">Hapus Data</x-button>
                                                </form>
                                            </div>
                                        </div>
                                    </x-modal>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-4">
                                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                                        </div>
                                        @if(request('search'))
                                            <h3 class="text-sm font-bold text-slate-900 mb-1">Waka Kurikulum tidak ditemukan</h3>
                                            <p class="text-sm text-slate-500">Tidak ada Waka Kurikulum yang cocok dengan kata kunci pencarian Anda.</p>
                                        @else
                                            <h3 class="text-sm font-bold text-slate-900 mb-1">Belum ada Waka Kurikulum</h3>
                                            <p class="text-sm text-slate-500 mb-4">Belum terdapat akun Waka Kurikulum yang terdaftar.</p>
                                            <x-button variant="primary" href="{{ route('admin.wakas.create') }}">
                                                Tambah Waka Kurikulum
                                            </x-button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($wakas->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $wakas->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.app>
