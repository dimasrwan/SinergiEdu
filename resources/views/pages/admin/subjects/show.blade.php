<x-layouts.app>
    <x-slot:title>Detail Mata Pelajaran - {{ $subject->name }}</x-slot:title>

    <div class="w-full max-w-4xl">
        <!-- Header & Breadcrumb -->
        <div class="mb-6 flex flex-col items-start gap-4">
            <a href="{{ route('admin.subjects.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-slate-800 gap-1.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
            <div class="w-full flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Detail Mata Pelajaran</h1>
                    <p class="mt-1 text-sm text-slate-500">Informasi rekap mata pelajaran beserta penugasan gurunya.</p>
                </div>
                <div class="flex gap-2 w-full sm:w-auto shrink-0">
                    <x-button variant="secondary" href="{{ route('admin.subjects.edit', $subject) }}" class="flex-1 sm:flex-none justify-center">Edit Mata Pelajaran</x-button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informasi Mata Pelajaran -->
            <div class="space-y-6">
                <x-card padding="lg">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-primary font-bold text-lg shadow-sm shrink-0">
                            {{ $subject->code }}
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">{{ $subject->name }}</h2>
                            <p class="text-sm font-semibold text-slate-500 mt-1">Master Data</p>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Distribusi Guru & Kelas -->
            <div class="space-y-6">
                <x-card padding="none" class="overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <svg class="h-5 w-5 text-accent" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                            Guru Pengampu & Kelas
                        </h3>
                    </div>

                    <div class="p-5">
                        @if(count($groupedTeachers) > 0)
                            <div class="space-y-4">
                                @foreach($groupedTeachers as $teacherName => $classes)
                                    <div class="flex flex-col sm:flex-row sm:items-start p-4 bg-slate-50 border border-slate-100 rounded-xl gap-3">
                                        <div class="flex-1">
                                            <p class="text-sm font-bold text-slate-900 mb-2">{{ $teacherName }}</p>
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach($classes as $className)
                                                    <span class="inline-flex text-[10px] font-bold text-slate-600 bg-white px-2 py-1 rounded-md border border-slate-200">
                                                        {{ $className }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="mx-auto w-12 h-12 bg-white border border-slate-100 rounded-full flex items-center justify-center text-slate-300 mb-3 shadow-sm">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                                </div>
                                <p class="text-sm font-bold text-slate-900">Belum ada penugasan guru</p>
                                <p class="text-xs text-slate-500 mt-1">Mata pelajaran ini belum didistribusikan ke kelas manapun.</p>
                            </div>
                        @endif
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.app>
