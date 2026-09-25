<x-layouts.app>
    <x-slot:title>Profil Pengguna</x-slot:title>
    
    <x-slot:sidebar>
        <div class="px-4 py-4 space-y-4">
            <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 text-sm font-semibold text-slate-600 hover:text-blue-700 hover:bg-blue-50 px-3 py-2 rounded-lg transition">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Dasbor
            </a>
            <div class="border-t border-slate-100 pt-4">
                <span class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Pengaturan</span>
                <a href="#profile-info" class="flex items-center gap-3 text-sm font-semibold text-slate-700 bg-slate-50 px-3 py-2 rounded-lg transition">
                    Informasi Profil
                </a>
                <a href="#password-update" class="flex items-center gap-3 text-sm font-semibold text-slate-600 hover:bg-slate-50 px-3 py-2 rounded-lg transition">
                    Keamanan Sandi
                </a>
                <a href="#account-delete" class="flex items-center gap-3 text-sm font-semibold text-red-600 hover:bg-red-50 px-3 py-2 rounded-lg transition">
                    Hapus Akun
                </a>
            </div>
        </div>
    </x-slot:sidebar>

    <div class="w-full">
        @php
            $user = auth()->user();
            $student = \App\Models\Student::where('user_id', $user->id)->first();
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            $parent = \App\Models\StudentParent::where('user_id', $user->id)->first();
        @endphp

        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Profil Pengguna</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola informasi akun dan keamanan akun Anda.</p>
        </div>

        <div class="space-y-6">
            <!-- Profile & Personal Info Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Profil Card (Left) -->
                <div class="lg:col-span-1">
                    <x-card class="h-full bg-white shadow-sm border border-slate-200/60 rounded-2xl p-6 md:p-8">
                        <div class="flex flex-col items-center text-center">
                            <div class="mb-4 flex flex-col items-center">
                                <x-avatar :user="$user" size="w-24 h-24" textSize="text-4xl" class="mb-4" />
                                
                                <div class="flex gap-2 justify-center">
                                    <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" class="inline" x-data="{ submitting: false }" @submit="submitting = true">
                                        @csrf
                                        <input type="file" name="photo" id="photo" class="hidden" accept=".jpg,.jpeg,.png" onchange="this.form.submit()" :disabled="submitting">
                                        <button type="button" onclick="document.getElementById('photo').click()" class="text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg-lg border border-blue-200 transition" x-bind:class="{ 'opacity-50 cursor-not-allowed': submitting }" :disabled="submitting">
                                            <span x-show="!submitting">Ganti Foto</span>
                                            <span x-show="submitting">Menyimpan...</span>
                                        </button>
                                    </form>

                                    @if ($user->profilePhotoUrl())
                                        <form action="{{ route('profile.photo.destroy') }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto profil?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg-lg border border-red-200 transition">
                                                Hapus Foto
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                @error('photo')
                                    <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <h2 class="text-xl font-bold text-slate-900 mb-1">{{ $user->name }}</h2>
                            <span class="inline-block px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wide bg-blue-50 text-blue-600 border border-blue-200/60 mb-4">
                                {{ $user->role->name ?? 'User' }}
                            </span>
                            <div class="w-full flex items-center justify-center gap-2 text-slate-500 text-sm bg-slate-50 py-2 px-3 rounded-lg-lg border border-slate-100">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                                <span class="truncate">{{ $user->email }}</span>
                            </div>
                        </div>

                        <!-- Metadata Profil Tambahan (jika ada) -->
                        @if($student || $teacher || $parent)
                            <div class="mt-6 pt-6 border-t border-slate-100 space-y-4">
                                @if($teacher)
                                    <div>
                                        <span class="block text-slate-400 text-xs font-semibold uppercase">NIP</span>
                                        <span class="text-slate-700 font-medium text-sm">{{ $teacher->nip ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-slate-400 text-xs font-semibold uppercase">Telepon</span>
                                        <span class="text-slate-700 font-medium text-sm">{{ $teacher->phone ?? '-' }}</span>
                                    </div>
                                @endif
                                @if($student)
                                    <div>
                                        <span class="block text-slate-400 text-xs font-semibold uppercase">NIS / NISN</span>
                                        <span class="text-slate-700 font-medium text-sm">{{ $student->nis ?? '-' }} / {{ $student->nisn ?? '-' }}</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <span class="block text-slate-400 text-xs font-semibold uppercase">L/P</span>
                                            <span class="text-slate-700 font-medium text-sm">{{ $student->gender === 'L' ? 'Laki-laki' : ($student->gender === 'P' ? 'Perempuan' : '-') }}</span>
                                        </div>
                                        <div>
                                            <span class="block text-slate-400 text-xs font-semibold uppercase">Kelas</span>
                                            <span class="text-slate-700 font-medium text-sm">{{ $student->studentClass->name ?? '-' }}</span>
                                        </div>
                                    </div>
                                @endif
                                @if($parent)
                                    <div>
                                        <span class="block text-slate-400 text-xs font-semibold uppercase">Telepon</span>
                                        <span class="text-slate-700 font-medium text-sm">{{ $parent->phone ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-slate-400 text-xs font-semibold uppercase">Alamat</span>
                                        <span class="text-slate-700 font-medium text-sm block" title="{{ $parent->address }}">{{ $parent->address ?? '-' }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </x-card>
                </div>

                <!-- Form Update Profile (Right) -->
                <div id="profile-info" class="lg:col-span-2 scroll-mt-24">
                    <x-card class="h-full bg-white shadow-sm border border-slate-200/60 rounded-2xl p-6 md:p-8">
                        @include('profile.partials.update-profile-information-form')
                    </x-card>
                </div>
            </div>

            <!-- Form Update Password (Full Width) -->
            <div id="password-update" class="scroll-mt-24">
                <x-card class="bg-white shadow-sm border border-slate-200/60 rounded-2xl p-6 md:p-8">
                    @include('profile.partials.update-password-form')
                </x-card>
            </div>

            <!-- Form Delete Account (Full Width) -->
            <div id="account-delete" class="scroll-mt-24">
                <div class="bg-red-50 border border-red-100/80 rounded-2xl p-6 md:p-8 shadow-sm">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
