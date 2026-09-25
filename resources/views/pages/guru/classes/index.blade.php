<x-layouts.app>
    <x-slot:title>Kelas Saya</x-slot:title>

    <div class="w-full space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Kelas Saya</h1>
                <p class="text-slate-500 text-sm mt-1">Daftar kelas dan mata pelajaran yang Anda ampu saat ini.</p>
            </div>
            @if(!$missingContext)
                <div class="bg-blue-50 text-primary px-4 py-2 rounded-xl text-sm font-semibold border border-blue-100 flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                    Tahun Ajaran {{ $activeAcademicYear->year }} • Semester {{ $activeSemester->name }}
                </div>
            @endif
        </div>

        @if($missingContext)
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-8 text-center max-w-2xl mx-auto">
                <div class="inline-flex items-center justify-center p-3 bg-amber-100 text-amber-600 rounded-lg mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Konteks Akademik Tidak Aktif</h3>
                <p class="text-slate-600 mb-4">Tahun ajaran atau semester aktif belum diatur oleh admin. Harap hubungi Admin Sekolah untuk melakukan konfigurasi akademik.</p>
            </div>
        @elseif($assignments->isEmpty())
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-8 text-center max-w-2xl mx-auto">
                <div class="inline-flex items-center justify-center p-3 bg-slate-200 text-slate-500 rounded-lg mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Belum ada kelas yang ditugaskan.</h3>
                <p class="text-slate-600 mb-4">Hubungi Admin Sekolah untuk mendapatkan penugasan mengajar.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($assignments as $assignment)
                    <a href="{{ route('guru.classes.show', $assignment) }}" class="block bg-white border border-slate-200 rounded-2xl p-5 hover:border-accent hover:shadow-md transition group relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-2 h-full bg-accent opacity-0 group-hover:opacity-100 transition"></div>
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-bold text-slate-900 text-xl mb-1">{{ $assignment->classroom->name }}</h3>
                                <p class="text-sm text-slate-600 font-medium">{{ $assignment->subject->name }}</p>
                            </div>
                            <span class="bg-blue-50 text-primary text-xs font-bold px-2.5 py-1 rounded-lg border border-blue-100">
                                Tingkat {{ $assignment->classroom->grade_level }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between border-t border-slate-100 pt-4 mt-2">
                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                                {{ $assignment->classroom->students()->count() ?? 0 }} Siswa
                            </div>
                            <span class="text-xs font-semibold text-accent group-hover:underline">Masuk Kelas &rarr;</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

    </div>
</x-layouts.app>
