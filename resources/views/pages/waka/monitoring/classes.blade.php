<x-layouts.app>
    <x-slot:title>Monitoring Kelas</x-slot:title>

    <div class="space-y-6 min-w-0">
        <x-page-header title="Monitoring Kelas" description="Pantau seluruh kelas akademik, total siswa terdaftar, dan rata-rata nilai kelas untuk tahun ajaran berjalan." />

        <x-card padding="none" class="overflow-hidden border border-slate-200/75 min-w-0">
            <!-- Desktop Table (hidden lg:block) -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Nama Kelas</th>
                            <th class="px-6 py-4">Tingkat Kelas</th>
                            <th class="px-6 py-4 text-center">Jumlah Siswa</th>
                            <th class="px-6 py-4 text-center">Partisipasi</th>
                            <th class="px-6 py-4 text-center">Rata-rata Nilai Kelas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($classes as $class)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-900">{{ $class->name }}</td>
                                <td class="px-6 py-4 text-slate-600">Tingkat {{ $class->grade_level }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-medium">
                                        {{ $class->students_count }} Siswa
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                     <div class="w-full bg-slate-200 rounded-full h-2 max-w-[100px] mx-auto overflow-hidden">
                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $class->participation_rate }}%"></div>
                                    </div>
                                    <span class="text-xs text-slate-500 mt-1 block">{{ $class->participation_rate }}%</span>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-primary text-base">
                                    {{ $class->average_grade > 0 ? number_format($class->average_grade, 1) : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada kelas terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View (lg:hidden) -->
            <div class="lg:hidden p-4 space-y-3.5 divide-y divide-slate-100">
                @forelse($classes as $class)
                    <div class="pt-3.5 first:pt-0 space-y-3 min-w-0">
                        <div class="flex items-center justify-between min-w-0">
                            <div>
                                <h3 class="font-bold text-slate-900 text-base truncate">{{ $class->name }}</h3>
                                <span class="text-xs text-slate-500 font-medium">Tingkat {{ $class->grade_level }}</span>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-50 text-primary text-xs font-bold shrink-0">
                                {{ $class->students_count }} Siswa
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 text-xs pt-1">
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Partisipasi Belajar</span>
                                <div class="flex items-center gap-2 mt-1.5">
                                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $class->participation_rate }}%"></div>
                                    </div>
                                    <span class="font-bold text-slate-700 shrink-0">{{ $class->participation_rate }}%</span>
                                </div>
                            </div>
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex flex-col justify-between">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Rata-rata Kelas</span>
                                <span class="text-lg font-black text-primary leading-tight mt-1">
                                    {{ $class->average_grade > 0 ? number_format($class->average_grade, 1) : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400 text-sm">
                        Belum ada kelas terdaftar.
                    </div>
                @endforelse
            </div>
        </x-card>
    </div>
</x-layouts.app>
