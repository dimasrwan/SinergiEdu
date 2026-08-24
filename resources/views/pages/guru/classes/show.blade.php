<x-layouts.app>
    <x-slot:title>Detail Kelas - {{ $class->classroom->name }}</x-slot:title>

    <div class="w-full space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('guru.classes.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ $class->classroom->name }}</h1>
                    <p class="text-slate-500 text-sm mt-1">{{ $class->subject->name }} • Tingkat {{ $class->classroom->grade_level }}</p>
                </div>
            </div>
            
            <div class="bg-blue-50 text-primary px-4 py-2 rounded-xl text-sm font-semibold border border-blue-100 flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                Tahun Ajaran {{ $class->academicYear->year }} • Semester {{ $class->semester->name }}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Info Penugasan -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Informasi Penugasan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                        <div>
                            <p class="text-xs text-slate-500 mb-1 uppercase tracking-wider font-semibold">Mata Pelajaran</p>
                            <p class="text-slate-900 font-medium">{{ $class->subject->name }} ({{ $class->subject->code }})</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1 uppercase tracking-wider font-semibold">Nama Kelas</p>
                            <p class="text-slate-900 font-medium">{{ $class->classroom->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1 uppercase tracking-wider font-semibold">Tingkat Pendidikan</p>
                            <p class="text-slate-900 font-medium">{{ $class->classroom->education_level }} - Kelas {{ $class->classroom->grade_level }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1 uppercase tracking-wider font-semibold">Jumlah Siswa</p>
                            <p class="text-slate-900 font-medium">{{ $class->classroom->students->count() ?? 0 }} Siswa terdaftar</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-primary rounded-2xl border border-primary p-6 text-white text-center">
                    <h3 class="font-bold text-lg mb-2">Area Pembelajaran</h3>
                    <p class="text-blue-100 text-sm mb-6">Kelola materi, tugas, dan nilai untuk kelas ini.</p>
                    
                    <div class="space-y-3">
                        <!-- Navigation Buttons (Currently redirect to placeholders or main index based on existing implementation) -->
                        <a href="{{ route('guru.materials.index') }}" class="block w-full py-2.5 bg-white/10 hover:bg-white/20 rounded-xl text-sm font-semibold transition border border-white/20">
                            Materi Pelajaran
                        </a>
                        <a href="{{ route('guru.assignments.index') }}" class="block w-full py-2.5 bg-white/10 hover:bg-white/20 rounded-xl text-sm font-semibold transition border border-white/20">
                            Tugas & Kuis
                        </a>
                        <a href="{{ route('guru.grades.index') }}" class="block w-full py-2.5 bg-white/10 hover:bg-white/20 rounded-xl text-sm font-semibold transition border border-white/20">
                            Penilaian
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</x-layouts.app>
