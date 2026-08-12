<x-layouts.app>
    <x-slot:title>Profil Pengguna</x-slot:title>
    
    <x-slot:sidebar>
        <div class="px-4 py-4 space-y-4">
            <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 text-sm font-semibold text-slate-600 hover:text-blue-700 hover:bg-blue-50 px-3 py-2 rounded-xl transition">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Dasbor
            </a>
            <div class="border-t border-slate-100 pt-4">
                <span class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Pengaturan</span>
                <a href="#profile-info" class="flex items-center gap-3 text-sm font-semibold text-slate-700 bg-slate-50 px-3 py-2 rounded-xl transition">
                    Informasi Profil
                </a>
                <a href="#password-update" class="flex items-center gap-3 text-sm font-semibold text-slate-600 hover:bg-slate-50 px-3 py-2 rounded-xl transition">
                    Keamanan Sandi
                </a>
                <a href="#account-delete" class="flex items-center gap-3 text-sm font-semibold text-red-600 hover:bg-red-50 px-3 py-2 rounded-xl transition">
                    Hapus Akun
                </a>
            </div>
        </div>
    </x-slot:sidebar>

    <div class="space-y-6 max-w-4xl">
        @php
            $user = auth()->user();
            $student = \App\Models\Student::where('user_id', $user->id)->first();
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            $parent = \App\Models\StudentParent::where('user_id', $user->id)->first();
        @endphp

        <!-- Profil Header / Card Utama -->
        <x-card padding="lg" class="bg-gradient-to-br from-slate-900 to-blue-900 text-white overflow-hidden relative border-0">
            <div class="absolute top-0 right-0 p-8 opacity-10">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                </svg>
            </div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center gap-6">
                <div class="w-24 h-24 rounded-full bg-white/20 flex items-center justify-center text-4xl font-bold backdrop-blur-sm border-4 border-white/30 shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <div class="flex flex-col md:flex-row md:items-center gap-3 mb-2">
                        <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                        <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wide bg-blue-500/30 text-blue-100 border border-blue-400/30">
                            {{ $user->role->name ?? 'User' }}
                        </span>
                    </div>
                    <p class="text-blue-100 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                        {{ $user->email }}
                    </p>
                </div>
            </div>
            
            <!-- Metadata Profil Tambahan (jika ada) -->
            @if($student || $teacher || $parent)
                <div class="mt-8 pt-6 border-t border-white/10 grid grid-cols-2 md:grid-cols-4 gap-4 relative z-10">
                    @if($teacher)
                        <div>
                            <span class="block text-blue-200/70 text-xs font-semibold uppercase">NIP</span>
                            <span class="text-white font-medium text-sm">{{ $teacher->nip ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-blue-200/70 text-xs font-semibold uppercase">Telepon</span>
                            <span class="text-white font-medium text-sm">{{ $teacher->phone ?? '-' }}</span>
                        </div>
                    @endif
                    @if($student)
                        <div>
                            <span class="block text-blue-200/70 text-xs font-semibold uppercase">NIS / NISN</span>
                            <span class="text-white font-medium text-sm">{{ $student->nis ?? '-' }} / {{ $student->nisn ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-blue-200/70 text-xs font-semibold uppercase">Jenis Kelamin</span>
                            <span class="text-white font-medium text-sm">{{ $student->gender === 'L' ? 'Laki-laki' : ($student->gender === 'P' ? 'Perempuan' : '-') }}</span>
                        </div>
                        <div>
                            <span class="block text-blue-200/70 text-xs font-semibold uppercase">Kelas</span>
                            <span class="text-white font-medium text-sm">{{ $student->studentClass->name ?? '-' }}</span>
                        </div>
                    @endif
                    @if($parent)
                        <div>
                            <span class="block text-blue-200/70 text-xs font-semibold uppercase">Telepon</span>
                            <span class="text-white font-medium text-sm">{{ $parent->phone ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-blue-200/70 text-xs font-semibold uppercase">Alamat</span>
                            <span class="text-white font-medium text-sm truncate block" title="{{ $parent->address }}">{{ $parent->address ?? '-' }}</span>
                        </div>
                    @endif
                </div>
            @endif
        </x-card>

        <!-- Form Update Profile -->
        <div id="profile-info" class="scroll-mt-24">
            <x-card padding="lg">
                @include('profile.partials.update-profile-information-form')
            </x-card>
        </div>

        <!-- Form Update Password -->
        <div id="password-update" class="scroll-mt-24">
            <x-card padding="lg">
                @include('profile.partials.update-password-form')
            </x-card>
        </div>

        <!-- Form Delete Account -->
        <div id="account-delete" class="scroll-mt-24">
            <div class="bg-red-50 border border-red-100 rounded-3xl p-6 shadow-sm">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-layouts.app>
