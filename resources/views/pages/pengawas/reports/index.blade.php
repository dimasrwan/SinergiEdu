<x-layouts.app>
    <x-slot:title>Laporan Kinerja Sekolah</x-slot:title>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <x-page-header title="Laporan Kinerja"
                description="Analisis hasil belajar agregat dan kelola dokumen laporan kinerja guru/sekolah." />
            <x-button href="{{ route('pengawas.reports.create') }}" variant="primary">
                Buat Laporan Kinerja
            </x-button>
        </div>

        @if(session('success'))
            <div
                class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm text-emerald-800 flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Tab Navigation --}}
        <div class="flex border-b border-slate-200">
            <button id="tab-analytics-btn"
                class="px-5 py-3 text-sm font-bold border-b-2 border-primary text-primary transition focus:outline-none">
                📊 Analisis Kinerja Akademik
            </button>
            <button id="tab-documents-btn"
                class="px-5 py-3 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition focus:outline-none">
                📄 Dokumen Laporan Resmi
            </button>
        </div>

        {{-- Tab Content 1: Analytics --}}
        <div id="tab-analytics" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Kiri: Peringkat Kelas --}}
                <div class="lg:col-span-1">
                    <x-card padding="none" class="h-full">
                        <div class="p-5 border-b border-slate-100">
                            <h3 class="text-base font-bold text-slate-900">Rata-rata Nilai per Kelas</h3>
                            @if($activeYear && $activeSemester)
                                <p class="text-xs text-slate-400 mt-0.5">{{ $activeYear->name }} ·
                                    {{ $activeSemester->name }}</p>
                            @endif
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-slate-50/50 border-b border-slate-100">
                                        <th
                                            class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">
                                            Peringkat</th>
                                        <th
                                            class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">
                                            Kelas</th>
                                        <th
                                            class="px-5 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wide">
                                            Rata-rata</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($classRankings as $idx => $rank)
                                        <tr class="hover:bg-slate-50/50">
                                            <td class="px-5 py-3 font-bold text-slate-900">#{{ $idx + 1 }}</td>
                                            <td class="px-5 py-3 font-semibold text-slate-800">{{ $rank['name'] }}</td>
                                            <td class="px-5 py-3 font-black text-primary text-right">
                                                {{ $rank['avg'] > 0 ? $rank['avg'] : '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-5 py-6 text-center text-slate-400 text-sm">Belum ada
                                                data nilai akademik.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </x-card>
                </div>

                {{-- Kanan: Metrik Kinerja Mengajar Guru --}}
                <div class="lg:col-span-2">
                    <x-card padding="none" class="h-full">
                        <div class="p-5 border-b border-slate-100">
                            <h3 class="text-base font-bold text-slate-900">Indikator Aktivitas & Kinerja Mengajar Guru
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">Metrik dihitung dari rata-rata nilai siswa, jumlah
                                tugas, dan umpan balik yang diberikan oleh guru.</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-slate-50/50 border-b border-slate-100">
                                        <th
                                            class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">
                                            Nama Guru</th>
                                        <th
                                            class="px-5 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wide">
                                            Rata-rata Nilai Siswa</th>
                                        <th
                                            class="px-5 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wide">
                                            Tugas Diberikan</th>
                                        <th
                                            class="px-5 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wide">
                                            Umpan Balik Guru</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($teacherMetrics as $teacher)
                                        <tr class="hover:bg-slate-50/50">
                                            <td class="px-5 py-3.5">
                                                <div class="font-semibold text-slate-800">{{ $teacher['name'] }}</div>
                                                <div class="text-xs text-slate-400">NIP: {{ $teacher['nip'] }}</div>
                                            </td>
                                            <td class="px-5 py-3.5 text-center">
                                                <span
                                                    class="font-bold text-primary">{{ $teacher['avg_score'] > 0 ? $teacher['avg_score'] : '–' }}</span>
                                            </td>
                                            <td class="px-5 py-3.5 text-center">
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-50 text-blue-700">{{ $teacher['assignments_count'] }}</span>
                                            </td>
                                            <td class="px-5 py-3.5 text-center">
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-50 text-emerald-700">{{ $teacher['feedbacks_count'] }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-5 py-6 text-center text-slate-400 text-sm">Belum ada
                                                data guru terdaftar.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>

        {{-- Tab Content 2: Written Documents --}}
        <div id="tab-documents" class="hidden space-y-6">
            <x-card padding="none">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th
                                    class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">
                                    Judul Laporan</th>
                                <th
                                    class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">
                                    Sasaran</th>
                                <th
                                    class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">
                                    Tanggal Dibuat</th>
                                <th
                                    class="px-5 py-3.5 text-right text-xs font-bold text-slate-500 uppercase tracking-wide">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($reports as $rep)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-slate-900">{{ $rep->title }}</div>
                                        <div class="text-xs text-slate-400 line-clamp-1 mt-0.5 max-w-sm">{{ $rep->content }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        @if($rep->teacher)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">Guru:
                                                {{ $rep->teacher->user?->name }}</span>
                                        @endif
                                        @if($rep->classroom)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-sky-50 text-sky-700 border border-sky-100">Kelas:
                                                {{ $rep->classroom->name }}</span>
                                        @endif
                                        @if(!$rep->teacher && !$rep->classroom)
                                            <span class="text-xs text-slate-400">Seluruh Sekolah</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-slate-500 text-xs">{{ $rep->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('pengawas.reports.show', $rep) }}"
                                                class="text-xs font-semibold text-primary hover:text-blue-800 transition flex items-center gap-0.5">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                                Lihat & Cetak
                                            </a>
                                            <a href="{{ route('pengawas.reports.edit', $rep) }}"
                                                class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition">Edit</a>
                                            <form action="{{ route('pengawas.reports.destroy', $rep) }}" method="POST"
                                                onsubmit="return confirm('Hapus laporan kinerja ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-xs font-semibold text-red-500 hover:text-red-700 transition">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center text-slate-400 text-sm">Belum ada dokumen
                                        laporan resmi yang dibuat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($reports->hasPages())
                    <div class="p-5 border-t border-slate-100">
                        {{ $reports->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const tabAnalyticsBtn = document.getElementById('tab-analytics-btn');
                const tabDocumentsBtn = document.getElementById('tab-documents-btn');
                const tabAnalyticsContent = document.getElementById('tab-analytics');
                const tabDocumentsContent = document.getElementById('tab-documents');

                tabAnalyticsBtn.addEventListener('click', () => {
                    tabAnalyticsBtn.className = "px-5 py-3 text-sm font-bold border-b-2 border-primary text-primary transition focus:outline-none";
                    tabDocumentsBtn.className = "px-5 py-3 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition focus:outline-none";
                    tabAnalyticsContent.classList.remove('hidden');
                    tabDocumentsContent.classList.add('hidden');
                });

                tabDocumentsBtn.addEventListener('click', () => {
                    tabDocumentsBtn.className = "px-5 py-3 text-sm font-bold border-b-2 border-primary text-primary transition focus:outline-none";
                    tabAnalyticsBtn.className = "px-5 py-3 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition focus:outline-none";
                    tabDocumentsContent.classList.remove('hidden');
                    tabAnalyticsContent.classList.add('hidden');
                });
            });
        </script>
    @endpush
</x-layouts.app>