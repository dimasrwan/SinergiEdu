<x-layouts.app>
    <x-slot:title>Monitoring Kelas</x-slot:title>

    <div class="space-y-6">
        <x-page-header title="Monitoring Kelas" description="Pantau seluruh kelas akademik, total siswa terdaftar, dan rata-rata nilai kelas untuk tahun ajaran berjalan." />

        <x-table>
            <x-slot:head>
                <tr>
                    <th class="px-6 py-4">Nama Kelas</th>
                    <th class="px-6 py-4">Tingkat Kelas</th>
                    <th class="px-6 py-4 text-center">Jumlah Siswa</th>
                    <th class="px-6 py-4 text-center">Rata-rata Nilai Kelas</th>
                </tr>
            </x-slot:head>
            <x-slot:body>
                @forelse($classes as $class)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-950">{{ $class->name }}</td>
                        <td class="px-6 py-4">Tingkat {{ $class->grade_level }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 font-medium">
                                {{ $class->students_count }} Siswa
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-blue-700">
                            {{ $class->average_grade > 0 ? $class->average_grade : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 mb-4 ring-8 ring-slate-50/50">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.68 0-5.302.2-7.854.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800">Belum Ada Kelas</h3>
                            <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Tidak ada kelas terdaftar yang dapat dimonitor saat ini.</p>
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-table>
    </div>
</x-layouts.app>
