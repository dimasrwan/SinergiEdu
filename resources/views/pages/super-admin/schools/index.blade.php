<x-layouts.app title="Manajemen Sekolah">
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Sekolah</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola sekolah yang menggunakan platform SinergiEdu.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('super_admin.schools.create') }}" class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors">
                <svg class="-ml-0.5 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Sekolah
            </a>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-4 rounded-xl shadow-sm ring-1 ring-slate-900/5 mb-6">
        <form action="{{ route('super_admin.schools.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="sr-only">Cari</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full rounded-lg border-0 py-2 pl-10 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6" placeholder="Cari nama sekolah atau NPSN...">
                </div>
            </div>
            <div class="sm:w-48">
                <label for="status" class="sr-only">Status</label>
                <select id="status" name="status" class="block w-full rounded-lg border-0 py-2 pl-3 pr-10 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-primary sm:text-sm sm:leading-6" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div>
                <button type="submit" class="inline-flex w-full sm:w-auto items-center justify-center rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-300 transition-colors">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-900/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-semibold text-slate-900 sm:pl-6 w-16">NO</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900">NAMA SEKOLAH</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900">NPSN</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900">EMAIL</th>
                        <th scope="col" class="px-3 py-3.5 text-center text-xs font-semibold text-slate-900">JUMLAH PENGGUNA</th>
                        <th scope="col" class="px-3 py-3.5 text-center text-xs font-semibold text-slate-900">STATUS</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 w-24">
                            <span class="sr-only">AKSI</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($schools as $index => $school)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-slate-900 sm:pl-6">
                            {{ $schools->firstItem() + $index }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-900 font-semibold">
                            <div class="flex items-center">
                                @if($school->logo)
                                    <img src="{{ Storage::url($school->logo) }}" alt="Logo" class="h-8 w-8 rounded-md mr-3 object-cover border border-slate-200">
                                @else
                                    <div class="h-8 w-8 rounded-md mr-3 bg-slate-100 flex items-center justify-center border border-slate-200">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                                        </svg>
                                    </div>
                                @endif
                                {{ $school->name }}
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                            {{ $school->npsn ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                            {{ $school->email ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500 text-center">
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-800">
                                {{ number_format($school->users_count) }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                            @if($school->is_active)
                                <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Aktif</span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">Nonaktif</span>
                            @endif
                        </td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('super_admin.schools.show', $school) }}" class="text-slate-400 hover:text-blue-600 transition-colors" title="Detail">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </a>
                                <a href="{{ route('super_admin.schools.edit', $school) }}" class="text-slate-400 hover:text-amber-600 transition-colors" title="Edit">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-3 py-8 text-center text-sm text-slate-500">
                            Tidak ada data sekolah yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($schools->hasPages())
        <div class="border-t border-slate-200 px-4 py-3 sm:px-6">
            {{ $schools->links() }}
        </div>
        @endif
    </div>
</x-layouts.app>
