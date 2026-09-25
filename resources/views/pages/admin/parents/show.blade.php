<x-layouts.app>
    <x-slot:title>Detail Orang Tua - {{ $parent->user->name }}</x-slot:title>

    <div class="w-full">
        <!-- Header & Breadcrumb -->
        <div class="mb-6 flex flex-col items-start gap-4">
            <a href="{{ route('admin.parents.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <div class="w-full flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Detail Orang Tua / Wali</h1>
                    <p class="mt-1 text-sm text-slate-500">Informasi profil wali beserta daftar anak yang berada di bawah pengawasannya.</p>
                </div>
                <div class="flex gap-2 w-full sm:w-auto shrink-0">
                    <x-button variant="secondary" href="{{ route('admin.parents.edit', $parent) }}" class="flex-1 sm:flex-none justify-center">Edit Data Wali</x-button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Kolom Kiri: Profil Wali -->
            <div class="lg:col-span-5 space-y-6">
                <x-card padding="none" class="overflow-hidden">
                    <div class="h-24 bg-gradient-to-br from-slate-800 to-slate-900"></div>
                    
                    <div class="px-6 pb-6 relative">
                        <!-- Avatar -->
                        <div class="relative -mt-12 mb-4">
                            <div class="w-24 h-24 rounded-2xl bg-white p-1 shadow-sm border border-slate-100">
                                <div class="w-full h-full bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 font-bold text-2xl">
                                    {{ strtoupper(substr($parent->user->name ?? 'W', 0, 1)) }}
                                </div>
                            </div>
                        </div>

                        <!-- Basic Info -->
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">{{ $parent->user->name ?? 'Tanpa Nama' }}</h2>
                            <p class="text-sm font-semibold text-slate-500 mt-1">Akun Wali Murid</p>
                        </div>
                        
                        <div class="mt-6 border-t border-slate-100 pt-6 space-y-5">
                            <div>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Email Akses Login</p>
                                <p class="text-sm font-medium text-slate-900">{{ $parent->user->email ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor Handphone</p>
                                <p class="text-sm font-medium text-slate-900">{{ $parent->phone ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Alamat Tinggal</p>
                                <p class="text-sm font-medium text-slate-900 leading-relaxed">{{ $parent->address ?? 'Belum diisi.' }}</p>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Kolom Kanan: Anak yang Terhubung -->
            <div class="lg:col-span-7 space-y-6">
                <x-card padding="lg">
                    <div class="flex items-center justify-between mb-5 border-b border-slate-100 pb-3">
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <svg class="h-5 w-5 text-accent" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                            Anak yang Terhubung
                        </h3>
                        <span class="inline-flex items-center justify-center bg-blue-50 text-primary text-xs font-bold px-2.5 py-0.5 rounded-lg border border-blue-100">
                            {{ $parent->students->count() }} Siswa
                        </span>
                    </div>
                    
                    @if($parent->students->count() > 0)
                        <div class="space-y-4">
                            @foreach($parent->students as $student)
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-white border border-slate-200 shadow-sm rounded-lg hover:border-slate-300 transition-colors">
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">{{ $student->user->name ?? '-' }}</p>
                                        <p class="text-xs text-slate-500 font-mono mt-0.5 mb-1">NIS: {{ $student->nis ?? '-' }}</p>
                                    </div>
                                    <div class="mt-2 sm:mt-0">
                                        <span class="inline-flex text-[10px] font-bold uppercase tracking-wider text-primary bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100">
                                            {{ $student->activeClassroom()->name ?? 'Belum Ada Kelas' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-8 text-center">
                            <div class="mx-auto w-12 h-12 bg-white shadow-sm rounded-full flex items-center justify-center text-slate-300 mb-4">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </div>
                            <p class="text-sm font-bold text-slate-900">Belum Terhubung dengan Siswa</p>
                            <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Orang tua/wali ini belum dikaitkan dengan profil siswa manapun di sistem.</p>
                            <x-button variant="secondary" href="{{ route('admin.parents.edit', $parent) }}" class="mt-4 !py-2 !text-xs">
                                Tambahkan Hubungan Anak
                            </x-button>
                        </div>
                    @endif
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.app>
